<?php

namespace App\Console\Commands;

use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use Illuminate\Console\Command;

class ActualPrograms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'programs:actual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if programs are expired and there is other which must be set as actual';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $opExpired = OperationalProgram::Expired()->get();
        if( $opExpired->count() ) {
            $expiredIds = $opExpired->pluck('id')->toArray();
            OperationalProgram::whereIn('id', $expiredIds)
                ->where(['actual' => 1])
                ->update(['actual' => 0]);
        }

        $lpExpired = LegislativeProgram::Expired()->get();
        if( $lpExpired->count() ) {
            $expiredIds = $lpExpired->pluck('id')->toArray();
            LegislativeProgram::whereIn('id', $expiredIds)
                ->where(['actual' => 1])
                ->update(['actual' => 0]);
        }

        //Search for actual
        OperationalProgram::Actual()
            ->where(['actual' => 0])
            ->update(['actual' => 1]);
        LegislativeProgram::Actual()
            ->where(['actual' => 0])
            ->update(['actual' => 1]);
    }
}
