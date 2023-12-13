<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardEstablishment;
use App\Models\AdvisoryBoardEstablishmentTranslation;
use App\Models\AdvisoryBoardOrganizationRule;
use App\Models\AdvisoryBoardOrganizationRuleTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AdvisoryBoardRegulatoryFrameworkSeeder extends Seeder
{

    /** @var array - Our advisory board ids. */
    private array $advisory_board_ids = [];

    private Collection $advisory_board_organization_rules;

    private Collection $advisory_board_establishments;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        $this->advisory_board_organization_rules = AdvisoryBoardOrganizationRule::all();

        $this->advisory_board_establishments = AdvisoryBoardEstablishment::all();

        $this->importOrganizationRules();

        $this->importEstablishments();
    }

    private function importEstablishments(): void
    {
        $this->command->info("Import of advisory board regulatory frameworks establishments begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_establishments = DB::connection('old_strategy')->select(
            "select * from councildetails c where c.\"name\" = 'establishment' and c.title ilike '%акт%' and c.\"toVersion\" is null"
        );

        $all_establishment_ids = $this->advisory_board_establishments->pluck('id')->toArray();

        foreach ($old_establishments as $framework) {
            if (in_array($framework->detailID, $all_establishment_ids)) {
                $skipped++;
                continue;
            }

            if (!in_array($framework->councilID, $this->advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $establishment = new AdvisoryBoardEstablishment();
            $establishment->id = $framework->detailID;
            $establishment->advisory_board_id = $framework->councilID;
            $establishment->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $framework->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_REGULATORY_FRAMEWORK_ESTABLISHMENT_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $framework->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_REGULATORY_FRAMEWORK_ESTABLISHMENT_UPLOAD_DIR .
                DIRECTORY_SEPARATOR . $establishment->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $framework->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $framework->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    $service->storeDbRecord(
                        $establishment->id,
                        File::CODE_AB,
                        $file['filename'],
                        DocTypesEnum::AB_ESTABLISHMENT_RULES->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardEstablishmentTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_establishment_id = $establishment->id;
                $translation->description .= '<br><br>' . $framework->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board regulatory frameworks establishments were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }


    private function importOrganizationRules(): void
    {
        $this->command->info("Import of advisory board regulatory frameworks organization rules begins at " . date("H:i"));

        $imported = 0;
        $skipped = 0;
        $files_imported = 0;

        $old_frameworks = DB::connection('old_strategy')->select(
            "select * from councildetails c where c.\"name\" ilike '%organization%' and c.title ilike '%правилник%' and c.\"toVersion\" is null"
        );

        foreach ($old_frameworks as $framework) {
            if (in_array($framework->detailID, $this->advisory_board_organization_rules->pluck('id')->toArray())) {
                $skipped++;
                continue;
            }

            if (!in_array($framework->councilID, $this->advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_framework = new AdvisoryBoardOrganizationRule();
            $new_framework->id = $framework->detailID;
            $new_framework->advisory_board_id = $framework->councilID;
            $new_framework->save();

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $framework->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_REGULATORY_FRAMEWORK_ORGANIZATION_RULES_UPLOAD_DIR
            );
            mkdirIfNotExists($directory);

            $directory = base_path(
                'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_UPLOAD_DIR .
                $framework->councilID . DIRECTORY_SEPARATOR . File::ADVISORY_BOARD_REGULATORY_FRAMEWORK_ORGANIZATION_RULES_UPLOAD_DIR .
                DIRECTORY_SEPARATOR . $new_framework->id
            );
            mkdirIfNotExists($directory);

            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $framework->folderID);
            $copied_files = copyFiles($directory_to_copy_from, $directory, $framework->folderID);

            if (!empty($copied_files)) {
                $service = app(AdvisoryBoardFileService::class);

                foreach ($copied_files as $file) {
                    $service->storeDbRecord(
                        $new_framework->id,
                        File::CODE_AB,
                        $file['filename'],
                        DocTypesEnum::AB_ORGANIZATION_RULES->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardOrganizationRuleTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_organization_rule_id = $new_framework->id;
                $translation->description = $framework->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board regulatory frameworks organization rules were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
