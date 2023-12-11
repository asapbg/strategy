<?php

namespace App\Console\Commands;

use App\Models\CustomRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class seedOldUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy users to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        DB::table('model_has_roles')->truncate();
//        DB::table('users')->truncate();

        $formatTimestamp = 'Y-m-d H:i:s';
        //TODO missing roles ??????
        $mappingRoles = [
            1 => 2, //Системен администратор => Супер Администратор
            2 => 9, //Регистриран потребител => Външен потребител
            3 => 2,	//Администратор дискусии => Супер Администратор
            4 => 2,	//Администратор файлове => Супер Администратор
            5 => 4, //Модератор страт. документи => Модератор „Стратегически документи"
            6 => 3,	//Модератор общ. консултации => Модератор „Обществени консултации“
        ];
        $externalRoleId = CustomRole::where('name', '=', CustomRole::EXTERNAL_USER_ROLE)->first()->id;
        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy')->select('select max(users.userid) from users');
        //start from this id in old database
        $currentStep = (int)DB::table('users')->select(DB::raw('max(old_id) as max'))->first()->max + 1;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('old_strategy')
                    ->select('select
                        -- usercategoryaccess table ?????
                        -- institution_id ??????????????
                        u.userid as old_id,
                        u.username as username,
                        case when profile.organization is not null then 1 else 0 end as is_org
                        profile.organization as org_name,
                        case when profile.firstname is null then u.username else profile.firstname as first_name,
                        profile.lastname as last_name,
                        -- user_type 1 - internal 2 - external
                        m.email,
                        -- 3 - STATUS_BLOCKED, 1 STATUS_ACTIVE
                        case when m.islockedout = true then 3 else 1 end as activity_status,
                        -- email_verified_at
                        profile.phone,
                        -- description
                        m."password" as password,
                        m.lastpasswordchangeddate as password_changed_at,
                        u.lastactivitydate as last_login_at,
                        1 as active,
                        m.createdate  as created_at,
                        -- updated_at
                        -- person_identity
                        -- company_identity
                        json_agg(json_build_object(\'id\', roles.roleid , \'name\', roles.rolename, \'description\', roles.description)) as roles
                    from dbo.users u
                    join dbo.membership m on m.userid = u.userid
                    join dbo.usersinroles uroles on uroles.userid = u.userid
                    join dbo.roles roles on roles.roleid = uroles.roleid
                    left join dbo.profile profile on profile.userid = u.userid
                    -- order by u.userid
                    group by u.userid, m.userid, profile.userid');

                if (sizeof($oldDbResult)) {
                    DB::beginTransaction();
                    try {
                        foreach ($oldDbResult as $item) {
                            $newUserRoles = [];
                            $prepareNewUser = [
                                'old_id' => $item->old_id,
                                'username' => $item->username,
                                'is_org' => $item->is_org,
                                'org_name' => $item->org_name,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'user_type' => null,
                                'email' => $item->email,
                                'phone' => $item->phone,
                                'activity_status' => $item->activity_status,
                                'email_verified_at' => null,
                                'password' => Hash::make($item->password),
                                'password_changed_at' => !empty($item->password_changed_at) ? Carbon::parse($item->password_changed_at)->format($formatTimestamp) : null,
                                'last_login_at' => !empty($item->last_login_at) ? Carbon::parse($item->last_login_at)->format($formatTimestamp) : null,
                                'active' => (bool)$item->active,
                                'institution_id' => null, //TODO get institution ????? we can get this by author if we receive a mapping for user institution to IISDA
                            ];

                            $roles = json_decode($item->roles, true);

                            if(sizeof($roles)) {
                                foreach ($roles as $r) {
                                    if(isset($mappingRoles[$r['id']])) {
                                        $newUserRoles[] = $mappingRoles[$r['id']];
                                    } else{
                                        echo 'Missing role: '.$r['name'].' with ID '.$r['id'].PHP_EOL;
                                    }
                                }
                                if(sizeof($newUserRoles)) {
                                    if(sizeof($newUserRoles) == 1 && $newUserRoles[0] == $externalRoleId) {
                                        $prepareNewUser['user_type'] = User::USER_TYPE_EXTERNAL;
                                    } else{
                                        $prepareNewUser['user_type'] = User::USER_TYPE_INTERNAL;
                                    }
                                }
                            }

                            $newUser = User::create($prepareNewUser);
                            if($newUser && sizeof($newUserRoles)) {
                                $newUser->assignRole($newUserRoles);
                                $newUser->save();
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error('Migration old startegy users: ' . $e);
                        DB::rollBack();
                        dd($prepareNewUser, $roles);
                    }
                }
                $currentStep += $step;
            }
        }

        Artisan::call('db:seed UsersSeeder');
        Artisan::call('db:seed UsersAZSeeder');
    }
}
