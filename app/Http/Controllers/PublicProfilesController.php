<?php

namespace App\Http\Controllers;

use App\Models\Consultations\PublicConsultation;
use App\Models\LegislativeInitiative;
use App\Models\Publication;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use Illuminate\Http\Request;

class PublicProfilesController extends Controller
{
    //Institution
    public function institutionProfile(Request $request, Institution $item){
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => __('custom.general_info'), 'url' => '']
            )
        );
        return $this->view('site.public_profiles.institution', compact('item', 'pageTitle'));
    }

    public function institutionPublicConsultations(Request $request, Institution $item){
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => trans_choice('custom.public_consultations', 2), 'url' => '']
            )
        );
        $publicConsultation = $item->publicConsultation()->orderBy('date', 'created_at')->get();
        return $this->view('site.public_profiles.institution.pc', compact('item', 'pageTitle', 'publicConsultation'));
    }

//    public function institutionStrategicDocuments(Request $request, Institution $item){
//        return $this->view('site.public_profiles.institution', compact('item'));
//    }

    public function institutionLegislativeInitiatives(Request $request, Institution $item){
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => trans_choice('custom.legislative_initiatives', 2), 'url' => '']
            )
        );
        $li = $item->legislativeInitiatives();
        return $this->view('site.public_profiles.institution.li', compact('item', 'li'));
    }

    public function institutionPris(Request $request, Institution $item){
        $paginate = $filter['paginate'] ?? config('app.default_paginate');
        $items = $item->pris()
            ->with(['translations', 'actType', 'actType.translations', 'institutions', 'institutions.translation'])
            ->orderBy('doc_date', 'desc')
            ->paginate($paginate);
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => __('custom.pris'), 'url' => '']
            )
        );
        return $this->view('site.public_profiles.institution.pris', compact('item', 'pageTitle', 'items'));
    }

    public function institutionAdvBoard(Request $request, Institution $item){
        $items = $item->advisoryBoards();
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => trans_choice('custom.advisory_boards', 2), 'url' => '']
            )
        );
        return $this->view('site.public_profiles.institution.adv_board', compact('item', 'pageTitle', 'items'));
    }

    public function institutionModerators(Request $request, Institution $item){
        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->name;
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('institution.profile', $item)],
                ['name' => trans_choice('custom.contacts', 2), 'url' => '']
            )
        );
        return $this->view('site.public_profiles.institution.moderators', compact('item', 'pageTitle'));
    }

    //USer
    public function userPublicConsultation(Request $request, User $item){

        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->fullName();
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => ''],
                ['name' => trans_choice('custom.public_consultations', 2), 'url' => '']
            )
        );

        $pcIds = $item->commentsPc->pluck('object_id')->unique()->toArray();
        $items = PublicConsultation::ActivePublic()->with(['comments' => function ($q) use($item){
            $q->where('user_id', '=', $item->id);
        }])->whereIn('id', $pcIds)->get();

        return $this->view('site.public_profiles.user.pc', compact('item', 'pageTitle', 'items'));
    }
    public function userLegislativeInitiatives(Request $request, User $item){

        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->fullName();
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => ''],
                ['name' => trans_choice('custom.legislative_initiatives', 2), 'url' => '']
            )
        );
        //Author
        $ids = $item->legislativeInitiatives->pluck('id')->toArray();
        $commentsLiIds = $item->legislativeInitiativesComments->pluck('legislative_initiative_id')->toArray();
        if(sizeof($commentsLiIds)){
            $ids = array_merge($ids, $commentsLiIds);
        }
        $votedLiIds = $item->legislativeInitiativesLike->pluck('legislative_initiative_id')->toArray();
        if(sizeof($votedLiIds)){
            $ids = array_merge($ids, $votedLiIds);
        }
        $items = LegislativeInitiative::whereIn('id', array_keys($ids))->get();

        return $this->view('site.public_profiles.user.li', compact('item', 'pageTitle', 'items'));
    }

}
