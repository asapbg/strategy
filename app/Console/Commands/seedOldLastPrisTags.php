<?php

namespace App\Console\Commands;

use App\Models\Pris;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class seedOldLastPrisTags extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'old:pris_tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate last PRIS data to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();
        $this->info('Start at ' . date('Y-m-d H:i:s'));
        $locales = config('available_languages');
        $ourTags = Tag::with(['translation'])->get()->pluck('id', 'translation.label')->toArray();
        $ourPris = Pris::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('pris')->select('select max(archimed.e_items.id) from archimed.e_items');
        //start from this id in old database
        //$currentStep = DB::table('pris')->select(DB::raw('max(old_id) as max'))->first()->max + 1;
        $currentStep = 0;

        if ((int)$maxOldId[0]->max) {
            $stop = false;
            $maxOldId = (int)$maxOldId[0]->max;
            try {
                while ($currentStep <= $maxOldId && !$stop) {
                    //echo "FromId: ".$currentStep.PHP_EOL;
                    $oldDbResults = DB::connection('pris')->select('
                        select pris.id as old_id, pris."xml" as to_parse_xml_details
                          FROM archimed.e_items pris
                         where true
                           and pris.id >= ' . $currentStep . '
                           and pris.id < ' . ($currentStep + $step) . '
                           --and pris.itemtypeid <> 5017 -- skip law records
                      order by pris.id asc
                    ');

                    if (sizeof($oldDbResults)) {
                        foreach ($oldDbResults as $oldDbResult) {
                            $tags = [];

                            $xml = simplexml_load_string($oldDbResult->to_parse_xml_details);
                            $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
                            $data = json_decode($json, true);

                            //Update existing
                            if (isset($ourPris) && sizeof($ourPris) && isset($ourPris[(int)$oldDbResult->old_id])) {
                                $existPris = Pris::find($ourPris[(int)$oldDbResult->old_id]);

                                if ($existPris) {
                                    if (isset($data['DocumentContent']) && isset($data['DocumentContent']['Attribute']) && sizeof($data['DocumentContent']['Attribute'])) {
                                        $attributes = $data['DocumentContent']['Attribute'];
                                        foreach ($attributes as $att) {
                                            //get tags
                                            if (isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Термини') {
                                                if (isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                                    //echo "Tags: ".$att['Value']['Value'].PHP_EOL;
                                                    $tags = preg_split('/\r\n|\r|\n/', $att['Value']['Value']);
                                                } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                                    //echo "Tags: ".$att['Value'].PHP_EOL;
                                                    $tags = preg_split('/\r\n|\r|\n/', $att['Value']);
                                                }
                                            }
                                        }

                                        //3. Create connection pris - tags
                                        if (sizeof($tags)) {
                                            $newTags = array();
                                            foreach ($tags as $tag) {
                                                if (!isset($ourTags[$tag])) {
                                                    //create tag
                                                    $newTag = \App\Models\Tag::create();
                                                    if ($newTag) {
                                                        foreach ($locales as $locale) {
                                                            $newTag->translateOrNew($locale['code'])->label = $tag;
                                                        }
                                                    }
                                                    $newTag->save();
                                                    $this->info("Tag with name $tag created successfully");
                                                    $ourTags[$tag] = $newTag->id;
                                                }
                                                $newTags[] = '(' . (int)$ourTags[$tag] . ', ' . $existPris->id . ')';
                                            }

                                            if (sizeof($newTags)) {
                                                DB::statement('delete from pris_tag where pris_id =' . $existPris->id);
                                                DB::statement('insert into pris_tag values ' . implode(',', $newTags));
                                                $this->comment('Tags for Pris with old id ' . $oldDbResult->old_id . ' are updated');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($currentStep == $maxOldId) {
                        $stop = true;
                    } else {
                        $currentStep += $step;
                        if ($currentStep > $maxOldId) {
                            $currentStep = $maxOldId;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error('Error: '. $e->getMessage());
                Log::error('Migration old pris: ' . $e);
            }
        }
        $this->info('End at ' . date('Y-m-d H:i:s'));
    }
}
