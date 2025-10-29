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
        DB::table('pris_change_pris')->truncate();
        file_put_contents('old_pris_connections_missing_documents.txt', '');
        file_put_contents('old_pris_missing_connections.txt', '');
        $step = 50;
        $maxId = Pris::max('id');
        $currentStep = 0;
//        $maxId = 1;
        while ($currentStep < $maxId) {
            $prisDocuments = Pris::select('id','old_connections')
                ->withTrashed()
                ->where('id', '>=', $currentStep)
                ->where('id', '<', ($currentStep + $step))
//                ->where('id', '14558')
                ->where('asap_last_version', '=', 1)
                ->whereNotNull('old_connections')
                ->get();

            if ($prisDocuments->count()) {
                foreach ($prisDocuments as $prisDocument) {
                    //echo 'Start fixing connections of pris doc with ID: ' . $prisDocument->id . PHP_EOL;

                    try {
                        $oldConnections = explode(';', $prisDocument->old_connections);
                        if (sizeof($oldConnections)) {
                            foreach ($oldConnections as $oldC) {

                                $connection = $this->parseConnection($oldC);
                                if (sizeof($connection) == 5) {
                                    $oldConnection = $connection[1];
                                    switch ($oldConnection) //type of Connection
                                    {
                                        case 'изменя':
                                            $newConnection = PrisDocChangeTypeEnum::CHANGE->value;
                                            $prisId = $prisDocument->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'отменя':
                                            $newConnection = PrisDocChangeTypeEnum::CANCEL->value;
                                            $prisId = $prisDocument->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'допълва':
                                            $newConnection = PrisDocChangeTypeEnum::COMPLEMENTS->value;
                                            $prisId = $prisDocument->id;
                                            $changedPrisId = $this->findPris($connection);
                                            break;
                                        case 'изменен от':
                                            $newConnection = PrisDocChangeTypeEnum::CHANGE->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $prisDocument->id;
                                            break;
                                        case 'отменен от':
                                            $newConnection = PrisDocChangeTypeEnum::CANCEL->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $prisDocument->id;
                                            break;
                                        case 'допълнен от':
                                            $newConnection = PrisDocChangeTypeEnum::COMPLEMENTS->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $prisDocument->id;
                                            break;
                                        case 'виж':
                                            $newConnection = PrisDocChangeTypeEnum::SEE_IN->value;
                                            $prisId = $this->findPris($connection);
                                            $changedPrisId = $prisDocument->id;
                                            break;
                                    }

                                    if ($newConnection && $prisId && $changedPrisId) {
                                        if ($oldConnection != 'виж') {
                                            DB::statement('insert into pris_change_pris
                                                                (pris_id, changed_pris_id, connect_type, old_connect_type)
                                                             select ?, ?, ?, ?
                                                             where not exists (
                                                                select pris_change_pris.pris_id from pris_change_pris where pris_id = ? and changed_pris_id = ? and connect_type = ?)'
                                                , [$prisId, $changedPrisId, $newConnection, $oldConnection, $prisId, $changedPrisId, $newConnection]);
                                        } else {
                                            DB::statement('insert into pris_change_pris
                                                                (pris_id, changed_pris_id, connect_type, old_connect_type)
                                                             select ?, ?, ?, ?
                                                             where not exists (
                                                                select pris_change_pris.pris_id from pris_change_pris where (pris_id = ? and changed_pris_id = ? and connect_type = ?) or (pris_id = ? and changed_pris_id = ? and connect_type = ?))'
                                                , [$prisId, $changedPrisId, $newConnection, $oldConnection, $prisId, $changedPrisId, $newConnection, $changedPrisId, $prisId, $newConnection]);
                                        }
                                        $this->info("Connections fixed for pris doc with ID $changedPrisId");
                                    }
                                } else {
                                    $this->error("Missing connections for pris with ID $prisDocument->id". ' - ' . $oldC . ' | ' . json_encode($connection));
                                    file_put_contents('old_pris_missing_connections.txt', 'Pris ID:' . $prisDocument->id . ' - ' . $oldC . ' | ' . json_encode($connection) . PHP_EOL, FILE_APPEND);
                                }


                            }
                        }

                    } catch (\Exception $e) {
                        Log::error('Fix old pris connections: ' . $e->getMessage());
                        $this->error('Error: '. $e->getMessage());
                    }
                }

            }
            $currentStep += $step;
        }
        return Command::SUCCESS;
    }

    private function parseConnection($str)
    {
        $str = preg_replace('/\s+/', ' ', $str);
        $str = trim($str);
        //изменен от Пост 268 дата 01/01/09
        preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от|виж) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        if (sizeof($matches) != 5) {
            //изменен от Пост268 дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от|виж) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост)([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if (sizeof($matches) != 5) {
            //изменен от Пост П-268 дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от|виж) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) П-([\d*(?:\.\d+)?]{1,}) дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if (sizeof($matches) != 5) {
            //изменен от Пост 268 на ВАС дата 01/01/09
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от|виж) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) на ВАС дата ([\d\/\d\/\d]{8})+/', $str, $matches);
        }
        if (sizeof($matches) != 5) {
            //изменен от Пост 268 от 2009
            preg_match('/^(изменя|изменен от|отменя|отменен от|допълва|допълнен от|виж) (РАЗП|разп|Разп|РЕШ|Реш|реш|ПРОТ|Прот|ПОСТ|Пост) ([\d*(?:\.\d+)?]{1,}) от ([\d]{4})+/', $str, $matches);
        }
        return $matches;
    }

    private function findPris($data)
    {
//        [
//            0 => "изменя РАЗП 71 дата 31/12/80"
//            1 => "изменя"
//            2 => "РАЗП"
//            3 => "71"
//            4 => "31/12/80"
//        ],

        $category = match ($data[2]) {
            'РАЗП', 'разп', 'Разп' => LegalActType::TYPE_DISPOSITION,
            'РЕШ', 'Реш', 'реш' => LegalActType::TYPE_DECISION,
            'ПРОТ', 'Прот' => LegalActType::TYPE_PROTOCOL,
            'ПОСТ', 'Пост' => LegalActType::TYPE_DECREES,
            default => null,
        };

        if (!$category) {
            return null;
        }

        $number = $data[3];

        if (!$number) {
            return null;
        }

        if (strlen($data[4]) == 4) {
            $from = $data[4] . '-01-01';
            $to = $data[4] . '-12-31';
            $pris = Pris::where('legal_act_type_id', '=', $category)
                ->where('doc_num', '=', $number)
                ->where('doc_date', '>=', $from)
                ->where('doc_date', '<=', $to)
                ->where('asap_last_version', '=', 1)
                ->get();


//            if($pris->count() == 0){
//                //TODO if not found last version search by last by id
//                $pris = Pris::where('legal_act_type_id', '=', $category)->where('doc_num', '=', $number)->where('doc_date', '>=', $from)->where('doc_date', '<=', $to)->orderBy('id', 'desc')->limit(1)->get();
//            }

            if ($pris->count() != 1) {
                if ($from >= '1990-03-22') {
                    file_put_contents('old_pris_connections_missing_documents.txt', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
                }
                $this->comment('PRIS exist duplicated or not exist at all: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }

            return $pris->first()->id;
        } else {
            $dateInfo = explode('/', $data[4]);
            if (sizeof($dateInfo) != 3) {
                return null;
            }

            $doc_year = ((int)$dateInfo[2] < 24 ? '20' . $dateInfo[2] : '19' . $dateInfo[2]);
            $docDate = $doc_year . '-' . $dateInfo[1] . '-' . $dateInfo[0];
            if (is_null(strtotime($docDate))) {
                $this->comment('Inavild PRIS date: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }

            if (checkdate($dateInfo[1], $dateInfo[0], $doc_year)) {
                $pris = Pris::where('legal_act_type_id', '=', $category)
                    ->where('doc_num', '=', $number)
                    ->where('doc_date', '=', $docDate)
                    ->where('asap_last_version', '=', 1)
                    ->get();
            } else {
                if (!empty($doc_year)) {
                    $pris = Pris::where('legal_act_type_id', '=', $category)
                        ->where('doc_num', '=', $number)
                        ->whereYear('doc_date', '=', $doc_year)
                        ->where('asap_last_version', '=', 1)
                        ->get();
                }
            }

//            if($pris->count() == 0){
//                //TODO if not found last version search by last by id
//                $pris = Pris::where('legal_act_type_id', '=', $category)->where('doc_num', '=', $number)->where('doc_date', '=', $docDate)->orderBy('id', 'desc')->limit(1)->get();
//            }

            if ($pris->count() != 1) {
                if ($docDate >= '1990-03-22') {
                    file_put_contents('old_pris_connections_missing_documents.txt', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
                }
                $this->comment('PRIS exist duplicated or not exist at all: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
                return null;
            }

            return $pris->first()->id;
        }
    }
}
