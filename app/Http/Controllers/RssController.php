<?php

namespace App\Http\Controllers;

use App\Enums\CalcTypesEnum;
use App\Enums\OgpStatusEnum;
use App\Enums\PageModulesEnum;
use App\Enums\PollStatusEnum;
use App\Enums\PublicationTypesEnum;
use App\Http\Requests\SendMessageRequest;
use App\Mail\ContactFormMsg;
use App\Models\AdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\LegalActType;
use App\Models\LegislativeInitiative;
use App\Models\OgpPlan;
use App\Models\Page;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Enums\LegislativeInitiativeStatusesEnum;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RssController extends Controller
{
    function pcItem(Request $request, $id = 0)
    {
        $item = PublicConsultation::with(['responsibleInstitution', 'actType', 'fieldOfAction', 'pris', 'comments'])->find($id);
        return response(view('feed::pc_single',  compact('item')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    function advItem (Request $request, $id = 0)
    {
        $item = AdvisoryBoard::with(['policyArea', 'authority', 'chairmen', 'establishment', 'meetings'])->find($id);
        return response(view('feed::adv_single',  compact('item')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function liItem (Request $request, $id = 0)
    {
        $item = LegislativeInitiative::with(['institutions', 'comments'])->find($id);
        return response(view('feed::li_single',  compact('item')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
