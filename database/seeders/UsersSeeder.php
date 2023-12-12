<?php

namespace Database\Seeders;

use App\Models\CustomRole;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // make asap user with admin role
        if(!User::where('email', '=', 'admin@asap.bg')->first()) {
            $user = new User;
            $user->first_name = 'Asap';
            $user->last_name = 'Admin';
            $user->username = "asap-admin";
            $user->email = 'admin@asap.bg';
            $user->password = bcrypt('pass123');
            $user->email_verified_at = Carbon::now();
            $user->password_changed_at = Carbon::now();
            $user->user_type = User::USER_TYPE_INTERNAL;
            $user->save();

            $this->command->info("User with email: $user->email saved");
            $role = Role::where('name', 'super-admin')->first();
            $user->assignRole($role);
            $this->command->info("Role $role->name was assigned to $user->first_name $user->last_name");
        }

        if(!User::where('email', '=', 'service-user@asap.bg')->first()) {
            // make asap user with service_user role
            $user = new User;
            $user->first_name = 'Asap';
            $user->last_name = 'Service User';
            $user->username = "service_user";
            $user->email = 'service-user@asap.bg';
            $user->password = bcrypt('pass123');
            $user->email_verified_at = Carbon::now();
            $user->password_changed_at = Carbon::now();
            $user->user_type = User::USER_TYPE_INTERNAL;
            $user->save();

            $this->command->info("User with email: $user->email saved");

            $role = Role::where('name', CustomRole::SUPER_USER_ROLE)->first();
            $user->assignRole($role);

            $this->command->info("Role $role->name was assigned to $user->first_name $user->last_name");
        }

        // make moderator users
        $moderators = [
            'advisory',
            'strategic',
            'legal',
            'advisory-boards',
            'advisory-board',
            'partnership'
        ];

        foreach ($moderators as $section) {
            if(!User::where('email', '=', 'moderator-' . $section . '@asap.bg')->first()) {
                $user = new User;
                $user->first_name = Role::whereName('moderator-' . $section)->first()->display_name;
                $user->last_name = '';
                $user->username = "moderator-$section";
                $user->email = 'moderator-' . $section . '@asap.bg';
                $user->password = bcrypt('pass123');
                $user->email_verified_at = Carbon::now();
                $user->password_changed_at = Carbon::now();
                $user->user_type = User::USER_TYPE_INTERNAL;
                $user->save();

                $this->command->info("User with email: $user->email saved");

                $role = Role::whereName('moderator-' . $section)->first();
                $user->assignRole($role);

                $this->command->info("Role $role->name was assigned to $user->first_name $user->last_name");
            }
        }
    }
}
