<?php

namespace App\Services\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Models\File;
use Carbon\Carbon;

class AdvisoryBoardFileService
{

    public function __construct()
    {
    }

    public function upload(
        $file,
        string $language,
        int $id_object,
        int $id_subject,
        string $doc_type,
        bool $is_update = false,
        string $description = null,
        ?string $custom_name = null,
        ?string $resolution = null,
        ?string $state_newspaper = null,
        ?string $effective_at = null,
        ?int $parent_id = null
    ): void
    {
        if (!$file) {
            return;
        }

        $version = null;

        if ($is_update) {
            $version = File::where('locale', '=', $language)
                ->where('id_object', '=', $id_object)
                ->where('doc_type', '=', $doc_type)
                ->where('code_object', '=', File::CODE_AB)
                ->count();
        }

        $store_name = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
        $dir = File::ADVISORY_BOARD_UPLOAD_DIR . $id_subject . DIRECTORY_SEPARATOR;

        $sub_dir = match ((int)$doc_type) {
            DocTypesEnum::AB_SECRETARIAT->value => File::ADVISORY_BOARD_SECRETARIAT_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            DocTypesEnum::AB_FUNCTION->value => File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            DocTypesEnum::AB_REGULATORY_FRAMEWORK->value => File::ADVISORY_BOARD_REGULATORY_FRAMEWORK_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value => File::ADVISORY_BOARD_MEETINGS_AND_DECISIONS_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            DocTypesEnum::AB_MODERATOR->value => File::ADVISORY_BOARD_MODERATOR_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            DocTypesEnum::AB_CUSTOM_SECTION->value => File::ADVISORY_BOARD_CUSTOM_SECTION_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            default => '',
        };

        $full_dir = $dir . $sub_dir . $id_object . DIRECTORY_SEPARATOR;
//        dd($full_dir);
        $file->storeAs($full_dir, $store_name, 'public_uploads');

        $newFile = new File([
            'id_object' => $id_object,
            'code_object' => File::CODE_AB,
            'filename' => $store_name,
            'doc_type' => $doc_type,
            'content_type' => $file->getClientMimeType(),
            'path' => $full_dir . $store_name,
            'description_' . $language => $description ?? '',
            'sys_user' => auth()->user()->id,
            'locale' => $language,
            'version' => ($version + 1) . '.0',
            'custom_name' => $custom_name,
            'resolution_council_ministers' => $resolution,
            'state_newspaper' => $state_newspaper,
            'effective_at' => Carbon::parse($effective_at),
            'parent_id' => $parent_id,
        ]);

        $newFile->save();
    }
}
