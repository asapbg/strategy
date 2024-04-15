<?php

namespace App\Console\Commands;

use App\Enums\PrisDocChangeTypeEnum;
use App\Models\InstitutionLevel;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOldPrisImporters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pris_importers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix old PRIS importers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents('institutions_for_mapping_last_pris.txt', '');
        $institutionForMapping = [];
        //Create default institution
        $diEmail = 'magdalena.mitkova+egov@asap.bg';
        $dInstitution = Institution::where('email', '=', $diEmail)->withTrashed()->first();
        $locales = config('available_languages');
        if(!$dInstitution) {
            $insLevel = InstitutionLevel::create([
                'system_name' => 'default'
            ]);
            if(!$insLevel) {
                $this->error('Cant create default institution');
            }
            if($insLevel) {
                foreach ($locales as $locale) {
                    $insLevel->translateOrNew($locale['code'])->name = 'Default Level';
                }
                $insLevel->save();
            }

            $dInstitution = Institution::create([
                'email' => $diEmail,
                'institution_level_id' => $insLevel->id
            ]);

            if(!$dInstitution) {
                $this->error('Cant create default institution');
            }
            foreach ($locales as $locale) {
                $dInstitution->translateOrNew($locale['code'])->name = 'Default';
            }
            $dInstitution->save();
        }
        $ourPris = Pris::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        $importers = [
            'МВнР, МРР' => [
                'importer' => 'МВнР, МРР',
                'institution_id' => [128, 0]
            ],
            'МРР, МО, МИЕ' => [
                'importer' => 'МРР, МО, МИЕ',
                'institution_id' => [0, 140, 0]
            ],
            'МЗ, МРР' => [
                'importer' => 'МЗ, МРР',
                'institution_id' => [132, 0]
            ],
            'ММС, МРР' => [
                'importer' => 'ММС, МРР',
                'institution_id' => [137, 0]
            ],
            'МФ,  МРР' => [
                'importer' => 'МФ,  МРР',
                'institution_id' => [146, 0]
            ],
            'МК,  МРР' => [
                'importer' => 'МК,  МРР',
                'institution_id' => [136, 0]
            ],
            'МРР, МО' => [
                'importer' => 'МРР, МО',
                'institution_id' => [0, 140]
            ],
            'ЗМПИР, МТСП' => [
                'importer' => 'ЗМПИР, МТСП',
                'institution_id' => [0, 144]
            ],
            'МРР, МК' => [
                'importer' => 'МРР, МК',
                'institution_id' => [0, 136]
            ],
            'МЗПИР, МИЕ' => [
                'importer' => 'МЗПИР, МИЕ',
                'institution_id' => [0, 0]
            ],
            'МО, МРР' => [
                'importer' => 'МО, МРР',
                'institution_id' => [140, 0]
            ],
            'ЗМП, МП' => [
                'importer' => 'ЗМП, МП',
                'institution_id' => [0, 141]
            ],
            'ЗМП, МВР' => [
                'importer' => 'ЗМП, МВР',
                'institution_id' => [0, 129]
            ],
            'МРР, МЗХ' => [
                'importer' => 'МРР, МЗХ',
                'institution_id' => [0, 133]
            ],
            'МРР, МИЕ' => [
                'importer' => 'МРР, МИЕ',
                'institution_id' => [0, 0]
            ],
            'МВР, МРР' => [
                'importer' => 'МВР, МРР',
                'institution_id' => [129, 0]
            ],
            'МЗХ, МРР' => [
                'importer' => 'МЗХ, МРР',
                'institution_id' => [133, 0]
            ],
            'МК, МРР' => [
                'importer' => 'МК, МРР',
                'institution_id' => [136, 0]
            ],
            'МФ, МРР' => [
                'importer' => 'МФ, МРР',
                'institution_id' => [146, 0]
            ],
            'МРР, МИП' => [
                'importer' => 'МРР, МИП',
                'institution_id' => [0, 0]
            ],
            'МИЕ, МРР' => [
                'importer' => 'МИЕ, МРР',
                'institution_id' => [0, 0]
            ],
            'МРР, МФ' => [
                'importer' => 'МРР, МФ',
                'institution_id' => [0, 146]
            ],
            'МИЕТ, МРЕП' => [
                'importer' => 'МИЕТ, МРЕП',
                'institution_id' => [0, 0]
            ],
            'МУСЕС, МТИТС' => [
                'importer' => 'МУСЕС, МТИТС',
                'institution_id' => [0, 0]
            ],
            'МИЕТ, МУСЕС' => [
                'importer' => 'МИЕТ, МУСЕСС',
                'institution_id' => [0, 0]
            ],
            'МТИТС, МУСЕС' => [
                'importer' => 'МТИТС, МУСЕС',
                'institution_id' => [0, 0]
            ],
            'ВОМН, МРРБ' => [
                'importer' => 'ВОМН, МРРБ',
                'institution_id' => [0, 142]
            ],
            'МРРБ МО' => [
                'importer' => 'МРРБ МО',
                'institution_id' => [142, 140]
            ],
            'МЗ МОН МЗП' => [
                'importer' => 'МЗ МОН МЗП',
                'institution_id' => [132, 138, 0]
            ],
            'МИС МФ' => [
                'importer' => 'МИС МФ',
                'institution_id' => [0, 146]
            ],
            'МФ МРРБ МТ' => [
                'importer' => 'МФ МРРБ МТ',
                'institution_id' => [146, 142, 145]
            ],
            'МРРБ МФ' => [
                'importer' => 'МРРБ МФ',
                'institution_id' => [142, 146]
            ],
            'МВнР МТ МИЕ' => [
                'importer' => 'МВнР МТ МИЕ',
                'institution_id' => [128, 145, 0]
            ],
            'МРРБ МТСП МИЕ' => [
                'importer' => 'МРРБ МТСП МИЕ',
                'institution_id' => [142, 144, 0]
            ],
            'МВнР МФ МТ' => [
                'importer' => 'МВнР МФ МТ',
                'institution_id' => [128, 146, 145]
            ],
            'МВнР МО' => [
                'importer' => 'МВнР МО',
                'institution_id' => [128, 140]
            ],
            'МО МИЕ' => [
                'importer' => 'МО МИЕ',
                'institution_id' => [140, 0]
            ],
            'МВнР МТСП' => [
                'importer' => 'МВнР МТСП',
                'institution_id' => [128, 144]
            ],
            'МП МО' => [
                'importer' => 'МП МО',
                'institution_id' => [141, 140]
            ],
            'МЗ МРРБ' => [
                'importer' => 'МЗ МРРБ',
                'institution_id' => [132, 142]
            ],
            'МО МФ' => [
                'importer' => 'МО МФ',
                'institution_id' => [140, 146]
            ],
            'МВнР МК' => [
                'importer' => 'МВнР МК',
                'institution_id' => [128, 136]
            ],
            'МВнР МИЕ' => [
                'importer' => 'МВнР МИЕ',
                'institution_id' => [128, 0]
            ],
            'МЕВ МВР' => [
                'importer' => 'МЕВ МВР',
                'institution_id' => [0, 129]
            ],
            'МЗ МДПБА' => [
                'importer' => 'МЗ МДПБА',
                'institution_id' => [132, 0]
            ],
            'МК МВнР' => [
                'importer' => 'МК МВнР',
                'institution_id' => [136, 128]
            ],
            'МК МОН' => [
                'importer' => 'МК МОН',
                'institution_id' => [136, 138]
            ],
            'МИЕ МВнР' => [
                'importer' => 'МИЕ МВнР',
                'institution_id' => [0, 128]
            ],
            'МТСП МФ' => [
                'importer' => 'МТСП МФ',
                'institution_id' => [144, 146]
            ],
            'МИ МОН' => [
                'importer' => 'МИ МОН',
                'institution_id' => [0, 138]
            ],
            'МТС и МФ' => [
                'importer' => 'МТС и МФ',
                'institution_id' => [143, 146]
            ],
            'МК МФ' => [
                'importer' => 'МК МФ',
                'institution_id' => [136, 146]
            ],
            'МЗ и МП' => [
                'importer' => 'МЗ и МП',
                'institution_id' => [132, 141]
            ],
            'МИ МТСП' => [
                'importer' => 'МИ МТСП',
                'institution_id' => [0, 144]
            ],
            'МИ МЕЕР' => [
                'importer' => 'МИ МЕЕР',
                'institution_id' => [0, 0]
            ],
            'МО МРРБ' => [
                'importer' => 'МО МРРБ',
                'institution_id' => [140, 142]
            ],
            'МИ МЗ' => [
                'importer' => 'МИ МЗ',
                'institution_id' => [0, 132]
            ],
            'заместник министър-председателят и министър на вътрешните работи, МИП' => [
                'importer' => 'заместник министър-председателят и министър на вътрешните работи, МИП',
                'institution_id' => []
            ],
            'заместник министър-председател и министър на правосъдието, МРР, МИП' => [
                'importer' => 'заместник министър-председател и министър на правосъдието, МРР, МИП',
                'institution_id' => []
            ],
            'ЗМПИР, заместник министър-председателят и министър на вътрешните работи' => [
                'importer' => 'ЗМПИР, заместник министър-председателят и министър на вътрешните работи',
                'institution_id' => []
            ],
            'ЗМПИР, заместник министър-председателят и министър на правосъдието' => [
                'importer' => 'ЗМПИР, заместник министър-председателят и министър на правосъдието',
                'institution_id' => []
            ],
            ':FIL' => [
                'importer' => ':FIL',
                'institution_id' => null,
            ],
            ':RE' => [
                'importer' => ':RE',
                'institution_id' => null,
            ],
            'ДА' => [
                'importer' => 'ДА',
                'institution_id' => null,
            ],
            'ДАЕЕР' => [
                'importer' => 'ДАЕЕР',
                'institution_id' => null,
            ],
            'ДАИТС' => [
                'importer' => 'ДАИТС',
                'institution_id' => null,
            ],
            'ЕВ' => [
                'importer' => 'ЕВ',
                'institution_id' => null,
            ],
            'зам. министър-председателят и председател на ЦКБППМН' => [
                'importer' => 'зам. министър-председателят и председател на ЦКБППМН',
                'institution_id' => null,
            ],
            'и.д. главен секретар на МС' => [
                'importer' => 'и.д. главен секретар на МС',
                'institution_id' => null,
            ],
            'М3' => [
                'importer' => 'М3',
                'institution_id' => 132,
            ],
            'МВнР' => [
                'importer' => 'МВнР',
                'institution_id' => 128,
            ],
            'МВНР' => [
                'importer' => 'МВнР',
                'institution_id' => 128,
            ],
            'МвНР' => [
                'importer' => 'МВнР',
                'institution_id' => 128,
            ],
            'МВнр' => [
                'importer' => 'МВнР',
                'institution_id' => 128,
            ],
            'МвнР' => [
                'importer' => 'МВнР',
                'institution_id' => 128,
            ],
            'МВР' => [
                'importer' => 'МВР',
                'institution_id' => 129,
            ],
            'МВр' => [
                'importer' => 'МВР',
                'institution_id' => 129,
            ],
            'МДА' => [
                'importer' => 'МДА',
                'institution_id' => null,
            ],
            'МДААР' => [
                'importer' => 'МДААР',
                'institution_id' => null,
            ],
            'МДПБА' => [
                'importer' => 'МДПБА',
                'institution_id' => null,
            ],
            'МЕВ' => [
                'importer' => 'МЕВ',
                'institution_id' => null,
            ],
            'МЕЕР' => [
                'importer' => 'МЕЕР',
                'institution_id' => null,
            ],
            'МЗ' => [
                'importer' => 'МЗ',
                'institution_id' => 132,
            ],
            'Мз' => [
                'importer' => 'МЗ',
                'institution_id' => 132,
            ],
            'МЗГ' => [
                'importer' => 'МЗГ',
                'institution_id' => null,
            ],
            'МЗГАР' => [
                'importer' => 'МЗГАР',
                'institution_id' => null,
            ],
            'МЗГБ' => [
                'importer' => 'МЗГБ',
                'institution_id' => null,
            ],
            'МЗГП' => [
                'importer' => 'МЗГП',
                'institution_id' => null,
            ],
            'МЗП' => [
                'importer' => 'МЗП',
                'institution_id' => null,
            ],
            'МЗХ' => [
                'importer' => 'МЗХ',
                'institution_id' => 133,
            ],
            'мзх' => [
                'importer' => 'МЗХ',
                'institution_id' => 133,
            ],
            'МИ' => [
                'importer' => 'МИ',
                'institution_id' => null,
            ],
            'МИЕ' => [
                'importer' => 'МИЕ',
                'institution_id' => null,
            ],
            'МИЕТ' => [
                'importer' => 'МИЕТ',
                'institution_id' => null,
            ],
            'МИС' => [
                'importer' => 'МИС',
                'institution_id' => null,
            ],
            'МК' => [
                'importer' => 'МК',
                'institution_id' => 136,
            ],
            'МКТ' => [
                'importer' => 'МКТ',
                'institution_id' => null,
            ],
            'ММС' => [
                'importer' => 'ММС',
                'institution_id' => 137,
            ],
            'ММСМС-ДирЕИОМФИ' => [
                'importer' => 'ММС',
                'institution_id' => 137,
            ],
            'МНО' => [
                'importer' => 'МНО',
                'institution_id' => null,
            ],
            'МО' => [
                'importer' => 'МО',
                'institution_id' => 140,
            ],
            'Мо' => [
                'importer' => 'МО',
                'institution_id' => 140,
            ],
            'мо' => [
                'importer' => 'МО',
                'institution_id' => 140,
            ],
            'МОМН' => [
                'importer' => 'МОМН',
                'institution_id' => null,
            ],
            'МОН' => [
                'importer' => 'МОН',
                'institution_id' => 138,
            ],
            'МОСВ' => [
                'importer' => 'МОСВ',
                'institution_id' => 139,
            ],
            'МП' => [
                'importer' => 'МП',
                'institution_id' => 141,
            ],
            'мп' => [
                'importer' => 'МП',
                'institution_id' => 141,
            ],
            'М-во на правосъдието' => [
                'importer' => 'М-во на правосъдието',
                'institution_id' => 141,
            ],
            'м-во на правосъдието' => [
                'importer' => 'М-во на правосъдието',
                'institution_id' => 141,
            ],
            'МППЕИ' => [
                'importer' => 'МППЕИ',
                'institution_id' => null,
            ],
            'МПр' => [
                'importer' => 'МПр',
                'institution_id' => null,
            ],
            'МРРБ' => [
                'importer' => 'МРРБ',
                'institution_id' => 142,
            ],
            'мррб' => [
                'importer' => 'МРРБ',
                'institution_id' => 142,
            ],
            'МС' => [
                'importer' => 'МС',
                'institution_id' => 127,
            ],
            'МТ' => [
                'importer' => 'МТ',
                'institution_id' => 145,
            ],
            'МТИТС' => [
                'importer' => 'МТИТС',
                'institution_id' => null,
            ],
            'МТС' => [
                'importer' => 'МТС',
                'institution_id' => 143,
            ],
            'МТС_' => [
                'importer' => 'МТС',
                'institution_id' => 143,
            ],
            'МТСГ' => [
                'importer' => 'МТСГ',
                'institution_id' => null,
            ],
            'МТСП' => [
                'importer' => 'МТСП',
                'institution_id' => 144,
            ],
            'МТТ' => [
                'importer' => 'МТТ',
                'institution_id' => null,
            ],
            'МФ' => [
                'importer' => 'МФ',
                'institution_id' => 146,
            ],
            'мф' => [
                'importer' => 'МФ',
                'institution_id' => 146,
            ],
            'МФВС' => [
                'importer' => 'МФВС',
                'institution_id' => null,
            ],
            'министър без портфейл' => [
                'importer' => 'министър без портфейл',
                'institution_id' => null,
            ],
            'министър без портфейл (Ал. Праматарски)' => [
                'importer' => 'министър без портфейл (Ал. Праматарски)',
                'institution_id' => null,
            ],
            'министър без портфейл (Б. Димитров)' => [
                'importer' => 'министър без портфейл (Б. Димитров)',
                'institution_id' => null,
            ],
            'министър без портфейл (М. Кунева)' => [
                'importer' => 'министър без портфейл (М. Кунева)',
                'institution_id' => null,
            ],
            'министър без портфейл (Н. Моллов)' => [
                'importer' => 'министър без портфейл (Н. Моллов)',
                'institution_id' => null,
            ],
            'министър без портфейл (Ф. Хюсменова)' => [
                'importer' => 'министър без портфейл (Ф. Хюсменова)',
                'institution_id' => null,
            ],
            'министър-председателят' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'министър-председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Министър-председателят' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Министър-председател' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'мин.-председателят' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Министър-председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'МИНИСТЪР-ПРЕДСЕДАТЕЛЯ' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'мин-председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Мин.-председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Мин.- председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'мин.- председателя' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'миншстэр-председателят' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'министър-председател' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'мин.-председател' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'МИНИСТЪР-ПРЕДСЕДАТЕЛЯТ' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'министър- председател' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'министър- председателят' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'Министър-председателят на РБ' => [
                'importer' => 'Министър-председателят',
                'institution_id' => 127,
            ],
            'министърът на държ.администрац' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'Министърът на държавната адм.' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'Министърът на държ. администр.' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'М-рът на държ. адм.' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'м-рът на държ. администрация' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'министърът на държ.администр.' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'м-рът на държ. адм.' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'М-рът на държ. администрация' => [
                'importer' => 'Министърът на държ.администрация',
                'institution_id' => 127,
            ],
            'зам. министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.-министър-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. министър-председателят М. Плугчиева' => [
                'importer' => 'Зам. министър-председателят М. Плугчиева',
                'institution_id' => 127,
            ],
            'заместник министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.министър-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. министър-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам.мин.-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'заместник-министър председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам.мин.-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин.-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Заместник мин.-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин.-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам м-предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. министър-председателя' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин.-председ.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам. министър-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин-председателя' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин.-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин.-председ.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин.-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин.-предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин-предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам-мин предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам. министър-председателя' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин.-председателя' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зм. мин.-предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Зам. министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'заместник министър-председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'Заместник министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин. -предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин. - председателят' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам.мин.-председат.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'зам. мин.-предс.' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'замeстник министър-председател' => [
                'importer' => 'Зам. министър-председателят',
                'institution_id' => 127,
            ],
            'м-р Ф. Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'М-р без портфейл Ф. Хюсменова' => [
                'importer' => 'М-р без портфейл Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'М-ра без портфейл Ф. Хюсменова' => [
                'importer' => 'М-р без портфейл Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'М-р Ф. Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'м-р Фелиз Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'м-р Ф.Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'м-р ф. Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'м-р Хюсменова' => [
                'importer' => 'М-р Ф. Хюсменова',
                'institution_id' => 127,
            ],
            'М-р М. Кунева' => [
                'importer' => 'М-р М. Кунева',
                'institution_id' => 127,
            ],
            'м-р М. Кунева' => [
                'importer' => 'М-р М. Кунева',
                'institution_id' => 127,
            ],
            'М-р Ал. Праматарски' => [
                'importer' => 'М-р Ал. Праматарски',
                'institution_id' => 127,
            ],
            'м-р Александър Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'министър Ал. Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'м-р Ал. Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'м-р А. Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'А. Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'м-р Праматарски' => [
                'importer' => 'М-р Александър Праматарски',
                'institution_id' => 127,
            ],
            'м-р Б. Димитров' => [
                'importer' => 'М-р Б. Димитров',
                'institution_id' => 127,
            ],
            'м-р Б. Нанев' => [
                'importer' => 'М-р Б. Нанев',
                'institution_id' => 127,
            ],
            'М-р Д. Калчев' => [
                'importer' => 'М-р Д. Калчев',
                'institution_id' => 127,
            ],
            'м-р Д. Калчев' => [
                'importer' => 'М-р Д. Калчев',
                'institution_id' => 127,
            ],
            'м-р Д.Калчев' => [
                'importer' => 'М-р Д. Калчев',
                'institution_id' => 127,
            ],
            'м-р Емилия Масларова' => [
                'importer' => 'М-р Емилия Масларова',
                'institution_id' => 127,
            ],
            'м-р Моллов' => [
                'importer' => 'М-р Моллов',
                'institution_id' => 127,
            ],
            'М-р Н. Моллов' => [
                'importer' => 'М-р Моллов',
                'institution_id' => 127,
            ],
            'м-р Н. Моллов' => [
                'importer' => 'М-р Моллов',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическото развитие' => [
                'importer' => 'Зам. министър-председателят по икономическото развитие',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по икономическото развитие;' => [
                'importer' => 'Зам. министър-председателят по икономическото развитие',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по икономическото развитие' => [
                'importer' => 'Зам. министър-председателят по икономическото развитие',
                'institution_id' => 127,
            ],
            'зам. министър-председателят по икономическото развитие' => [
                'importer' => 'Зам. министър-председателят по икономическото развитие',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по икономическото развитие' => [
                'importer' => 'Зам. министър-председателят по икономическото развитие',
                'institution_id' => 127,
            ],
            'заместник министър-председател и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'зам. министър-председател и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по пръвосъдие' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'заместни министър-председател и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'заместник-министър председателят и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'заместник министър-председателят и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'замeстник министър-председател и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'заместник министър-председател по правосъдие' => [
                'importer' => 'Зам. министър-председател по правосъдие',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по правосъдие' => [
                'importer' => 'Зам. министър-председател по правосъдие',
                'institution_id' => 127,
            ],
            'заместник министър-председател и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'зам. министър-председателят и министър на правосъдието' => [
                'importer' => 'Зам. министър-председателят и министър на правосъдието',
                'institution_id' => 127,
            ],
            'вътрешен ред и сигурност и министър на правосъдието' => [
                'importer' => 'вътрешен ред и сигурност и министър на правосъдието',
                'institution_id' => 127,
            ],
            'министър-председател по вътрешен ред и сигурност и министър на отбраната' => [
                'importer' => 'Министър-председател по вътрешен ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'заместник министър-председател по вътрешен ред и сигурност и министър на отшбраната' => [
                'importer' => 'Зам. министър-председател по вътрешен ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'министър-председателят и министър на външните работи' => [
                'importer' => 'Министър-председателят и министър на външните работи',
                'institution_id' => 127,
            ],
            'министър-председателят и министър на външните работи М. Райков' => [
                'importer' => 'Министър-председателят и министър на външните работи',
                'institution_id' => 127,
            ],
            'зам. министър-председателят и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председателят и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'замистник министър-председателят и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'замстник министър-председателят и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник-министър председателят и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател и министър на външните работи' => [
                'importer' => 'Зам. министър-председателят и министър на външните работи',
                'institution_id' => 127,
            ],
            'заместник-министърът и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председателят и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическа политика' => [
                'importer' => 'Зам. министър-председател по икономическа политика',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по икономическа политика' => [
                'importer' => 'Зам. министър-председател по икономическа политика',
                'institution_id' => 127,
            ],
            'заместник министър председател по икономическа политика' => [
                'importer' => 'Зам. министър-председател по икономическа политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по европейските фондове' => [
                'importer' => 'Зам. министър-председател по европейските фондове',
                'institution_id' => 127,
            ],
            'заместник министър-председател по европейските фондове и икономическа политика' => [
                'importer' => 'Зам. министър-председател по европейските фондове и икономическа политика',
                'institution_id' => 127,
            ],
            'заместник. министър-председател по европейските фондове и икономическата политика' => [
                'importer' => 'Зам. министър-председател по европейските фондове и икономическа политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по европейските фондове и икономическата политика' => [
                'importer' => 'Зам. министър-председател по европейските фондове и икономическа политика',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по европейските фондове и икономическата политика' => [
                'importer' => 'Зам. министър-председател по европейските фондове и икономическа политика',
                'institution_id' => 127,
            ],
            'замeестник министър-председател по обществения ред и сигурност и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по обществен ред и сигурност и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по обществения ред и сигурност и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'заместник министър-председател по обществен ред и сигурност и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'Заместник министър-председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'заместник министър председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'замeтник министър-председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'замстник министър-председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'заместник министър председател по правосъдната реформа и министър на външните работи' => [
                'importer' => 'Зам. министър-председател по правосъдната реформа и сигурност и министър на външните работи',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по правосъдната реформа и министър на външните работи' => [
                'importer' => 'Зам. министър-председател по правосъдната реформа и сигурност и министър на външните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по правосъдната реформа и министър на външните работи' => [
                'importer' => 'Зам. министър-председател по правосъдната реформа и сигурност и министър на външните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по правосъдната реформа и министър на външните работи' => [
                'importer' => 'Зам. министър-председател по правосъдната реформа и сигурност и министър на външните работи',
                'institution_id' => 127,
            ],
            'заместник министър - председател по обществения ред и сигурността и министър на отбраната' => [
                'importer' => 'Зам. министър-председател по обществения ред и сигурност и министър на отбраната',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по социална политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник. министър-председател по социална политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по социалните политики и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър- председател по социалните политики и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по социалните политики и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по социална политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по социална политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по социална политика и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'зам. министър-председател по икономическите и социалните политики и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по икономическите и социалните политики и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическите и социалните политики и министър на труда и социалната политика;' => [
                'importer' => 'Зам. министър-председател по икономическите и социалните политики и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическите и социалните политики и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по икономическите и социалните политики и министър на труда и социалната политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по управление на средствата от Европейския съюз' => [
                'importer' => 'Зам. министър-председател по управление на средствата от Европейския съюз',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по управление на средствата от Европейския съюз' => [
                'importer' => 'Зам. министър-председател по управление на средствата от Европейския съюз',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по управление на европейските средства' => [
                'importer' => 'Зам. министър-председател по управление на европейските средства',
                'institution_id' => 127,
            ],
            'заместник министър-председател по управление на европейските средства' => [
                'importer' => 'Зам. министър-председател по управление на европейските средства',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по управление на европейските средства' => [
                'importer' => 'Зам. министър-председател по управление на европейските средства',
                'institution_id' => 127,
            ],
            'заместник министър-председател по еврофондовете министър на финансите' => [
                'importer' => 'Зам. министър-председател по еврофондовете министър на финансите',
                'institution_id' => 127,
            ],
            'заместник министър председател по еврофондовете и министър на финансите' => [
                'importer' => 'Зам. министър-председател по еврофондовете министър на финансите',
                'institution_id' => 127,
            ],
            'заместник министър-председател по еврофондовете и министър на финансите' => [
                'importer' => 'Зам. министър-председател по еврофондовете министър на финансите',
                'institution_id' => 127,
            ],
            'заместнек министър-председател по еврофондовете и министър на финансите' => [
                'importer' => 'Зам. министър-председател по еврофондовете министър на финансите',
                'institution_id' => 127,
            ],
            'замаместник министър-председател по еврофондовете и министър на финансите' => [
                'importer' => 'Зам. министър-председател по еврофондовете министър на финансите',
                'institution_id' => 127,
            ],
            'зам. министър председател по икономическата и демографската политика и министър на туризма' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика и министър на туризма',
                'institution_id' => 127,
            ],
            'заместник министър председател по икономическата и демографската политика и министър на туризма' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика и министър на туризма',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическата и демографската политика и министър на туризма' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика и министър на туризма',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическата и демографската политика' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическата демографската политика' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по икономическата и демографската политика' => [
                'importer' => 'Зам. министър-председател по икономическата и демографската политика',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по демографската и социалната политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по демографската и социалната политика и министър на труда и социалната политикс',
                'institution_id' => 127,
            ],
            'заместник министър-председател по демографската и социалната политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по демографската и социалната политика и министър на труда и социалната политикс',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по демографската и социалната политика и министър на труда и социалната политика' => [
                'importer' => 'Зам. министър-председател по демографската и социалната политика и министър на труда и социалната политикс',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономическите политики и министър на транспорта и съобщенията' => [
                'importer' => 'Зам. министър-председател по икономическите политики и министър на транспорта и съобщенията',
                'institution_id' => 127,
            ],
            'заместник министър-председател по регионалното развитие и благоустройството и министър на регионалното развитие и благоустройството' => [
                'importer' => 'Зам. министър-председател по регионалното развитие и благоустройството и министър на регионалното развитие и благоустройството',
                'institution_id' => 127,
            ],
            'заместник министър- председател по икономиката и индустрията и министър на икономиката и индустрията' => [
                'importer' => 'Зам. министър-председател по икономиката и индустрията и министър на икономиката и индустрията',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономиката и министър на икономиката и индустрията' => [
                'importer' => 'Зам. министър-председател по икономиката и индустрията и министър на икономиката и индустрията',
                'institution_id' => 127,
            ],
            'заместник министър-председател по икономиката и индустрията и министър на икономиката и индустрията' => [
                'importer' => 'Зам. министър-председател по икономиката и индустрията и министър на икономиката и индустрията',
                'institution_id' => 127,
            ],
            'заместник министър-председател по климатичните политики и министър на околната среда и водите' => [
                'importer' => 'Зам. министър-председател по климатичните политики и министър на околната среда и водите',
                'institution_id' => 127,
            ],
            'заместник министърпредседател по климатични политики и министър на околната среда и водите' => [
                'importer' => 'Зам. министър-председател по климатичните политики и министър на околната среда и водите',
                'institution_id' => 127,
            ],
            'заместник министър-председател по климатични политики и министър на околната среда и водите' => [
                'importer' => 'Зам. министър-председател по климатичните политики и министър на околната среда и водите',
                'institution_id' => 127,
            ],
            'замесник министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по коалиционна политика и държавна администрация и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председател по коалиционна поли¬тика и държавна администрация и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация и министър на вътрешните работи',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по коалиционна политика и държавна администрация' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация',
                'institution_id' => 127,
            ],
            'заместник министър-председател по коалиционна политика и държавна администрация' => [
                'importer' => 'Зам. министър-председател по коалиционна политика и държавна администрация',
                'institution_id' => 127,
            ],
            'заместник министър-председател по координация на европейските политикии институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'заместник министър-председател по координация на европейските политики и институционалние въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'замeстник министър-председател по координация на европейските политики и институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'замаместник министър-председател по координация на европейските политики и институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'зам. министър-председател по координация на европейските политики и институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'заместник министър-председател по координация на европейските политики и институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по координация на европейските политики и институционалните въпроси и министър на образованието и науката' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики институционалните въпроси и министър на образованието и науката',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по координация на европейските политики и инсти-туционалните въпроси' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики и институционалните въпроси',
                'institution_id' => 127,
            ],
            'заместник министър-председател по координация на европейските политики и институционалните въпроси' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики и институционалните въпроси',
                'institution_id' => 127,
            ],
            'заместник министър-председателят по координация на европейските политики и институционалните въпроси' => [
                'importer' => 'Зам. министър-председател по координация на европейските политики и институционалните въпроси',
                'institution_id' => 127,
            ],
            'заместник министър-председател  по подготовката на българското председателство на Съвета на ЕС - 2018' => [
                'importer' => 'Зам. министър-председател по подготовката на българското председателство на Съвета на ЕС - 2018',
                'institution_id' => 127,
            ],
            'заместник министър-председател по подготовка на българското председателство на Съвета на ЕС - 2018' => [
                'importer' => 'Зам. министър-председател по подготовката на българското председателство на Съвета на ЕС - 2018',
                'institution_id' => 127,
            ],
            'заместник министър-председател по подготовката на българското председателство на Съвета на ЕС-2018' => [
                'importer' => 'Зам. министър-председател по подготовката на българското председателство на Съвета на ЕС - 2018',
                'institution_id' => 127,
            ],
            'заместник министър-председател по подготовката на българското председателство на Съвета на ЕС - 2018' => [
                'importer' => 'Зам. министър-председател по подготовката на българското председателство на Съвета на ЕС - 2018',
                'institution_id' => 127,
            ],
            'заместник министър-председател по подготовката на българското председателство' => [
                'importer' => 'Зам. министър-председател по подготовката на българското председателство',
                'institution_id' => 127,
            ],
            'заместник министър-председател по социални политики и министър на здравеопазването' => [
                'importer' => 'Зам. министър-председател по социални политики и министър на здравеопазването',
                'institution_id' => 127,
            ],
            'заместник министър-председател по социални политики и министър на здравеопазване' => [
                'importer' => 'Зам. министър-председател по социални политики и министър на здравеопазването',
                'institution_id' => 127,
            ],
            'министър на регионалното развитие и министър на инвестиционното проектиране' => [
                'importer' => 'Министър на регионалното развитие и министър на инвестиционното проектиране',
                'institution_id' => 127,
            ]
        ];

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('pris')->select('select max(archimed.e_items.id) from archimed.e_items');
        //start from this id in old database
        $currentStep = 0;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;

            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('pris')->select('select
                        pris.id as old_id,
                        pris."number" as doc_num,
                        pris.parentid as parentdocumentid,
                        pris.rootid as rootdocumentid,
                        pris.masterid,
                        pris.state,
                        pris.xstate,
                        case when pris.islatestrevision = false then 0 else 1 end as last_version,
                        pris.itemtypeid as old_doc_type_id,
                        pris."xml" as to_parse_xml_details,
                        pris.activestate as active, -- check with distinct if only 0 and 1 // check also pris.state
                        pris.datepublished as published_at,
                        pris.datecreated as created_at,
                        pris.datemodified as updated_at,
                        sum(case when att.attachid is not null then 1 else 0 end) as has_files
                    FROM archimed.e_items pris
                    left join edocs.attachments att on att.documentid = pris.id
                    where true
                        and pris.id >= ' . $currentStep . '
                        and pris.id < ' . ($currentStep + $step) . '
                        and pris.itemtypeid <> 5017 -- skip law records
                        -- and documents.lastrevision = \'Y\' -- get final versions
                    group by pris.id
                    order by pris.id asc');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        //Update existing
                        if(isset($ourPris) && sizeof($ourPris) && isset($ourPris[(int)$item->old_id])) {
                            $existPris = Pris::find($ourPris[(int)$item->old_id]);

                            if ($existPris) {
                                $this->comment('Start update importers for Pris with old id ' . $item->old_id);
                                DB::beginTransaction();
                                //Update importers
                                try {
                                    $importerInstitutions = [];
                                    $xml = simplexml_load_string($item->to_parse_xml_details);
                                    $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
                                    $data = json_decode($json, true);
                                    if (isset($data['DocumentContent']) && isset($data['DocumentContent']['Attribute']) && sizeof($data['DocumentContent']['Attribute'])) {
                                        $attributes = $data['DocumentContent']['Attribute'];
                                        foreach ($attributes as $att) {
                                            //importer
                                            //institution_id
                                            if (isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Вносител') {
                                                $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                                if ($val) {
                                                    //echo "Importer: ".$att['Value']['Value'].PHP_EOL;
                                                    $importerStr = [];
                                                    $importerInstitutions = [];
                                                    $explode = explode(',', $val);
                                                    foreach ($explode as $e) {
                                                        if (isset($importers[trim($e)])) {
                                                            if(
                                                                (!is_array($importers[trim($e)]['institution_id']) && !is_null($importers[trim($e)]['institution_id']))
                                                                || (is_array($importers[trim($e)]['institution_id']) && sizeof($importers[trim($e)]['institution_id']) && array_sum($importers[trim($e)]['institution_id']) > 0)
                                                            ) {
                                                                if(is_array($importers[trim($e)]['institution_id'])){
                                                                    foreach ($importers[trim($e)]['institution_id'] as $i){
                                                                        $importerInstitutions[] = $i;
                                                                    }
                                                                } else{
                                                                    $importerInstitutions[] = $importers[trim($e)]['institution_id'];
                                                                }
                                                            } else{
                                                                if(!empty($importers[trim($e)]['importer'])){
                                                                    $importerStr[] = $importers[trim($e)]['importer'];
                                                                }
                                                                if(!isset($institutionForMapping[trim($e)])) {
                                                                    //TODO for mapping
                                                                    file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing ID in mapping: ' . $e . PHP_EOL, FILE_APPEND);
                                                                    $institutionForMapping[trim($e)] = trim($e);
                                                                }
                                                            }
                                                        } else{
//                                                            $valNoNeLine = str_replace(['\r\n', '\n\r','\n', '\r'], ';', $val);
                                                            $valNoNeLine = preg_replace("/[\r\n]+/", "", $val);
                                                            $explodeByRow = explode(';', $valNoNeLine);
                                                            if(sizeof($explodeByRow)){
                                                                foreach ($explodeByRow as $eByRow){
                                                                    if (isset($importers[trim($eByRow)])) {
                                                                        if (
                                                                            (!is_array($importers[trim($eByRow)]['institution_id']) && !is_null($importers[trim($eByRow)]['institution_id']))
                                                                            || (is_array($importers[trim($eByRow)]['institution_id']) && sizeof($importers[trim($eByRow)]['institution_id']) && array_sum($importers[trim($eByRow)]['institution_id']) > 0)
                                                                        ) {
                                                                            if(is_array($importers[trim($eByRow)]['institution_id'])){
                                                                                foreach ($importers[trim($eByRow)]['institution_id'] as $i){
                                                                                    $importerInstitutions[] = $i;
                                                                                }
                                                                            } else{
                                                                                $importerInstitutions[] = $importers[trim($eByRow)]['institution_id'];
                                                                            }
                                                                        } else{
                                                                            if(!empty($importers[trim($eByRow)]['importer'])){
                                                                                $importerStr[] = $importers[trim($eByRow)]['importer'];
                                                                            }
                                                                            if(!isset($institutionForMapping[trim($eByRow)])) {
                                                                                //TODO for mapping
                                                                                file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing ID in mapping: ' . $eByRow . PHP_EOL, FILE_APPEND);
                                                                                $institutionForMapping[trim($eByRow)] = trim($eByRow);
                                                                            }
                                                                        }
                                                                    } else{
                                                                        //TODO for mapping
                                                                        if(!isset($institutionForMapping[trim($eByRow)])){
                                                                            file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing in mapping: '.$eByRow.PHP_EOL, FILE_APPEND);
                                                                            $institutionForMapping[trim($eByRow)] = trim($eByRow);
                                                                        }
                                                                    }
                                                                }
                                                            } else{
                                                                //TODO for mapping
                                                                if(!isset($institutionForMapping[trim($e)])){
                                                                    file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing in mapping: '.$e.PHP_EOL, FILE_APPEND);
                                                                    $institutionForMapping[trim($e)] = trim($e);
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $existPris->old_importers = $val;
                                                    $existPris->save();
                                                    $existPris->translateOrNew('bg')->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                                    $existPris->translateOrNew('en')->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
//                                                    $existPris->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                                    if (isset($importerInstitutions) && sizeof($importerInstitutions)) {
                                                        $existPris->institutions()->sync($importerInstitutions);
                                                    } else {
                                                        $existPris->institutions()->sync([$dInstitution->id]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    DB::commit();
                                } catch (\Exception $e) {
                                    Log::error('Migration update old pris importers error: ' . $e);
                                    DB::rollBack();
                                }
                            }
                        }
                    }
                }
                $currentStep += $step;
            }
        }

        return Command::SUCCESS;
    }
}
