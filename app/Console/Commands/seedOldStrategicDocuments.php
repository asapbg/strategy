<?php

namespace App\Console\Commands;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Controllers\CommonController;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\FieldOfAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\StrategicDocument;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

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

    private array $policyAreas;
    private array $policyAreasArea;
    private array $policyAreasMunicipal;
    private array $languages;

    public function __construct()
    {
        parent::__construct();
//        DB::statement('delete from field_of_action_translations where field_of_action_id > 983');
//        DB::statement('delete from field_of_actions where id > 983');
//        DB::statement('update strategic_document set policy_area_id = null where policy_area_id > 983');

        $this->languages = config('available_languages');
        //Our policy area
        $policyAreasDBCentral = FieldOfAction::Central()->withTrashed()->with('translations')->get();
        $this->policyAreas = array();
        if($policyAreasDBCentral->count()){
            foreach ($policyAreasDBCentral as $p){
                $this->policyAreas[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }
        $policyAreasDBArea = FieldOfAction::Area()->withTrashed()->with('translations')->get();
        $this->policyAreasArea = array();
        if($policyAreasDBArea->count()){
            foreach ($policyAreasDBArea as $p){
                $this->policyAreasArea[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }
        $policyAreasDBMunicipal = FieldOfAction::Municipal()->withTrashed()->with('translations')->get();
        $this->policyAreasMunicipal = array();
        if($policyAreasDBMunicipal->count()){
            foreach ($policyAreasDBMunicipal as $p){
                $this->policyAreasMunicipal[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }

    }

    public function handle()
    {
        $this->info('Start at '.date('Y-m-d H:i:s'));
        file_put_contents('missing_field_of_actions_strategic_documents.txt', '');

        $acceptingInstitutions = AuthorityAcceptingStrategic::with('translations')
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('id', 'name')
            ->toArray();
        $locales = config('available_languages');

        $ourDocuments = StrategicDocument::withTrashed()->get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();

        //Old app categories
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
                sd.datecreated AS created_at,
                sd.datemodified AS updated_at,
                CASE WHEN sd.isactive = true THEN 1 ELSE 0 END AS active,
                CASE WHEN sd.isdeleted = true THEN CURRENT_TIMESTAMP ELSE NULL END AS deleted_at,
                cat.parentid as cat_parentid,
                cat.sectionid as cat_sectionid,
                cat.categoryname as cat_name
            FROM dbo.strategicdocuments AS sd
            LEFT JOIN dbo.institutiontypes AS sd_it ON sd.institutiontypeid = sd_it.id AND sd_it.languageid = 1
            LEFT JOIN dbo.categories AS cat ON cat.id = sd.categoryid AND cat.languageid = 1
            WHERE sd.languageid = 1"
        );



        //$acceptingInstitutions = AuthorityAcceptingStrategic::with('translations')->get()->pluck('id', 'name')->toArray();
        //dd($acceptingInstitutions);
        $ourUsers = User::withTrashed()->get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();

        try {
            DB::beginTransaction();

            foreach ($oldDocuments as $oldDocument) {
                $mappedKeys = $this->mapForeignKeysByCategory(
                    $oldDocument,
                    $oldCategories
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

                    //$acceptingInstitution = $acceptingInstitutions->where('name', $institutionName)->first();

                    if (!isset($acceptingInstitutions[$institutionName])) {
                        $acceptingInstitution = new AuthorityAcceptingStrategic();

                        foreach ($locales as $locale) {
                            $acceptingInstitution->translateOrNew($locale['code'])->name = $institutionName;
                        }

                        $acceptingInstitution->save();
                        $data['accept_act_institution_type_id'] = $acceptingInstitution->id ?? null;
                        $acceptingInstitutions[$institutionName] = $acceptingInstitution->id;
                    } else{
                        $data['accept_act_institution_type_id'] = (int)$acceptingInstitutions[$institutionName];
                    }
                }
                //

                $data['user_id'] = $ourUsers[$oldDocument->user_id] ?? null;

                unset(
                    $data['title'],
                    $data['description'],
                    $data['category_id'],
                    $data['institution_type_name'],
                    $data['cat_parentid'],
                    $data['cat_sectionid'],
                    $data['cat_name']
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
        $this->info('End at '.date('Y-m-d H:i:s'));
    }

    /**
     * Returns data for the fields strategic_document_level_id, policy_area_id
     */
    public function mapForeignKeysByCategory(
        $oldStrategicDoc,
        \Illuminate\Support\Collection $oldCategories
    ) {
//        $oldCategory = $oldCategories->where('id', $oldStrategicDocCategoryId)->first();
        try {
            $levelMapping = array(
                FieldOfAction::CATEGORY_NATIONAL => InstitutionCategoryLevelEnum::CENTRAL->value,
                FieldOfAction::CATEGORY_AREA => InstitutionCategoryLevelEnum::AREA->value,
                FieldOfAction::CATEGORY_MUNICIPAL => InstitutionCategoryLevelEnum::MUNICIPAL->value,
            );

            $policy_area_id = null;
            $strategic_document_level_id = InstitutionCategoryLevelEnum::CENTRAL_OTHER->value;

            //Level
            if((int)$oldStrategicDoc->cat_parentid > 0 && isset($levelMapping[(int)$oldStrategicDoc->cat_parentid])) {
                $strategic_document_level_id = $levelMapping[(int)$oldStrategicDoc->cat_parentid];
            }

            //Policy Area
            if(!empty($oldStrategicDoc->cat_name)){
                if((int)$oldStrategicDoc->cat_parentid > 0){
                    $strategic_document_level_id = $levelMapping[(int)$oldStrategicDoc->cat_parentid];
                    if((int)$oldStrategicDoc->cat_parentid == FieldOfAction::CATEGORY_AREA){
                        if(empty($this->policyAreasArea) || !isset($this->policyAreasArea[mb_strtolower($oldStrategicDoc->cat_name)])) {
                            file_put_contents('missing_field_of_actions_strategic_documents.txt', $oldStrategicDoc->cat_name . PHP_EOL, FILE_APPEND);
                        }
                        $policy_area_id = $this->policyAreasArea[mb_strtolower($oldStrategicDoc->cat_name)];
                    } else if((int)$oldStrategicDoc->cat_parentid == FieldOfAction::CATEGORY_MUNICIPAL) {
                        if(empty($this->policyAreasMunicipal) || !isset($this->policyAreasMunicipal[mb_strtolower($oldStrategicDoc->cat_name)])) {
                            file_put_contents('missing_field_of_actions_strategic_documents.txt', $oldStrategicDoc->cat_name . PHP_EOL, FILE_APPEND);
                        }
                        $policy_area_id = $this->policyAreasMunicipal[mb_strtolower($oldStrategicDoc->cat_name)];
                    } else {
                        //Central
                        if(empty($this->policyAreas) || !isset($this->policyAreas[mb_strtolower($oldStrategicDoc->cat_name)])) {
                            file_put_contents('missing_field_of_actions_strategic_documents.txt', $oldStrategicDoc->cat_name . PHP_EOL, FILE_APPEND);
                        }
                        $policy_area_id = $this->policyAreas[mb_strtolower($oldStrategicDoc->cat_name)];
                    }
                } else{
                    //Main category
                    if(in_array((int)$oldStrategicDoc->category_id, [FieldOfAction::CATEGORY_NATIONAL,FieldOfAction::CATEGORY_AREA,FieldOfAction::CATEGORY_MUNICIPAL])){
                        $policy_area_id = (int)$oldStrategicDoc->category_id;
                        $strategic_document_level_id = $levelMapping[(int)$oldStrategicDoc->cat_parentid];
                    }
                }
            }

            return [
                'strategic_document_level_id' => $strategic_document_level_id,
                'policy_area_id' => $policy_area_id,
                'ekatte_municipality_id' => null,
                'ekatte_area_id' => null,
            ];
        } catch (\Throwable $th) {
            $this->info('Error mapping sd level and policy area: category name - '.$oldStrategicDoc->cat_name . ' | category parentid - ' . $oldStrategicDoc->cat_parentid);
            throw $th;
        }
    }
}
