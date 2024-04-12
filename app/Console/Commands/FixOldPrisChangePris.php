<?php

namespace App\Console\Commands;

use App\Enums\PrisDocChangeTypeEnum;
use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOldPrisChangePris extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pris_connections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix old PRIS connections between documents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents('old_pris_missing_connections.txt', '');
        $step = 50;
        $maxId = Pris::max('id');
        $currentStep = 0;
        while ($currentStep < $maxId) {
            $prisItems = Pris::withTrashed()
                ->where('id', '>=', $currentStep)
                ->where('id', '<', ($currentStep + $step))
                ->whereNotNull('old_connections')
                ->get();
            if($prisItems->count()){
                foreach ($prisItems as $item){
                    echo 'Start pris doc ID: '.$item->id. PHP_EOL;
                    DB::beginTransaction();
                    try {
                        $oldConnections = explode(';', $item->old_connections);
                        if(sizeof($oldConnections)){
                            foreach ($oldConnections as $key => $oldC){
                                $connection = $this->parseConnection($oldC);
                                if(sizeof($connection) == 5){
                                    $oldConnection = $connection[1];
                                    switch ($oldConnection) //type of Connection
                                    {
                                        case 'изменя':
                                            $newConnection = PrisDocChangeTypeEnum::CHANGE->value;
                                            $prisId = $item->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'отменя':
                                            $newConnection = PrisDocChangeTypeEnum::CANCEL->value;
                                            $prisId = $item->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'допълва':
                                            $newConnection = PrisDocChangeTypeEnum::COMPLEMENTS->value;
                                            $prisId = $item->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'изменен от':
                                            $newConnection = PrisDocChangeTypeEnum::CHANGE->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $item->id;
                                            break;
                                        case 'отменен от':
                                            $newConnection = PrisDocChangeTypeEnum::CANCEL->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $item->id;
                                            break;
                                        case 'допълнен от':
                                            $newConnection = PrisDocChangeTypeEnum::COMPLEMENTS->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $item->id;
                                            break;
                                    }

                                    if($newConnection && $prisId && $changedPrisId){
                                        DB::statement('insert into pris_change_pris
                                                                (pris_id, changed_pris_id, connect_type, old_connect_type)
                                                             select ?, ?, ?, ?
                                                             where not exists (
                                                                select pris_change_pris.pris_id from pris_change_pris where pris_id = ? and changed_pris_id = ? and connect_type = ?)'
                                            , [$prisId, $changedPrisId, $newConnection, $oldConnection, $prisId, $changedPrisId, $newConnection]);
                                    }
                                } else {
                                    file_put_contents('old_pris_missing_connections.txt', 'Pris ID:'.$item->id.' - '.$oldC.' | '.json_encode($connection).PHP_EOL, FILE_APPEND);
                                }
                            }
                        }
                        DB::commit();
                    }  catch (\Exception $e) {
                        Log::error('Fix old pris connections: ' . $e);
                        DB::rollBack();
                    }
                }

            }
            $currentStep += $step;
        }
        return Command::SUCCESS;
    }

    private function parseConnection($str){
        $str = preg_replace('/\s+/', ' ', $str);
        $str = trim($str);
        //изменен от Пост 268 дата 01/01/09
        preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        if(sizeof($matches) != 5){
            //изменен от Пост268 дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост)([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if(sizeof($matches) != 5){
            //изменен от Пост П-268 дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РЕШ) П-([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if(sizeof($matches) != 5){
            //изменен от Пост 268 на ВАС дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) на ВАС дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if(sizeof($matches) != 5){
            //изменен от Пост 268 от 2009
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) от ([\d]{4})+/', $str, $matches);
        }
        //TODO what to do with group #1 'виж'
        return $matches;
    }

    private function findPris($data){
//        [
//            0 => "изменя РАЗП 71 дата 31/12/80"
//            1 => "изменя"
//            2 => "РАЗП"
//            3 => "71"
//            4 => "31/12/80"
//        ],
        //        [
//            0 => "изменен от Пост 268 от 2009"
//            1 => "изменен от"
//            2 => "Пост"
//            3 => "268"
//            4 => "2009"
//        ]

        $category = match ($data[2]) {
            'РАЗП', 'разп', 'Разп' => LegalActType::TYPE_DISPOSITION,
            'РЕШ', 'Реш', 'реш' => LegalActType::TYPE_DECISION,
            'ПРОТ', 'Прот' => LegalActType::TYPE_PROTOCOL,
            'ПОСТ', 'Пост' => LegalActType::TYPE_DECREES,
            default => null,
        };

        if(!$category){
            return null;
        }

        $number = $data[3];

        if(!$number) {
            return null;
        }

        if(strlen($data[4]) == 4){
            $from = $data[4].'-01-01';
            $to = $data[4].'-12-31';
            $pris = Pris::where('legal_act_type_id', '=', $category)
                ->where('doc_num', '=', $number)
                ->where('doc_date', '>=', $from)
                ->where('doc_date', '<=', $to)
                ->where('last_version', '=', 1)
                ->get();


            if($pris->count() == 0){
                //TODO if not found search by last by id
                $pris = Pris::where('legal_act_type_id', '=', $category)
                    ->where('doc_num', '=', $number)
                    ->where('doc_date', '>=', $from)
                    ->where('doc_date', '<=', $to)
                    ->orderBy('id', 'desc')
                    ->limit(1)
                    ->get();
                dd($pris);
            }

            if($pris->count() > 1){
                dd($pris);
            }

            if($pris->count() != 1){
                $this->comment('PRIS exist duplicated or not exist at all: '.json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }

            return $pris->first()->id;
        } else{
            $dateInfo = explode('/', $data[4]);
            if(sizeof($dateInfo) != 3){
                return null;
            }

            $docDate = ((int)$dateInfo[2] < 24 ? '20'.$dateInfo[2] : '19'.$dateInfo[2]).'-'.$dateInfo[1].'-'.$dateInfo[0];
            if(is_null(strtotime($docDate))){
                $this->comment('Inavild PRIS date: '.json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }


            $pris = Pris::where('legal_act_type_id', '=', $category)
                ->where('doc_num', '=', $number)
                ->where('doc_date', '=', $docDate)
                ->where('last_version', '=', 1)
                ->get();

            if($pris->count() == 0){
                //TODO if not found search by last by id
                $pris = Pris::where('legal_act_type_id', '=', $category)
                    ->where('doc_num', '=', $number)
                    ->where('doc_date', '=', $docDate)
                    ->orderBy('id', 'desc')
                    ->limit(1)
                    ->get();
                dd($pris);
            }

            if($pris->count() > 1){
                dd($pris);
            }

            if($pris->count() != 1){
                $this->comment('PRIS exist duplicated or not exist at all: '.json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }

            return $pris->first()->id;
        }
    }
}
