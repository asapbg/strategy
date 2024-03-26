<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsStoreRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class OgpSettingsController extends Controller
{
    public function edit(Request $request, $section = Setting::OGP_SECTION)
    {
        if(!$request->user()->canAny(['manage.*', 'manage.partnership'])){
            return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        $availableSettings = [Setting::OGP_ADV_BOARD_FORUM, Setting::OGP_FORUM_INFO];
        $settings = null;
        if(sizeof($availableSettings)){
            $settings = Setting::Editable()->orderBy('id')
                ->whereIn('name', $availableSettings)
                ->where('section', '=', Setting::OGP_SECTION)
                ->get();
        }

        $sections = [Setting::OGP_SECTION];

        return $this->view('admin.ogp.settings', compact('settings','sections', 'section' ));

    }

    public function store(SettingsStoreRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $section = $validated['section'];
        unset($validated['section']);

        foreach ($validated as $name => $value) {
            $setting = Setting::Editable()->where('name', '=', $name)->first();
            if( !$request->user()->canAny(['manage.*', 'manage.partnership']) ) {
                return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
            }
            $setting->value = $value;
            $setting->update();
        }
        return redirect(route('admin.ogp.settings'))->with('success', __('custom.settings').' '.__('messages.updated_successfully_pl'));
    }
}
