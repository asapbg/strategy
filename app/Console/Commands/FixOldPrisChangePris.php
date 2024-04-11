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
                                                                select pris_change_pris.pris_id from pris_change_pris where pris_id = ? and changed_pris_id = ? and connect_type = ? and old_connect_type = ?)'
                                            , [$prisId, $changedPrisId, $newConnection, $oldConnection, $prisId, $changedPrisId, $newConnection, $oldConnection]);
                                    }
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
        //TODO what to di with group #1 'виж'
        preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от) (РАЗП|РЕШ|ПРОТ|ПОСТ) ([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        return $matches;
    }

    private function findPris($data){
//        [
//            0 => "изменя РАЗП 71 дата 31/12/80"
//            1 => "изменя"
//            2 => "РАЗП"
//            3 => "71"
//            4 => "31/12/80"
//        ]

        $category = match ($data[2]) {
            'РАЗП' => LegalActType::TYPE_DISPOSITION,
            'РЕШ' => LegalActType::TYPE_DECISION,
            'ПРОТ' => LegalActType::TYPE_PROTOCOL,
            'ПОСТ' => LegalActType::TYPE_DECREES,
            default => null,
        };

        if(!$category){
            return null;
        }

        $number = $data[3];

        if(!$number) {
            return null;
        }

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
            ->get();

        if($pris->count() != 1){
            $this->comment('PRIS exist duplicated or not exist at all: '.json_encode($data, JSON_UNESCAPED_UNICODE));
            return null;
        }

        return $pris->first()->id;
    }
}
