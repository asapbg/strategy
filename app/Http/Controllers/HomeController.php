<?php

namespace App\Http\Controllers;

use App\Enums\PublicationTypesEnum;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\LegislativeInitiative;
use App\Models\Publication;
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

        $publications = Publication::select('publication.*')
            ->whereActive(true)
            ->with(['translation','mainImg','category.translation'])
            ->joinTranslation(Publication::class)
            ->whereLocale(currentLocale())
            //->whereType(PublicationTypesEnum::TYPE_NEWS)
            ->whereDate('created_at', '<=', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')
            ->paginate(3);

        $default_img = "files".DIRECTORY_SEPARATOR.File::PUBLICATION_UPLOAD_DIR."news-default.jpg";

        //dd($publications->toArray());
        return $this->view('site.home.index',
            compact('consultations','initiatives', 'publications','default_img'));
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
        $initiatives = LegislativeInitiative::select('legislative_initiative.*')->with(['comments','likes'])
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_translations', function ($q){
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', app()->getLocale());
            })
            ->when(!empty($keywords), function ($query) use ($keywords){
                $query->where('law_translations.name', 'ilike', '%' . $keywords . '%');
                $query->orWhere('legislative_initiative.description', 'ilike', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                    });
            })
            ->whereNull('law.deleted_at')
            ->where('law.active', '=', true)
//            ->whereStatus(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE)
            ->orderBy('legislative_initiative.created_at', 'DESC')
            ->groupBy('legislative_initiative.id')
            ->paginate($paginate);

        if ($is_search) {
            return $this->view('site.home.initiatives', compact('initiatives'));
        }

        return $initiatives;
    }
}
