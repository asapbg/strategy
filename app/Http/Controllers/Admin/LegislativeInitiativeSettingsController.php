<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsStoreRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class LegislativeInitiativeSettingsController extends Controller
{
    public function edit(Request $request, $section = Setting::OGP_LEGISLATIVE_INIT_SECTION){

        if(!$request->user()->canAny('manage.*', 'manage.legislative_initiatives')){
            return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        $availableSettings = [Setting::OGP_LEGISLATIVE_INIT_REQUIRED_LIKES];
        $settings = null;
        if(sizeof($availableSettings)){
            $settings = Setting::Editable()->orderBy('id')
                ->whereIn('name', $availableSettings)
                ->where('section', '=', Setting::OGP_LEGISLATIVE_INIT_SECTION)
                ->get();
        }

        $sections = [Setting::OGP_LEGISLATIVE_INIT_SECTION];

        return $this->view('admin.legislative_initiatives.settings', compact('settings','sections', 'section' ));

    }

    public function store(SettingsStoreRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $section = $validated['section'];
        unset($validated['section']);

        foreach ($validated as $name => $value) {
            $setting = Setting::Editable()->where('name', '=', $name)->first();
            if( !$request->user()->canAny('manage.*', 'manage.legislative_initiatives') ) {
                return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
            }
            $setting->value = $value;
            $setting->update();
        }
        return redirect(route('admin.legislative_initiatives.settings'))->with('success', __('custom.settings').' '.__('messages.updated_successfully_pl'));
    }
}
