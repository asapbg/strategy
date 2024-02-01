<?php

namespace App\Console\Commands;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\FieldOfAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\StrategicDocument;
use App\Models\User;

class seedOldStrategicDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:sd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy strategic documents to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locales = config('available_languages');

        $ourDocuments = StrategicDocument::withTrashed()->get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();

        $oldCategories = collect(
            DB::connection('old_strategy_app')->select('SELECT id, parentid, sectionid, categoryname FROM dbo.categories WHERE languageid = 1')
        );

        $oldDocuments = DB::connection('old_strategy_app')->select(
            "SELECT
                sd.id AS old_id,
                sd.title AS title,
                sd.summary AS description,
                sd.categoryid AS category_id,
                sd.createdbyuserid AS user_id,
                sd.validto AS document_date_expiring,
                sd.createdbyuserid AS user_id,
                sd.institutiontypeid AS accept_act_institution_type_id,
                -- sd.documenttypeid AS pris_act_type_id -- TODO: IDK
                sd_it.institutiontypename AS institution_type_name,
                sd.documentdate AS document_date_accepted,
                datecreated AS created_at,
                datemodified AS updated_at,
                CASE WHEN sd.isactive = true THEN 1 ELSE 0 END AS active,
                CASE WHEN sd.isdeleted = true THEN CURRENT_TIMESTAMP ELSE NULL END AS deleted_at
            FROM dbo.strategicdocuments AS sd
            LEFT JOIN dbo.institutiontypes AS sd_it ON sd.institutiontypeid = sd_it.id AND sd_it.languageid = 1
            WHERE sd.languageid = 1"
        );

        $policyAreasDB = FieldOfAction::withTrashed()->with('translations', function ($q){
            $q->withTrashed();
        })->get();
        $policyAreas = array();
        if($policyAreasDB->count()){
            foreach ($policyAreasDB as $p){
                $policyAreas[$p->translate('bg')->name] = $p->id;
            }
        }

        $acceptingInstitutions = AuthorityAcceptingStrategic::with('translations')->get();
        $ourUsers = User::withTrashed()->get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();

        try {
            DB::beginTransaction();

            foreach ($oldDocuments as $oldDocument) {
                $mappedKeys = $this->mapForeignKeysByCategory(
                    $oldDocument->category_id,
                    $oldCategories,
                    $policyAreas,
                );

                $data = array_merge(
                    (array) $oldDocument,
                    $mappedKeys
                );

                $title = $data['title'];
                $description = $data['description'];

                // Create accept act institution if missing
                if (isset($data['institution_type_name'])) {
                    $institutionName = trim($data['institution_type_name']);

                    $acceptingInstitution = $acceptingInstitutions->where('name', $institutionName)->first();

                    if (!isset($acceptingInstitution)) {
                        $acceptingInstitution = new AuthorityAcceptingStrategic();

                        foreach ($locales as $locale) {
                            $acceptingInstitution->translateOrNew($locale['code'])->name = $institutionName;
                        }

                        $acceptingInstitution->save();
                    }

                    $data['accept_act_institution_type_id'] = $acceptingInstitution->id ?? null;
                }
                //

                $data['user_id'] = $ourUsers[$oldDocument->user_id] ?? null;

                unset(
                    $data['title'],
                    $data['description'],
                    $data['category_id'],
                    $data['institution_type_name']
                );

                if (isset($ourDocuments[$oldDocument->old_id])) {
                    $strategicDoc = StrategicDocument::withTrashed()->find($ourDocuments[$oldDocument->old_id]);

                    $strategicDoc->update($data);

                    $this->info('Updated data with ID: ' . $strategicDoc->id);
                } else {
                    $strategicDoc = StrategicDocument::create($data);

                    $this->info('Inserted data with ID: ' . $strategicDoc->id);
                }

                foreach ($locales as $locale) {
                    $strategicDoc->translateOrNew($locale['code'])->title = $title;
                    $strategicDoc->translateOrNew($locale['code'])->description = html_entity_decode($description);
                }

                $strategicDoc->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    /**
     * Returns data for the fields strategic_document_level_id, policy_area_id
     */
    public function mapForeignKeysByCategory(
        $oldStrategicDocCategoryId,
        \Illuminate\Support\Collection $oldCategories,
        array $policyAreas
    ) {
        $oldCategory = $oldCategories->where('id', $oldStrategicDocCategoryId)->first();
        try {
            $levelMapping = array(
                1 => InstitutionCategoryLevelEnum::CENTRAL->value,
                2 => InstitutionCategoryLevelEnum::AREA->value,
                3 => InstitutionCategoryLevelEnum::MUNICIPAL->value,
            );

            return [
                'strategic_document_level_id' => $levelMapping[(int)$oldCategory->parentid] ?? null,
                'policy_area_id' => $oldCategory ? ($policyAreas[$oldCategory->categoryname] ?? null) : null,
                'ekatte_municipality_id' => null,
                'ekatte_area_id' => null,
            ];
        } catch (\Throwable $th) {
            $this->info($oldCategory?->categoryname . ' ' . $oldCategory?->id);
            throw $th;
        }
    }
}
