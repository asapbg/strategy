<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        }
        else {
            $model = "\App\Models\\".capitalize($guard);
            if (!class_exists($model)) {
                $model = "\App\\".capitalize($guard);
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
     * @return false|string
     */
    function databaseDate($date)
    {
        return date("Y-m-d", strtotime($date));
    }
}

if (!function_exists('databaseDateTime')) {

    /**
     * Return datetime in Y-m-d H:i:s format for storing in database
     *
     * @param $date
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
     * @return false|string
     */
    function displayDate($datetime)
    {
        if (!$datetime) {
            return "";
        }
        return date('d-m-Y', strtotime($datetime));
    }
}

if (!function_exists('displayDateTime')) {

    /**
     * Return date from datetime string in d-m-Y H:i format
     *
     * @param $datetime
     * @return false|string
     */
    function displayDateTime($datetime)
    {
        if (!$datetime) {
            return "";
        }
        return date('d-m-Y H:i', strtotime($datetime));
    }
}

if (!function_exists('printDate')) {

    /**
     * Return date from datetime string in d-MMM-Y format in bg
     *
     * @param $datetime
     * @return false|string
     */
    function printDate()
    {
        $format = new IntlDateFormatter('bg_BG', IntlDateFormatter::NONE,
            IntlDateFormatter::NONE, NULL, NULL, 'd-MMM-Y');
        return datefmt_format($format, mktime('0','0','0'));
    }
}

if (!function_exists('capitalize')) {

    /**
     * Capitalize a given string
     *
     * @param $string
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
     * @param array  $ids
     *
     * @return array
     */
    function rolesNames(array $ids)
    {
        $roles = [];
        if( sizeof($ids) ) {
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
     * @return int
     */
    function getLocaleId(string $code): int
    {
        $id = 1; //by default get first language
        foreach (config('available_languages') as $key => $lang) {
            if( $code == $lang['code'] ) {
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
     * @return array
     */
    function optionsUserTypes(bool $any = false, string|int $anyValue = '', string|int $anyName=''): array
    {
        $options = User::getUserTypes();
        if( $any ) {
            $options[$anyValue] = $anyName;
            ksort($options);
        }
        return $options;
    }

    /**
     * return publication types options
     *
     * @method optionsApplicationStatus
     *
     * @param bool $any
     * @param string|int $anyValue
     * @param string|int $anyName
     * @return array
     */
    function optionsPublicationTypes(bool $any = false, string|int $anyValue = '', string|int $anyName=''): array
    {
        $options = [];
        if( $any ) {
            $options[] = ['value' => $anyValue, 'name' => $anyName];
        }
        foreach (\App\Enums\PublicationTypesEnum::options() as $key => $value) {
            $options[] = ['value' => $value, 'name' => __('custom.public_sections.types.'.$key)];
        }
        return $options;
    }

    if (!function_exists('optionsStatuses')) {

        /**
         * return regular status options
         *
         * @method optionsStatuses
         *
         * @param bool $any
         * @param string|int $anyValue
         * @param string|int $anyName
         * @return array
         */
        function optionsStatuses(bool $any = false, string|int $anyValue = '', string|int $anyName=''): array
        {
            $options = array(
                1 => trans_choice('custom.active', 1),
                0 => trans_choice('custom.inactive', 1),
            );
            if( $any ) {
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
            \Illuminate\Support\Facades\Log::error($method.': '.$error );
        }
    }

    if (!function_exists('stripHtmlTags')) {

        /**
         * return striped html string
         *
         * @param string $html_string
         * @param array $tags
         * @return string
         */
        function stripHtmlTags(string $html_string, array $tags = [])
        {
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
         * @param bool $year
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
}
