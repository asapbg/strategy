<?php

namespace App\Console\Commands;

use App\Models\CustomRole;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
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
        Artisan::call('db:seed UsersSeeder');
        Artisan::call('db:seed UsersAZSeeder');

        $formatTimestamp = 'Y-m-d H:i:s';
        //TODO missing roles ??????
        $mappingRoles = [
            1 => 2, //Системен администратор => Супер Администратор
            2 => 9, //Регистриран потребител => Външен потребител
            3 => 0,	//Администратор дискусии
            4 => 0,	//Администратор файлове
            5 => 4, //Модератор страт. документи => Модератор „Стратегически документи"
            6 => 3,	//Модератор общ. консултации => Модератор „Обществени консултации“
        ];
        $externalRoleId = CustomRole::where('name', '=', CustomRole::EXTERNAL_USER_ROLE)->first()->id;

        //Institutions
        $institutions = [
            ':FIL' => null,
            ':RE' => null,
            'ДА' => null,
            'ДАЕЕР' => null,
            'ДАИТС' => null,
            'ЕВ' => null,
            'зам. министър-председателят' =>null,
            'зам. министър-председателят и председател на ЦКБППМН' => null,
            'и.д. главен секретар на МС' => null,
            'М3' => 131,
            'МВнР' => 127,
            'МВР' => 127,
            'МДА' => null,
            'МДААР' => null,
            'МДПБА' => null,
            'МЕВ' => null,
            'МЕЕР' => null,
            'МЗ' => null,
            'МЗГ' => null,
            'МЗГАР' => null,
            'МЗГБ' => null,
            'МЗГП' => null,
            'МЗП' => null,
            'МЗХ' => 132,
            'МИ' => null,
            'МИЕ' => null,
            'МИЕТ' => null,
            'министър без портфейл' => null,
            'министър без портфейл (Ал. Праматарски)' => null,
            'министър без портфейл (Б. Димитров)' => null,
            'министър без портфейл (М. Кунева)' => null,
            'министър без портфейл (Н. Моллов)' => null,
            'министър без портфейл (Ф. Хюсменова)' => null,
            'министър-председателят' => 126,
            'МИС' => null,
            'МК' => 135,
            'МКТ' => null,
            'ММС' => 136,
            'МНО' => null,
            'МО' => 139,
            'МОМН' => null,
            'МОН' => 137,
            'МОСВ' => 138,
            'МП' => 140,
            'МППЕИ' => null,
            'МПр' => null,
            'МРРБ' => 141,
            'МС' => null,
            'МТ' => 144,
            'МТИТС' => 142,
            'МТС' => 142,
            'МТСГ' => null,
            'МТСП' => 143,
            'МТТ' => null,
            'МФ' => 145,
            'МФВС' => null,
            'Администрация на МС' => 126,
            'Агенция за публичните предприятия и контрол' => 3,
            'Българския институт по метрология' => 22,
            'Главна дирекция &quot;Гражданска въздухоплавателна администрация&quot;' => 26,
            'Държавна агенция &quot;Архиви&quot;' => 49,
            'Държавна агенция &quot;Безопасност на движението по пътищата&quot;' => 50,
            'Държавна агенция „Безопасност на движението по пътищата“' => 50,
            'Държавна агенция &quot;Държавен резерв и военновременни запаси&quot;' => 51,
            'Държавна агенция &quot;Национална сигурност&quot;' => 55,
            'Държавна агенция &quot;Разузнаване&quot;' => 56,
            'Държавна агенция &quot;Технически операции&quot;' => 57,
            'Държавна агенция за бежанците' => 52,
            'Държавна агенция за закрила на детето' => 53,
            'Държавна агенция за метрологичен и технически надзор' => 54,
            'Държавна комисия по сигурността на информацията' => 58,
            'Комисия за защита на конкуренцията' => 93,
            'Комисия за защита на личните данни' => 94,
            'Комисия за отнемане на незаконно придобито имущество' => 98,
            'Комисия за противодействие на корупцията и за отнемане на незаконно придобитото имущество' => 98,
            'Комисия за публичен надзор на регистрираните одитори' => 99,
            'Комисия за регулиране на съобщенията' => 102,
            'Министерски съвет' => 126,
            'Министерство на вътрешните работи' => 128,
            'Министерство на електронното управление' => 129,
            'Министерство на енергетиката' => 130,
            'Министерство на здравеопазването' => 131,
            'Министерство на земеделието' => 132,
            'Министерство на икономиката' => 133,
            'Министерство на икономиката и индустрията' => 133,
            'КЕВР' => 92,
            'АМС, дирекция &quot;Стратегическо планиране&quot;' => 126,
            'Държавна агенция &quot;Електронно управление&quot;' => 129,
            'Министерство на иновациите и растежа' => 134,
            'Министерство на културата' => 135,
            'Министерство на младежта и спорта' => 136,
            'Министерство на отбраната' => 139,
            'Министерство на правосъдието' => 140,
            'Министерство на труда и социалната политика' => 143,
            'Министерство на туризма' => 144,
            'Министерство на финансите' => 145,
            'Министерството на иновациите и растежа' => 134,
            'Министерстрво на културата' => 135,
            'Национален статистически институт' => 160,
            'Национален Статистически институт' => 160,
            'Национален съвет по цени и реимбурсиране на лекарствените продукти' => 185,
            'Национална служба за охрана' => 196,
            'Патентно ведомство' => 525,
            'ME' => 130,
            'MT' => 144,
            'АМС' => 126,
            'АЯР' => 7,
            'ДАБ' => 52,
            'ДАБЧ' => 72,
            'ДМА, АМС' => 126,
        ];


        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.users.userid) from dbo.users');
        //start from this id in old database
        $currentStep = (int)DB::table('users')->select(DB::raw('max(old_id) as max'))->first()->max + 1;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('old_strategy_app')
                    ->select('select
                        -- usercategoryaccess table ?????
                        -- institution_id ??????????????
                        u.userid as old_id,
                        u.username as username,
                        case when profile.organization is not null then 1 else 0 end as is_org,
                        profile.organization as org_name,
                        case when profile.firstname is null then u.username else profile.firstname end as first_name,
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
                    where u.userid >= '.(int)$currentStep.'
                    -- order by u.userid
                    group by u.userid, m.userid, profile.userid');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        DB::beginTransaction();
                        try {
                            $newUserRoles = [];
                            $duplicated = User::where('email', '=', $item->email)->first();
                            $prepareNewUser = [
                                'old_id' => $item->old_id,
                                'username' => $item->username,
                                'is_org' => $item->is_org,
                                'org_name' => $item->org_name,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'user_type' => null,
                                'email' => $duplicated ? 'duplicated-'.$item->email : $item->email,
                                'phone' => $item->phone,
                                'activity_status' => $item->activity_status,
                                'email_verified_at' => null,
                                'password' => Hash::make($item->password),
                                'password_changed_at' => !empty($item->password_changed_at) ? Carbon::parse($item->password_changed_at)->format($formatTimestamp) : null,
                                'last_login_at' => !empty($item->last_login_at) ? Carbon::parse($item->last_login_at)->format($formatTimestamp) : null,
                                'active' => (bool)$item->active,
                                'institution_id' => $institutions[$item->org_name] ?? null,
                            ];

                            $roles = json_decode($item->roles, true);

                            if (sizeof($roles)) {
                                foreach ($roles as $r) {
                                    if (isset($mappingRoles[$r['id']])) {
                                        $newUserRoles[] = $mappingRoles[$r['id']];
                                    } else {
                                        echo 'Missing role: ' . $r['name'] . ' with ID ' . $r['id'] . PHP_EOL;
                                    }
                                }
                                //users in old system do not have external or internal flag.
                                // We check roles and set this property depending on roles that we found for each user

                                if (sizeof($newUserRoles)) {
                                    if (sizeof($newUserRoles) == 1 && $newUserRoles[0] == $externalRoleId) {
                                        $prepareNewUser['user_type'] = User::USER_TYPE_EXTERNAL;
                                    } else {
                                        $newUserRoles = array_filter($newUserRoles, fn($m) => $m != 0);
                                        $prepareNewUser['user_type'] = User::USER_TYPE_INTERNAL;
                                    }
                                } else {
                                    //if no roles found save as external user
                                    $prepareNewUser['user_type'] = User::USER_TYPE_EXTERNAL;
                                }
                            }

                            $newUser = User::create($prepareNewUser);
                            if ($newUser && sizeof($newUserRoles)) {
                                $newUser->assignRole($newUserRoles);
                                $newUser->save();
                            }

                            $this->comment('User with old id (' . $newUser->old_id . ') is created');
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Migration old startegy users: ' . $e);
                            DB::rollBack();
                            dd($prepareNewUser, $roles);
                        }
                    }
                }
                $currentStep += $step;
            }
        }
    }
}
