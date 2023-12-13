<?php

namespace App\Http\Controllers;

use App\Models\Consultations\OperationalProgramRow;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegislativeInitiative;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\LegislativeInitiativeStatusesEnum;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $consultations = $this->getConsultations(new Request());
        $initiatives = $this->getInitiatives(new Request());

        //dd($consultations->toArray());
        return $this->view('site.home.index', compact('consultations','initiatives'));
    }

    /**
     * @params Request $request
     * @return mixed
     */
    public function getConsultations(Request $request)
    {
        $paginate = 4;
        $is_search = $request->has('search');
        $title = $request->offsetGet('pc_search_title');
        $consultations = PublicConsultation::select('public_consultation.*')
            ->ActivePublic()
            ->where('public_consultation_translations.locale', app()->getLocale())
            ->with(['translation', 'fieldOfAction.translation'])
            ->with('comments:object_id')
            ->jointranslation(PublicConsultation::class)
            ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
            ->join('field_of_action_translations', function ($q) {
                $q->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->when($title, function ($query, $title) {
                return $query->where('title', 'ILIKE', "%$title%");
            })
            ->orderBy('public_consultation.created_at', 'DESC')
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.home.consultations', compact('consultations'));
        }

        return $consultations;
    }

    /**
     * @params Request $request
     * @return mixed
     */
    public function getInitiatives(Request $request)
    {
        $paginate = 4;
        $is_search = $request->has('search');
        $keywords = $request->offsetGet('keywords');
        $initiatives = LegislativeInitiative::with(['comments:legislative_initiative_id','likes','operationalProgramTitle'])
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->whereHas('operationalProgram', function ($query) use ($keywords) {
                    $operational_program_ids = OperationalProgramRow::select('operational_program_id')->where('value', 'ilike', "%$keywords%")->pluck('operational_program_id');

                    $query->whereIn('operational_program_id', $operational_program_ids);
                })
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhereHas('user', function ($query) use ($keywords) {
                    $query->where('first_name', 'like', '%' . $keywords . '%');
                    $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                    $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                });
            })
            ->whereStatus(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE)
            ->orderBy('created_at', 'DESC')
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.home.initiatives', compact('initiatives'));
        }

        return $initiatives;
    }
}
