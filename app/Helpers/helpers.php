<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

if (!function_exists('currentUser')) {

    /**
     * If more then one guard is used in the app
     * return the correct user's instance
     */
    function currentUser()
    {
        $guard = currentGuard();

        if ($guard == config('auth')['defaults']['guard']) {
            if (Auth::check() && Auth::user() && Auth::user() instanceof User) {
                return Auth::user();
            }
        } else {
            $model = "\App\Models\\" . capitalize($guard);
            if (!class_exists($model)) {
                $model = "\App\\" . capitalize($guard);
                if (!class_exists($model)) {
                    return null;
                }
            }
            if (Auth::guard($guard)->check() && Auth::guard($guard)->user() instanceof $model) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}

if (!function_exists('currentGuard')) {

    /**
     * If more then one guard is used in the app return the current guard
     *
     * @return string
     */
    function currentGuard()
    {
        if (Auth::guard() instanceof \Illuminate\Auth\SessionGuard) {
            return explode("_", Auth::guard()->getName())[1];
        }

        return config('auth')['defaults']['guard'];
    }
}

if (!function_exists('databaseDate')) {

    /**
     * Return date in Y-m-d format for storing in database
     *
     * @param $date
     *
     * @return false|string
     */
    function databaseDate($date)
    {
        if (!$date) {
            return null;
        }
        return date("Y-m-d", strtotime($date));
    }
}

if (!function_exists('databaseDateTime')) {

    /**
     * Return datetime in Y-m-d H:i:s format for storing in database
     *
     * @param $date
     *
     * @return false|string
     */
    function databaseDateTime($datetime)
    {
        if (!$datetime) {
            return null;
        }
        return date("Y-m-d H:i:s", strtotime($datetime));
    }
}

if (!function_exists('displayDate')) {

    /**
     * Return date from datetime string in d-m-Y format
     *
     * @param $datetime
     *
     * @return false|string
     */
    function displayDate($datetime)
    {
        if (!$datetime) {
            return "";
        }
        return date(config('app.date_format'), strtotime($datetime));
    }
}

if (!function_exists('displayDateTime')) {

    /**
     * Return date from datetime string in d-m-Y H:i format
     *
     * @param $datetime
     *
     * @return false|string
     */
    function displayDateTime($datetime)
    {
        if (!$datetime) {
            return "";
        }
        return date(config('app.date_format') . ' H:i', strtotime($datetime));
    }
}

if (!function_exists('printDate')) {

    /**
     * Return date from datetime string in d-MMM-Y format in bg
     *
     * @param $datetime
     *
     * @return false|string
     */
    function printDate()
    {
        $format = new IntlDateFormatter('bg_BG', IntlDateFormatter::NONE,
            IntlDateFormatter::NONE, NULL, NULL, 'd-MMM-Y');
        return datefmt_format($format, mktime('0', '0', '0'));
    }
}

if (!function_exists('capitalize')) {

    /**
     * Capitalize a given string
     *
     * @param $string
     *
     * @return string
     */
    function capitalize($string)
    {
        $firstChar = mb_substr($string, 0, 1, "UTF-8");
        $then = mb_substr($string, 1, null, "UTF-8");

        return mb_strtoupper($firstChar, "UTF-8") . $then;
    }
}

if (!function_exists('u_trans')) {

    /**
     * Translates the string and converts its first letter to capital
     *
     * @method u_trans
     * @param string  $value string to translate
     * @param integer $count singular or plural
     *
     * @return string
     */
    function u_trans($value, $count = 1)
    {
        return capitalize(trans_choice($value, $count));
    }
}

if (!function_exists('l_trans')) {

    /**
     * Translates the string and converts its first letter to lower
     *
     * @method l_trans
     * @param string  $value string to translate
     * @param integer $count singular or plural
     *
     * @return string
     */
    function l_trans($value, $count = 1)
    {
        return mb_strtolower(trans_choice($value, $count));
    }
}

if (!function_exists('rolesNames')) {

    /**
     * Get roles names by id
     *
     * @method rolesNames
     * @param array $ids
     *
     * @return array
     */
    function rolesNames(array $ids)
    {
        $roles = [];
        if (sizeof($ids)) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $ids)->get()->pluck('name')->toArray();
        }
        return $roles;
    }
}

