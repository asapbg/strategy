<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsStoreRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class AdvisoryBoardSettingsController extends Controller
{

    public function edit(Request $request, $section = Setting::ADVISORY_BOARDS_SECTION){

        if(!$request->user()->canAny(['manage.*', 'manage.advisory-boards'])){
            return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        $availableSettings = [Setting::AB_REVIEW_PERIOD_NOTIFY];
        $settings = null;
        if(sizeof($availableSettings)){
            $settings = Setting::Editable()->orderBy('id')
                ->whereIn('name', $availableSettings)
                ->where('section', '=', Setting::ADVISORY_BOARDS_SECTION)
                ->get();
        }

        $sections = [Setting::ADVISORY_BOARDS_SECTION];

        return $this->view('admin.advisory-boards.settings', compact('settings','sections', 'section' ));

    }

    public function store(SettingsStoreRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $section = $validated['section'];
        unset($validated['section']);

        foreach ($validated as $name => $value) {
            $setting = Setting::Editable()->where('name', '=', $name)->first();
            if( !$request->user()->canAny(['manage.*', 'manage.advisory-boards']) ) {
                return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
            }
            $setting->value = $value;
            $setting->update();
        }
        return redirect(route('admin.advisory-boards.settings'))->with('success', __('custom.settings').' '.__('messages.updated_successfully_pl'));
    }
}
