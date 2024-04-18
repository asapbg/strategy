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
        file_put_contents('institutions_for_mapping_user.txt', '');
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
            'зам. министър-председателят' => null,
            'зам. министър-председателят и председател на ЦКБППМН' => null,
            'и.д. главен секретар на МС' => null,
            'М3' => 132,
            'МВнР' => 128,
            'МВР' => 129,
            'МДА' => 127,
            'МДААР' => null,
            'МДПБА' => null,
            'МЕВ' => null,
            'МЕЕР' => null,
            'МЗ' => 132,
            'МЗГ' => null,
            'МЗГАР' => null,
            'МЗГБ' => null,
            'МЗГП' => null,
            'МЗП' => null,
            'МЗХ' => 133,
            'МИ' => null,
            'МИЕ' => null,
            'МИЕТ' => null,
            'министър без портфейл' => null,
            'министър без портфейл (Ал. Праматарски)' => null,
            'министър без портфейл (Б. Димитров)' => null,
            'министър без портфейл (М. Кунева)' => null,
            'министър без портфейл (Н. Моллов)' => null,
            'министър без портфейл (Ф. Хюсменова)' => null,
            'министър-председателят' => null,
            'МИС' => null,
            'МК' => 136,
            'МКТ' => null,
            'ММС' => 137,
            'МНО' => null,
            'МО' => 140,
            'МОМН' => null,
            'МОН' => 138,
            'МОСВ' => 139,
            'МП' => 141,
            'МППЕИ' => null,
            'МПр' => null,
            'МРРБ' => 142,
            'МС' => 127,
            'МТ' => 145,
            'МТИТС' => 142,
            'МТС' => 143,
            'МТСГ' => null,
            'МТСП' => 144,
            'МТТ' => null,
            'МФ' => 146,
            'МФВС' => null,
            'Администрация на МС' => 127,
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
            'Министерски съвет' => 127,
            'Министерство на вътрешните работи' => 129,
            'Министерство на електронното управление' => 130,
            'Министерство на енергетиката' => 131,
            'Министерство на здравеопазването' => 132,
            'Министерство на земеделието' => 133,
            'Министерство на икономиката' => null,
            'Министерство на икономиката и индустрията' => 134,
            'КЕВР' => 92,
            'АМС, дирекция &quot;Стратегическо планиране&quot;' => 127,
            'Държавна агенция &quot;Електронно управление&quot;' => null,
            'Министерство на иновациите и растежа' => 135,
            'Министерство на културата' => 136,
            'Министерство на младежта и спорта' => 137,
            'Министерство на отбраната' => 140,
            'Министерство на правосъдието' => 141,
            'Министерство на труда и социалната политика' => 144,
            'Министерство на туризма' => 145,
            'Министерство на финансите' => 146,
            'Министерството на иновациите и растежа' => 135,
            'Министерстрво на културата' => 136,
            'Национален статистически институт' => 162,
            'Национален Статистически институт' => 162,
            'Национален съвет по цени и реимбурсиране на лекарствените продукти' => 187,
            'Национална служба за охрана' => 198,
            'Патентно ведомство' => 527,
            'ME' => null,
            'MT' => null,
            'АМС' => 127,
            'АЯР' => null,
            'ДАБ' => null,
            'ДАБЧ' => null,
            'ДМА, АМС' => 127,
            'Областна администрация - Пазарджик' => 215,
            'Община Тунджа' => 499,
            'община Куклен' => 383,
            'ССББ' => null,
            'НСМСБ' => null,
            'РИОСВ' => null,
            'БИТСП' => null,
            'СРМ' => null,
            'Дирекция &quot;НП Централен Балкан&quot;' => 46,
            'общинска администрация брезово' => 295,
            'Община Бойчиновци' => 285,
            'БХК' => null,
            'Министерство на транспорта' => 143,
            'Министерство на транспорта и съобщенията' => 143,
            'MEE' => null,
            'министерство на отбраната' => 140,
            'НАП' => 192,
            'AEP' => null,
            'Министерство на oколната среда и водите' => 139,
            'община Елена' => 345,
            'Община Самоков' => 456,
            'Община Царево' => 510,
            'Общинска администрация гр.Банско' => 272,
            'Община Девня' => 329,
            'BBCMB' => null,
            'Държавна агенция закрила на детето' => 53,
            'Националното бюро за контрол на специалните разузнавателни средства' => 202,
            'Община Димитровград' => 331,
            'Община Хайредин' => 504,
            'Община Белица' => 275,
            'MO' => 140,
            'Община Априлци' => 267,
            'Община Казанлък' => 360,
            'Община Брусарци' => 296,
            'община Павел баня' => 423,
            'Община Пещера' => 430,
            'Община Върбица' => 312,
            'Община Долна Митрополия' => 336,
            'община Сливница' => 471,
            'Община Копривщица' => 372,
            'Община Ихтиман' => 358,
            'Община Мирково' => 406,
            'Община Луковит' => 393,
            'Община Исперих' => 357,
            'Община Черноочене' => 516,
            'Община Велики Преслав' => 302,
            'община Стралджа' => 483,
            'Община Стамболийски' => 478,
            'Община Велинград' => 304,
            'Община Летница' => 389,
            'Община Чавдар' => 512,
            'Община Баните' => 271,
            'Община Велико Търново' => 303,
            'община Болярово' => 286,
            'Община &quot;Тунджа&quot;' => 499,
            'община Първомай' => 441,
            'Община Пордим' => 437,
            'РИОСВ - Пловдив' => 614,
            'Община Белене' => 274,
            'Община Роман' => 450,
            'Централна избирателна комисия' => 701,
            'Община Доспат' => 339,
            'Община Троян' => 496,
            'община Крумовград' => 380,
            'Община Приморско' => 439,
            'Община Карнобат' => 366,
            'Община Никола Козлево' => 413,
            'Община Сопот' => 476,
            'Общинска администрация - Симеоновград' => 467,
            'Община Видин' => 308,
            'ОБЩИНА БОРОВАН' => 288,
            'Областна администрация-Плевен' => 217,
            'Община Борован' => 288,
            'Община Хисаря' => 507,
            'Министерски Съвет' => 127,
            'Община Сливен' => 470,
            'община Марица' => 401,
            'община Сунгурларе' => 487,
            'Община Никопол' => 415,
            'Министрество на външните работи' => 128,
            'Областна администрация Варна' => 205,
            'ИАРА' => 82,
            'Областна администрация на област Хасково' => 228,
            'НСИ' => 162,
            'Комисия за публичен надзор над регистрираните одитори' => 99,
            'Община Попово' => 436,
            'Агенция Митници' => 8,
            'Агенция &quot;Митници&quot;' => 8,
            'МОРСКА АДМИНИСТРАЦИЯ' => 76,
            'ИА&quot;МОРСКА АДМИНИСТРАЦИЯ&quot;' => 76,
            'Община Елин Пелин' => 346,
            'НОИ' => 161,
            'Община Елена' => 345,
            'община Аксаково' => 263,
            'Областна администрация - Смолян' => 223,
            'Министерство на регионалното развитие и благоустройството' => 142,
            'AZ' => 11,
            'Община Рила' => 448,
            'Община Дряново' => 341,
            'Община Антоново' => 266,
            'ОБЩИНА ДРАГОМАН' => 340,
            'ОБЩИНА ЦАР КАЛОЯН' => 509,
            'Община Варна' => 301,
            'Община Пловдив' => 433,
            'община Пловдив' => 433,
            'ОбА - Симеоновград' => 467
        ];


        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.users.userid) from dbo.users');
        //start from this id in old database