if (!function_exists('getLocaleId')) {

    /**
     * returns id of the current locale based on configuration
     *
     * @method getLocaleId
     * @param string $code
     *
     * @return int
     */
    function getLocaleId(string $code): int
    {
        $id = 1; //by default get first language
        foreach (config('available_languages') as $key => $lang) {
            if ($code == $lang['code']) {
                $id = $key;
            }
        }
        return $id;
    }
}

if (!function_exists('optionsUserTypes')) {

    /**
     * Get all users types and return options
     *
     * @method optionsUserTypes
     *
     * @param bool $any
     * @param string|int $anyValue
     * @param string|int $anyName
     *
     * @return array
     */
    function optionsUserTypes(bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
    {
        $options = User::getUserTypes();
        if ($any) {
            $options[$anyValue] = $anyName;
            ksort($options);
        }
        return $options;
    }
}

/**
 * return publication types options
 *
 * @method optionsApplicationStatus
 *
 * @param bool       $any
 * @param string|int $anyValue
 * @param string|int $anyName
 *
 * @return array
 */
function optionsPublicationTypes(bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
{
    $options = [];
    if ($any) {
        $options[] = ['value' => $anyValue, 'name' => $anyName];
    }
    foreach (\App\Enums\PublicationTypesEnum::options() as $key => $value) {
        $options[] = ['value' => $value, 'name' => trans_choice("custom.public_sections.types.$key", 1)];
    }
    return $options;
}

if (!function_exists('optionsStatuses')) {

    /**
     * return regular status options
     *
     * @method optionsStatuses
     *
     * @param bool       $any
     * @param string|int $anyValue
     * @param string|int $anyName
     *
     * @return array
     */
    function optionsStatuses(bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
    {
        $options = array(
            1 => trans_choice('custom.active', 1),
            0 => trans_choice('custom.inactive', 1),
        );
        if ($any) {
            $options[$anyValue] = $anyName;
            ksort($options);
        }
        return $options;
    }
}

if (!function_exists('optionsPublished')) {

    /**
     * return regular status options
     *
     * @method optionsPublished
     *
     * @param bool       $any
     * @param string|int $anyValue
     * @param string|int $anyName
     *
     * @return array
     */
    function optionsPublished(bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
    {
        $options = array(
            1 => trans_choice('custom.published', 1),
            0 => trans_choice('custom.draft', 1),
        );
        if ($any) {
            $options[$anyValue] = $anyName;
            ksort($options);
        }
        return $options;
    }
}

if (!function_exists('logError')) {

    /**
     * Write to error log file
     *
     * @method logError
     * @param string $method
     * @param string $error
     */
    function logError(string $method, string $error): void
    {
        \Illuminate\Support\Facades\Log::error($method . ': ' . $error);
    }
}

if (!function_exists('stripHtmlTags')) {

    /**
     * return striped html string
     *
     * @param string $html_string
     * @param array  $tags
     *
     * @return string
     */
    function stripHtmlTags(string $html_string, array $tags = [])
    {
        $html_string = str_replace('style', 'tyle', $html_string); //clear web style when copy text
        $tagsToStrip = sizeof($tags) ? $tags : ['p', 'ul', 'ol', 'li', 'b', 'i', 'u'];
        return strip_tags($html_string, $tagsToStrip);
    }
}

if (!function_exists('extractMonths')) {

    /**
     * return months (with or without year) from time period
     *
     * @param string $form
     * @param string $to
     * @param bool   $year
     *
     * @return array
     */
    function extractMonths(string $form, string $to, bool $year = true): array
    {
        $months = [];
        $period = \Carbon\CarbonPeriod::create($form, '1 month', $to);
        foreach ($period as $d) {
            $months[] = $year ? $d->format("m.Y") : $d->format("m");
        }
        return $months;
    }
}

if (!function_exists('fileIcon')) {
    /**
     * @param string $fileType
     *
     * @return string
     */
    function fileIcon($fileType): string
    {
//        $icon = '<i class="fas fa-file-download text-secondary me-1"></i>';
        $icon = '<i class="fas fa-file text-secondary me-1"></i>';
        switch ($fileType) {
            case 'application/pdf':
            case 'pdf':
                $icon = '<i class="fas fa-file-pdf text-danger me-1"></i>';
                break;
            case 'text/csv':
                $icon = '<i class="fas fa-file-csv text-primary me-1"></i>';
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $icon = '<i class="fas fa-file-word text-info me-1"></i>';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $icon = '<i class="fas fa-file-excel text-success me-1"></i>';
                break;
            case 'application/rar':
            case 'application/x-rar':
                $icon = '<i class="fas fa-file-zipper text-primary me-1"></i>';
                break;
        }
        return $icon;
    }
}

if (!function_exists('optionsFromModel')) {

    /**
     * return prepared options for search form from standard model option
     *
     * @method optionsFromModel
     *
     * @param            $dbOptions
     * @param bool       $any
     * @param string|int $anyValue
     * @param string|int $anyName
     *
     * @return array
     */
    function optionsFromModel($dbOptions, bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
    {
        $options = [];
        if ($any) {
            $options[] = ['value' => $anyValue, 'name' => $anyName];
        }
        foreach ($dbOptions as $option) {
            $data = ['value' => $option->id, 'name' => $option->name];
            foreach ($option as $key => $value){
                if(!in_array($key, ['id', 'name'])){
                    $data['data-'.$key] = $value;
                }
            }
            $options[] = $data;
        }
        return $options;
    }
}

if (!function_exists('optionsStatusesFilter')) {

    /**
     * return regular status options for filter
     *
     * @method optionsStatusesFilter
     *
     * @param bool       $any
     * @param string|int $anyValue
     * @param string|int $anyName
     *
     * @return array
     */
    function optionsStatusesFilter(bool $any = false, string|int $anyValue = '', string|int $anyName = ''): array
    {
        $options = array(
          ['value' => 1, 'name' => trans_choice('custom.active', 1)],
          ['value' => 0, 'name' => trans_choice('custom.inactive', 1)]
        );
        if ($any) {
            $options[] = ['value' => $anyValue, 'name' => $anyName];
            ksort($options);
        }
        return $options;
    }
}

if (!function_exists('is_json')) {

    /**
     * Check if string is a json format.
     *
     * @param string $string
     *
     * @return bool
     */
    function is_json(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('compareByTimeStamp')) {

    /**
     * Check if string is a json format.
     *
     * @param $time1
     * @param $time2
     * @return bool
     */
    function compareByTimeStamp($time1, $time2)
    {
        if (strtotime($time1) > strtotime($time2))
            return 1;
        else if (strtotime($time1) < strtotime($time2))
            return -1;
        else
            return 0;
    }
}

if (!function_exists('fileHtmlContent')) {

    /**
     * Check if string is a json format.
     *
     * @param $file
     */
    function fileHtmlContent($file)
    {
        $content = '';
        $downLoadRoute = $file instanceof \App\Models\File ? route('download.file', $file) : route('strategy-document.download-file', $file);
        if(in_array($file->content_type, App\Models\File::CONTENT_TYPE_IMAGES) || in_array($file->content_type, \App\Models\File::IMG_CONTENT_TYPE)){
            return '<a class="mb-2 btn btn-sm btn-success" href="'.$downLoadRoute.'">'.__('custom.download').'</a><br>'.$file->preview;
        }

        switch ($file->content_type) {
            case 'application/pdf':
                $path = (!str_contains($file->path, 'files') ? 'files/' : '') . $file->path;
                $content = '<a class="mb-2 btn btn-sm btn-success" href="'.$downLoadRoute.'">'.__('custom.download').'</a><embed src="' . asset($path) . '" width="100%" height="700px" />';
                break;
            case 'application/msword': //doc
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': //xlsx
            case 'application/vnd.ms-excel': //xls
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': //docx
//                $downLoadRoute = $file instanceof \App\Models\File ? route('download.file', $file) : route('strategy-document.download-file', $file);
                $content = '<a class="mb-2 btn btn-sm btn-success" href="'.$downLoadRoute.'">'.__('custom.download').'</a><iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . $downLoadRoute . '" width="100%" height="700px;"/></iframe>';
                break;
//            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': //docx
//                //$content = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . route('download.file', $file) . '" width="100%" height="700px;"/></iframe>';
//                $content = \PhpOffice\PhpWord\IOFactory::load(Storage::disk('public_uploads')->path($file->path));
//                $content->setDefaultFontName('Fira Sans BGR');
//                $html = new \PhpOffice\PhpWord\Writer\HTML($content);
//                $content = $html->getContent();
//                break;
            default:
                return '<a class="mb-2 btn btn-sm btn-success" href="'.$downLoadRoute.'">'.__('custom.download').'</a><p>Документът не може да бъде визуализиран</p>';
        }

        return $content;
    }
}

if (!function_exists('fileHtmlContentByPath')) {

    /**
     * Check if string is a json format.
     *
     * @param string $path
     */
    function fileHtmlContentByPath(string $path, $type = 'pdf')
    {
        $content = '';

        switch ($type) {
            case 'pdf':
                $content = '<embed src="' . asset($path) . '" width="100%" height="700px" />';
                break;
            default:
                return '<p>Документът не може да бъде визуализиран</p>';
        }

        return $content;
    }
}

if (!function_exists('strategicFileHtmlContent')) {

    /**
     * Check if string is a json format.
     *
     * @param $file
     * @return bool
     */
    function strategicFileHtmlContent($file)
    {
        $content = '';
        switch ($file->content_type) {
            case 'application/pdf':
                $path = (!str_contains($file->path, 'files') ? 'files/' : '') . $file->path;
                $content = '<embed src="' . asset($path) . '" width="100%" height="700px" />';
                break;
            case 'application/msword':
                $content = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . route('strategy-document.download-file', $file) . '" width="100%" height="700px;"/></iframe>';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $content = \PhpOffice\PhpWord\IOFactory::load(Storage::disk('public_uploads')->path($file->path));
                $content->setDefaultFontName('Fira Sans BGR');
                $html = new \PhpOffice\PhpWord\Writer\HTML($content);
                $content = $html->getContent();
                break;
        }

        return $content;
    }
}

if (!function_exists('htmlToText')) {

    /**
     * return striped html string
     *
     * @param string $html_string
     * @return string
     */
    function htmlToText(string $html_string)
    {
        $html_string = str_replace('style', 'tyle', $html_string); //clear web style when copy text
        return strip_tags($html_string);
    }
}

if (!function_exists('paginationSelect')) {

    /**
     * return pagination options
     *
     * @return array
     */
    function paginationSelect()
    {
        return [
            ['value' => 10, 'name' => 10],
            ['value' => 20, 'name' => 20],
            ['value' => 30, 'name' => 30],
            ['value' => 40, 'name' => 40],
            ['value' => 50, 'name' => 50],
        ];
    }
}

if (!function_exists('enumToSelectOptions')) {

    /**
     * return pagination options
     *
     * @param array  $enums
     * @param string $translationBase
     * @param bool   $any
     *
     * @return array
     */
    function enumToSelectOptions(array $enums, string $translationBase = '', bool $any = false, $excludeValues = []): array
    {
        $options = [];
        if ($any) {
            $options[] = ['value' => '', 'name' => ''];
        }
        if (sizeof($enums)) {
            foreach ($enums as $name => $val) {
                if(empty($excludeValues) || !in_array($val, $excludeValues)){
                    $options[] = ['value' => $val, 'name' => !empty($translationBase) ? __('custom.' . $translationBase . '.' . $name) : $name];
                }
            }
        }
        return $options;
    }
}

if (!function_exists('mkdirIfNotExists')) {

    /**
     * Creates dir if do not exists.
     *
     * @param $directory - It should be an absolute path. Try using base_path()
     *
     * @return void
     */
    function mkdirIfNotExists($directory): void
    {
        if (file_exists($directory)) {
            return;
        }

        mkdir($directory, 0777, true);
    }
}

if (!function_exists('currentLocale')) {

    /**
     * Get current locale
     *
     * @return string
     */
    function currentLocale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('clearString')) {

    /**
     * Clear string from new lines and multiple spaces
     *
     * @param $string
     * @return string
     */
    function clearString($string): string
    {
        // Create an array with the values you want to replace
        $searches = ["\r", "\n", "\r\n"];

        // Replace the line breaks with a space
        $string = str_replace($searches, " ", $string);

        // Replace multiple spaces with one
        return preg_replace('!\s+!', ' ', $string);
    }
}

if (!function_exists('copyFile')) {

    /**
     * Copy only non-existing files.
     * Used to copy files from the previous project.
     *
     * @param $directory_copy
     * @param $directory_paste
     * @param $folder_id
     *
     * @return array
     */
    function copyFiles($directory_copy, $directory_paste, $folder_id): array
    {
        $copied_files = [];

        if (!file_exists($directory_copy)) {
            return [];
        }

        $folders = array_filter(array_map('basename', scandir($directory_copy)), function ($file) use ($directory_copy) {
            return is_dir($directory_copy . DIRECTORY_SEPARATOR . $file);
        });

        /**
         * Ex: array:3 [
         *  0 => "."
         *  1 => ".."
         *  2 => "DLFE-7702.pdf"
         * ]
         */
        if (count($folders) < 3) {
            return [];
        }

        for ($i = 2; $i < count($folders); $i++) {
            $sub_dir = $directory_copy . DIRECTORY_SEPARATOR . $folders[$i];
            $file_info = explode('.', $folders[$i]);

            if (count($file_info) !== 2) {
                continue;
            }

            $file_name = $file_info[0];
            $file_extension = $file_info[1];

            $files = array_filter(scandir($sub_dir), function ($file) use ($sub_dir) {
                return !is_dir($sub_dir . '/' . $file);
            });
            $files = array_values($files);

            foreach ($files as $file) {
                $source = $sub_dir . DIRECTORY_SEPARATOR . $file;
                $to = $directory_paste . DIRECTORY_SEPARATOR . $file_name . '.' . $file_extension;

                if (file_exists($to)) {
                    continue;
                }

                if (copy($source, $to)) {
                    $temp = [];
                    $temp['filename'] = basename($to);
                    $temp['content_type'] = mime_content_type($to);
                    $temp['path'] = explode(DIRECTORY_SEPARATOR . 'files', $to)[1];
                    $temp['version'] = $file;
                    $copied_files[] = $temp;
                }
            }
        }

        return $copied_files;
    }

}

if (!function_exists('canPreview')) {

    /**
     * Check if string is a json format.
     * @param $file
     * @return bool
     */
    function canPreview($file): bool
    {
        $can = false;
        switch ($file->content_type) {
            case 'application/pdf':
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $can = true;
                break;
        }

        return $can;
    }
}

if (!function_exists('getOldFileInformation')) {

    /**
     * Get information for an old file from the old db.
     * It's using the lportal.dlfileentry table.
     *
     * @param       $search_name - The file name.
     * @param array $files       - Array of file objects.
     *
     * @return mixed|null
     */
    function getOldFileInformation($search_name, array $files): object|null
    {
        foreach ($files as $file) {
            if ($file->name == $search_name) {
                return $file;
            }
        }

        return null;
    }
}

if (!function_exists('dateBetween')) {

    /**
     * Determine if date pased by parameter or NOW is between specified dates
     */

    function dateBetween($from, $to, $date = null): bool
    {
        $date = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $from = Carbon::parse($from)->format('Y-m-d');
        $to = Carbon::parse($to)->format('Y-m-d');

        return $date >= $from && $date <= $to;
    }
}

if (!function_exists('dateAfter')) {

    /**
     * Determine if date pased by parameter or NOW is after specified date
     */

    function dateAfter($afterDate, $date = null): bool
    {
        $date = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $afterDate = Carbon::parse($afterDate)->format('Y-m-d');

        return $date < $afterDate;
    }
}

if (!function_exists('dateBefore')) {

    /**
     * Determine if date pased by parameter or NOW is before specified date
     */

    function dateBefore($beforeDate, $date = null): bool
    {
        $date = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $beforeDate = Carbon::parse($beforeDate)->format('Y-m-d');

        return $date > $beforeDate;
    }
}

if (!function_exists('addUrlParams')) {

    /**
     * DO NOT CHANGE. Using in profile subscriptions
     */

    function addUrlParams($params): string
    {
        $strParams = '';

        foreach ($params as $key => $value) {
            if(is_array($value)){
                foreach ($value as $v){
                    $strParams .= (empty($strParams) ? '?' : '&') .$key.'[]='.$v;
                }
            } else{
                $strParams .= (empty($strParams) ? '?' : '&') .$key.'='.$value;
            }
        }

        return $strParams;
    }
}


if (!function_exists('clearAfterStripTag')) {
    function clearAfterStripTag($string): string
    {
        $string = str_replace(['&amp;nbsp;', '&nbsp;', '&amp;'], ' ', $string);

        return trim($string);
    }
}

if (!function_exists('transliterate_new')) {

    /**
     * Transliterate given string from cyrillic to latin letters and revers
     *
     * @method transliterate_new
     * @param string $str
     * @param bool $reverse
     * @return string
     */
    function transliterate_new(string $str, bool $reverse = false)
    {
        $cyr = array('ч', 'Ч', 'щ', 'Щ', 'ш', 'Ш',
            'ц', 'Ц', 'ц', 'Ц',
            'ю', 'Ю', 'ю', 'Ю',
            'я', 'Я', 'я', 'Я',
            'а', 'А', 'б', 'Б', 'в', 'В', 'в', 'В',
            'г', 'Г', 'д', 'Д', 'е', 'Е',
            'ж', 'Ж', 'ж', 'Ж', 'з', 'З',
            'и', 'И',
            //'й', 'Й',
            'к', 'К', 'л', 'Л', 'м', 'М', 'н', 'Н',
            'о', 'О', 'п', 'П', 'р', 'Р', 'с', 'С',
            'т', 'Т', 'у', 'У', 'ф', 'Ф', 'х', 'Х',
            'ъ', 'Ъ',
            //'ь',
        );

        $lat = array('ch', 'CH', 'sht', 'SHT', 'sh', 'SH',
            'c', 'C', 'ts', 'TS',
            'iu', 'IU', 'yu', 'YU',
            'q', 'Q', 'ya', 'YA',
            'a', 'A', 'b', 'B', 'v', 'V', 'w', 'W',
            'g', 'G', 'd', 'D', 'e', 'E',
            'zh', 'ZH', 'j', 'J', 'z', 'Z',
            'i', 'I',
            //'y', 'Y',
            'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N',
            'o', 'O', 'p', 'P', 'r', 'R', 's', 'S',
            't', 'T', 'u', 'U', 'f', 'F', 'h', 'H',
            'y', 'Y',
            //'',
        );

        return $reverse ? str_replace($lat, $cyr, $str) : str_replace($cyr, $lat, $str);
    }
}

if (!function_exists('fileThumbnail')) {
    function fileThumbnail($file)
    {
        $thumbnail = '';
        if(in_array($file->content_type, ['image/png', 'image/jpeg', 'image/gif', 'image/apng', 'image/avif', 'image/webp'])){
            $thumbnail = '<div class="col-md-4 mb-3"><img class="img-thumbnail preview-file-modal" role="button" data-file="'.$file->id.'" data-url="'.route('modal.file_preview', ['id' => $file->id]).'" src="'.asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $file->path)).'"></div>';
        }

        return $thumbnail;
    }
}

if (!function_exists('getNamesByFullName')) {

    /**
     * @method getNamesByFullName
     * @param string $fullName
     * @return array
     */
    function getNamesByFullName(string $fullName, $transliterate = true): array
    {
        $name_expl = explode(" ", $transliterate ? transliterate($fullName) : $fullName);

        $names = [
            'first_name' => $name_expl[0] ?? null,
            'middle_name' => null,
            'last_name' => $name_expl[1] ?? null,
        ];

        if (count($name_expl) > 2) {
            $names = [
                'first_name' => $name_expl[0] ?? null,
                'middle_name' => $name_expl[1] ?? null,
                'last_name' => $name_expl[2] ?? null,
            ];
        }

        return $names;
    }
}

if (!function_exists('transliterate')) {

    /**
     * Transliterate a given string from latin to cyrillic
     *
     * @method transliterate
     * @param $str
     * @return string
     */
    function transliterate($str)
    {
        $lat = [
            'yu', 'ya', 'ts', 'ch', 'sh', 'sht', 'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'a', 'i', 'y', 'e',
            'YU', 'YA', 'TS', 'CH', 'SH', 'SHT', 'A', 'B', 'V', 'G', 'D', 'E', 'IO', 'ZH', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'A', 'I', 'Y', 'e'
        ];
        $cyr = [
            'ю', 'я', 'ц', 'ч', 'ш', 'щ', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'ь', 'э',
            'Ю', 'Я', 'Ц', 'Ч', 'Ш', 'Щ', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ъ', 'Ы', 'Ь', 'Э'
        ];

        return str_replace($lat, $cyr, $str);
    }
}

if (!function_exists('groupAdvisoryBoardItems')) {

    /**
     * Used to group advisory board items in site listing.
     *
     * @param $groups
     * @param $item
     * @param $type
     * @param $id_field
     * @param $relation
     *
     * @return void
     */
    function groupItems($groups, $item, $type, $id_field, $relation) {
        $found_group = $groups->where('id', $item->$id_field)->first();

        if (!$found_group) {
            $groups->push([
                'group_type' => $type,
                'id' => $item->$id_field,
                'name' => $item->$relation->name,
                'items' => collect([$item]),
            ]);
        } else {
            $found_group['items']->push($item);
        }
    }
}

if (!function_exists('generateImageThumbnail')) {
//    function generateImageThumbnail(\App\Models\File $file, $type = 'list')
//    {
//        $source_image_path = Storage::disk('public_uploads')->path($file->path);
//        $maxWidth = $type == 'list' ? env('THUMBNAIL_LIST_IMAGE_MAX_WIDTH', 250) : env('THUMBNAIL_LIST_IMAGE_MAX_WIDTH', 446);
//        $maxHeight = $type == 'list' ? env('THUMBNAIL_LIST_SMALL_IMAGE_MAX_HEIGHT', 190) : env('THUMBNAIL_HOME_PAGE_IMAGE_MAX_HEIGHT', 200);
//        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
//        switch ($source_image_type) {
//            case IMAGETYPE_JPEG:
//                $source_gd_image = imagecreatefromjpeg($source_image_path);
//                $thumbnail_image_path_list = Storage::disk('public_uploads')->path('thumbnails/'.$file->id.'/list.jpg');
//                $thumbnail_image_path_home_page = Storage::disk('public_uploads')->path('thumbnails/'.$file->id.'/home_page.jpg');
//                break;
//            case IMAGETYPE_PNG:
//                $source_gd_image = imagecreatefrompng($source_image_path);
//                $thumbnail_image_path_list = Storage::disk('public_uploads')->path('thumbnails/'.$file->id.'/list.png');
//                $thumbnail_image_path_home_page = Storage::disk('public_uploads')->path('thumbnails/'.$file->id.'/home_page.png');
//                break;
//        }
//        if ($source_gd_image === false) {
//            return false;
//        }
//        $source_aspect_ratio = $source_image_width / $source_image_height;
//        $thumbnail_aspect_ratio = $maxWidth / $maxHeight;
//        if ($source_image_width <= $maxWidth && $source_image_height <= $maxHeight) {
//            $thumbnail_image_width = $source_image_width;
//            $thumbnail_image_height = $source_image_height;
//        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
//            $thumbnail_image_width = (int) ($maxHeight * $source_aspect_ratio);
//            $thumbnail_image_height = $maxHeight;
//        } else {
//            $thumbnail_image_width = $maxWidth;
//            $thumbnail_image_height = (int) ($maxWidth / $source_aspect_ratio);
//        }
//        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
//        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
//
//        $img_disp = imagecreatetruecolor($maxWidth,$maxWidth);
//        $backcolor = imagecolorallocate($img_disp,0,0,0);
//        imagefill($img_disp,0,0,$backcolor);
//
//        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp)/2)-(imagesx($thumbnail_gd_image)/2), (imagesy($img_disp)/2)-(imagesy($thumbnail_gd_image)/2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));
//
//        imagejpeg($img_disp, $thumbnail_image_path, 90);
//        imagedestroy($source_gd_image);
//        imagedestroy($thumbnail_gd_image);
//        imagedestroy($img_disp);
//        return true;
//    }


    /**
     * https://image.intervention.io/v3
     * @param \App\Models\File $file
     * @return void
     */
    function generateImageThumbnail(\App\Models\File $file): void
    {
        $destinationPathThumbnail = Storage::disk('public_uploads')->path('thumbnails');
        mkdirIfNotExists($destinationPathThumbnail);
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

        $thumbs = array(
            'list_publication' => [
                'width' => \App\Models\File::THUMBNAIL_LIST_PUBLICATION_IMAGE_MAX_WIDTH,
                'height' => \App\Models\File::THUMBNAIL_LIST_PUBLICATION_SMALL_IMAGE_MAX_HEIGHT,
            ],
            'list_news' => [
                'width' => \App\Models\File::THUMBNAIL_LIST_NEWS_IMAGE_MAX_WIDTH,
                'height' => \App\Models\File::THUMBNAIL_LIST_NEWS_SMALL_IMAGE_MAX_HEIGHT,
            ],
            'home' => [
                'width' => \App\Models\File::THUMBNAIL_HOME_PAGE_IMAGE_MAX_WIDTH ,
                'height' => \App\Models\File::THUMBNAIL_HOME_PAGE_IMAGE_MAX_HEIGHT ,
            ]
        );
        foreach ($thumbs as $type => $options){
            $image = $manager->read(Storage::disk('public_uploads')->path($file->path));
            $image->cover($options['width'], $options['height'])
                ->toJpeg()
                ->save($destinationPathThumbnail.DIRECTORY_SEPARATOR.$file->id.'_thumbnail_'.$type.'.jpg');
        }
    }
}
