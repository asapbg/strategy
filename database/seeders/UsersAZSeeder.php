<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersAZSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'email' => 'is.ivanov@government.bg',
                'first_name' => 'Искрен',
                'last_name' => 'Иванов',
                'role' => 'super-admin',
            ],
            [
                'email' => 'joro.penchev@gmail.com',
                'first_name' => 'Joro',
                'last_name' => 'Penchev',
                'role' => 'super-admin',
            ]
        ];

        foreach ($data as $item) {
            $exist = User::where('email' , '=', $item['email'])->first();
            if( !$exist ) {
                $user = new User;
                $user->first_name = $item['first_name'];
                $user->last_name = $item['last_name'];
                $user->username = $item['email'];
                $user->email = $item['email'];
                $user->password = bcrypt('1234qweR@');
                $user->email_verified_at = Carbon::now();
                $user->password_changed_at = Carbon::now();
                $user->user_type = User::USER_TYPE_INTERNAL;
                $user->save();
                $this->command->info("User with email: $user->email saved");

                $role = Role::where('name', $item['role'])->first();
                $user->assignRole($role);
                $this->command->comment("User: ".$item['email']." created.");
            }
        }
    }
}
