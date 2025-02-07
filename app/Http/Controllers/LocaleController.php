<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{

    public function __invoke(Request $request)
    {
        if ($request->has('locale')) {
            $locale = $request->offsetGet('locale');

            $available_language_codes = array_column(config('available_languages'), 'code');

            if (!in_array($locale, $available_language_codes)) {
                $locale = $available_language_codes[array_key_first($available_language_codes)];
            }

            session(['locale' => $locale]);
            app()->setLocale($locale);
        }

        return back();
    }
}
