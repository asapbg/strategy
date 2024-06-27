<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateOpenData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:open_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OpenData reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reports = [
            'pris' => ['standard', 'archive'],
            'public-consultations' => ['standard', 'field-of-actions', 'field-of-actions-institution', 'institutions', 'full'],
            'strategic-documents' => ['standard', 'full'],
            'legislative-initiatives' => ['standard'],
            'legislative-program' => ['standard'],
            'operational-program' => ['standard'],
            'adv-boards' => ['standard'],
            'impact_assessments' => ['executors'],
            'library' => ['standard'],
            'polls' => ['standard'],
            'ogp' => ['full'],

        ];
        $this->comment('Start at: '.date('Y-m-d H:i:s'));

        foreach ($reports as $section => $types){
            foreach ($types as $type){
                $action = match ($section) {
                    'public-consultations' => 'apiReportPc',
                    'strategic-documents' => 'apiReportSd',
                    'legislative-initiatives' => 'apiReportLegislativeInitiative',
                    'legislative-program' => 'apiReportLp',
                    'operational-program' => 'apiReportOp',
                    'adv-boards' => 'apiReportAdvBoards',
                    'pris' => 'apiReportPris',
                    'impact_assessments' => 'apiReportImpactAssessments',
                    'library' => 'apiReportLibrary',
                    'polls' => 'apiReportPolls',
                    'ogp' => 'apiOgp',
                    default => '',
                };
                $this->comment($action.'@'.$type.' start at: '.date('Y-m-d H:i:s'));
                \App::call('App\Http\Controllers\ReportsController@'.$action, ['type' => $type, 'inFile' => true]);
                $this->comment($action.'@'.$type.' end at: '.date('Y-m-d H:i:s'));
            }
        }
        $this->comment('End at: '.date('Y-m-d H:i:s'));
    }
}
