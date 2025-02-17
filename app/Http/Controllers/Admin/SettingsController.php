<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsStoreRequest;
use App\Library\Facebook;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index($section = 'system_notifications'): \Illuminate\View\View
    {
        $settings = Setting::Editable()->orderBy('id')->where('section', '=', $section)->get();
        $sections = Setting::Editable()->orderBy('id')->get()->unique('section')->pluck('section')->toArray();
        $disabledSettings = null;
        if ($section == Setting::FACEBOOK_SECTION) {
            $disabledSettings = Setting::NotEditable()->orderBy('id')->where('section', '=', $section)->get();
        }
        return $this->view('admin.settings.index', compact('settings', 'section', 'sections', 'disabledSettings'));
    }

    public function store(SettingsStoreRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $section = $validated['section'];
        unset($validated['section']);

        foreach ($validated as $name => $value) {
            $setting = Setting::Editable()->where('name', '=', $name)->first();
            if ($request->user()->cannot('update', $setting)) {
                abort(Response::HTTP_FORBIDDEN);
            }
            $setting->value = $value;
            $setting->update();

            if ($name == Setting::CONTACT_MAIL_KEY) {
                Cache::forget(Setting::CONTACT_MAIL_KEY);
            }

            if ($name == Setting::FACEBOOK_APP_ID) {
                Cache::forget(Setting::FACEBOOK_APP_ID);
            }
        }
        return redirect(route('admin.settings', ['section' => $section]))->with('success', __('custom.settings') . ' ' . __('messages.updated_successfully_pl'));
    }

    public function initFacebook(Request $request)
    {
        if ($request->user()->cannot('facebookInit', Setting::class)) {
            if ($request->ajax()) {
                return response()->json(['error' => 1, 'msg' => __('messages.unauthorized')], 200);
            } else {
                return back()->with('warning', __('messages.unauthorized'));
            }
        }
        $data = array();
        $facebookApi = new Facebook();
        //get and save userLongLiveToken
        $userLongLiveToken = $facebookApi->getUserLongLivedToken();
        if (!isset($userLongLiveToken['error'])) {
            Setting::where('name', '=', Setting::FACEBOOK_USER_LONG_LIVE_TOKEN)
                ->where('section', '=', Setting::FACEBOOK_SECTION)
                ->update(['value' => $userLongLiveToken['access_token']]);
            $data[Setting::FACEBOOK_USER_LONG_LIVE_TOKEN] = $userLongLiveToken['access_token'];
        } else {
            if ($request->ajax()) {
                return response()->json(['error' => 1, 'msg' => 'Неуспешен опит за взимане на Потребител (Long-Lived Token): ' . $userLongLiveToken['message']], 200);
            } else {
                return back()->with('danger', 'Неуспешен опит за взимане на Потребител (Long-Lived Token)');
            }
        }
        //get and save pageLongLiveToken
        $facebookApi->initTokens();
        $pageLongLiveToken = $facebookApi->getPageLongLivedToken();
        if (!isset($pageLongLiveToken['error'])) {
            Setting::where('name', '=', Setting::FACEBOOK_PAGE_LONG_LIVE_TOKEN)
                ->where('section', '=', Setting::FACEBOOK_SECTION)
                ->update(['value' => $pageLongLiveToken['access_token']]);
            $data[Setting::FACEBOOK_PAGE_LONG_LIVE_TOKEN] = $pageLongLiveToken['access_token'];
        } else {
            if ($request->ajax()) {
                return response()->json(['error' => 1, 'msg' => 'Неуспешен опит за взимане на Страница (Long-Lived Token за достъп): ' . $pageLongLiveToken['response']['error']['message']], 200);
            } else {
                return back()->with('danger', 'Неуспешен опит за взимане на Страница (Long-Lived Token за достъп)');
            }
        }

        if ($request->ajax()) {
            return response()->json(['tokens' => $data, 'msg' => __('Данните са обновени успешно')], 200);
        } else {
            return redirect(route('admin.settings', ['section' => Setting::FACEBOOK_SECTION]))->with('success', 'Данните са обновени успешно');
        }
    }

    /**
     * Start the command sync:institution-name-changes with the last
     * sync date as argument, so it will sync the institution names from that date till now
     *
     * @return JsonResponse
     */
    public function syncInstitutions()
    {
        $last_sync_date = request('last_sync_date');
        if (!$last_sync_date) {
            return response()->json(['success' => false, 'msg' => "Липсва дата на последна синхронизация"], 200);
        }
        Artisan::call('sync:institution-name-changes', ['date' => $last_sync_date]);
        return response()->json(['success' => true, 'msg' => __('custom.sync_started')], 200);
    }
}
