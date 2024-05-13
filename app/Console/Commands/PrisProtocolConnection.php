<?php

namespace App\Console\Commands;

use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrisProtocolConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:protocol';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect protocol to decisions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $maxId = Pris::where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL_DECISION)->where('doc_num', 'like', '%.%')->max('id');

        if($maxId){
            $step = 50;
            $currentStep = 0;
            $stop = false;

            while ($currentStep <= $maxId && !$stop) {
                $this->info('Start between '.$currentStep.' and '. ($currentStep + $step));
                $items = Pris::where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL_DECISION)
                    ->where('doc_num', 'like', '%.%')
                    ->where('id', '>=', $currentStep)
                    ->where('id', '<', $currentStep + $step)
                    ->get();
                if($items->count()){
                    foreach ($items as $item){
                        $protocol = explode('.', $item->doc_num);
                        if(!sizeof($protocol) == 2){
                            $this->clearProtocol($item);
                        } else{
                            $protocol = $protocol[0];
                            $dbProtocol = Pris::where('doc_num', $protocol)
                                ->where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL)
                                ->where('doc_date', '>=', Carbon::parse($item->doc_date)->startOfYear()->format('Y-m-d'))
                                ->where('doc_date', '<=', Carbon::parse($item->doc_date)->endOfYear()->format('Y-m-d'))
                                ->where('asap_last_version', 1)
                                ->get()
                                ->first();

                            if(!$dbProtocol){
                                $this->clearProtocol($item);
                            } else{
                                \DB::statement('update pris set decision_protocol = '.$dbProtocol->id.' where id = '.$item->id);
                            }
                        }
                    }
                }

                if($currentStep == $maxId){
                    $stop = true;
                } else{
                    $currentStep += $step;
                    if($currentStep > $maxId){
                        $currentStep = $maxId;
                    }
                }
            }
        }

        return Command::SUCCESS;
    }

    private function clearProtocol($item): void
    {
        \DB::statement('update pris set decision_protocol = null where id = '.$item->id);
    }
}
