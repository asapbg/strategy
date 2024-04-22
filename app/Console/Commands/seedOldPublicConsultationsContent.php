<?php

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
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

class seedOldPublicConsultationsContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:pc_content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy public consultations to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();
        $locales = config('available_languages');

        //records per query
        $step = 50;
        $currentStep = 1;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        //start from this id in old database
        $ourPc = PublicConsultation::whereHas('translation', function ($query) {
//            $query->whereNull('description')->where('locale', '=', 'bg');
            $query->where('locale', '=', 'bg');
        })->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        if ((int)$maxOldId[0]->max) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: " . $currentStep . PHP_EOL;
                $oldDbResult = DB::connection('old_strategy_app')
                    ->select('select
                        pc.id as old_id,
                        pc.summary as description
                    from dbo.publicconsultations pc
                        where pc.languageid = 1
                        and pc.id >= ' . $currentStep . '
                        and pc.id < ' . ($currentStep + $step) . '
                    order by pc.id ');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        if (isset($ourPc[(int)$item->old_id])) {
                            DB::beginTransaction();
                            try {
                                $existPc = PublicConsultation::find($ourPc[(int)$item->old_id]);
                                if ($existPc) {
                                    foreach ($locales as $locale) {
//                                        dd($item->description);
                                        $existPc->translateOrNew($locale['code'])->description = stripHtmlTags(html_entity_decode($item->description));
//                                        DB::statement('UPDATE public_consultation_translations set description = ? where public_consultation_id = ? and locale = ?', [$item->description, ((int)$ourPc[$item->old_id]), $locale['code']]);
                                    }
                                    $existPc->save();
                                    $this->comment('PC ID  with Old ID ' . $item->old_id . ' is updated');
                                }
                                DB::commit();
                            } catch (\Exception $e) {
                                Log::error('Migration old startegy public consultations,update description for old id ' . $item->old_id . ' error:' . $e);
                                DB::rollBack();
                            }
                        }
                    }
                    $currentStep += $step;
                }
            }
        }
    }
}