//        $currentStep = (int)(DB::table('users')->select(DB::raw('max(old_id) as max'))->first()->max) + 1;
        $currentStep = 0;

        $ourUsers = User::withTrashed()->where('email', 'not like', '%duplicated-%')->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();
        $missingInstitution = array();

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
                    and u.userid < '. ($currentStep + $step) .'
                    group by u.userid, m.userid, profile.userid
                    order by u.userid asc');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        if(!isset($institutions[$item->org_name]) || is_null($institutions[$item->org_name])){
                            $missingInstitution[$item->org_name] = $item->org_name;
                        }
                        if(!isset($ourUsers[(int)$item->old_id])){
                            $duplicated = User::where('email', '=', $item->email)->first();
                            $duplicatedOur = User::withTrashed()->where('email', '=', 'duplicated-'.$item->email)->first();
                            if($duplicatedOur){
                                continue;
                            }
                            DB::beginTransaction();
                            try {
                                $newUserRoles = [];
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
                            }
                        } else{
                            //Update institutions
                            $existingUser = User::find((int)$ourUsers[(int)$item->old_id]);
                            if($existingUser){
                                $existingUser->institution_id = $institutions[$item->org_name] ?? null;
                                $existingUser->save();
                                $this->comment('User with old id (' . $existingUser->old_id . ') is updated');
                            } else{
                                $this->comment('Cant\'t find old user OldId ('.(int)$item->old_id.') OurId (' . $ourUsers[(int)$item->old_id] . ')');
                            }
                        }
                    }
                }
                $currentStep += $step;
            }
        }
        if(sizeof($missingInstitution)){
            foreach ($missingInstitution as $mi){
                file_put_contents('institutions_for_mapping_user.txt', $mi . PHP_EOL, FILE_APPEND);
            }
        }
        $this->comment('Operation complete');
    }
}
