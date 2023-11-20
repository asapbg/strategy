<?php

namespace App\Enums;
use App\Models\File;

enum StrategicDocumentFileEnum
{
    /**
     * @param $lang
     * @return string[]
     */
    public static function validationRules($lang): array
    {
        return match($lang) {
            'bg' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)],
            'en' => ['sometimes', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)],
        };
    }
}
