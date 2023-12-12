<?php

namespace Database\Seeders;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardRegulatoryFramework;
use App\Models\AdvisoryBoardRegulatoryFrameworkTranslation;
use App\Models\File;
use App\Services\AdvisoryBoard\AdvisoryBoardFileService;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AdvisoryBoardRegulatoryFrameworkSeeder extends Seeder
{

    /** @var array - Our advisory board ids. */
    private array $advisory_board_ids = [];

    private Collection $advisory_board_frameworks;

    private Collection $advisory_board_framework_translations;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->advisory_board_ids = AdvisoryBoard::select('id')->pluck('id')->toArray();

        AdvisoryBoardRegulatoryFramework::truncate();
        AdvisoryBoardRegulatoryFrameworkTranslation::truncate();

        $this->importOrganizationRules();

        $this->advisory_board_frameworks = AdvisoryBoardRegulatoryFramework::all();
        $this->advisory_board_framework_translations = AdvisoryBoardRegulatoryFrameworkTranslation::all();

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

        foreach ($old_establishments as $framework) {
            if (!in_array($framework->councilID, $this->advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $establishment = $this->advisory_board_frameworks->first(fn($record) => $record->advisory_board_id === $framework->councilID) ?? new AdvisoryBoardRegulatoryFramework();
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
                        DocTypesEnum::AB_REGULATORY_FRAMEWORK->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = $this->advisory_board_framework_translations->first(fn($record) => $record->advisory_board_regulatory_framework_id === $establishment->id && $record->locale === $language['code']) ??
                    new AdvisoryBoardRegulatoryFrameworkTranslation();

                $translation->locale = $language['code'];
                $translation->advisory_board_regulatory_framework_id = $establishment->id;
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
            if (!in_array($framework->councilID, $this->advisory_board_ids)) {
                $skipped++;
                continue;
            }

            $new_framework = new AdvisoryBoardRegulatoryFramework();
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
                        DocTypesEnum::AB_REGULATORY_FRAMEWORK->value,
                        $file['content_type'],
                        $file['path'],
                        $file['version']
                    );

                    $files_imported++;
                }
            }

            foreach (config('available_languages') as $language) {
                $translation = new AdvisoryBoardRegulatoryFrameworkTranslation();
                $translation->locale = $language['code'];
                $translation->advisory_board_regulatory_framework_id = $new_framework->id;
                $translation->description = $framework->description ?? '';
                $translation->save();
            }

            $imported++;
        }

        $this->command->info("$imported advisory board regulatory frameworks organization rules were imported successfully at " . date("H:i") . " and $skipped were skipped. Totally $files_imported files imported.");
    }
}
