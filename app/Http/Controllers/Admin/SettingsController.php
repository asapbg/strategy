<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsStoreRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index($section = 'system_notifications'): \Illuminate\View\View
    {
        $settings = Setting::Editable()->orderBy('id')->where('section', '=', $section)->get();
        $sections = Setting::Editable()->orderBy('id')->get()->unique('section')->pluck('section')->toArray();
        return $this->view('admin.settings.index', compact('settings', 'section', 'sections'));
    }

    public function store(SettingsStoreRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $section = $validated['section'];
        unset($validated['section']);

        foreach ($validated as $name => $value) {
            $setting = Setting::Editable()->where('name', '=', $name)->first();
            if( $request->user()->cannot('update', $setting) ) {
                abort(Response::HTTP_FORBIDDEN);
            }
            $setting->value = $value;
            $setting->update();

            if($name == Setting::CONTACT_MAIL_KEY) {
                Cache::forget(Setting::CONTACT_MAIL_KEY);
            }
        }
        return redirect(route('admin.settings', ['section' => $section]))->with('success', __('custom.settings').' '.__('messages.updated_successfully_pl'));
    }
}
