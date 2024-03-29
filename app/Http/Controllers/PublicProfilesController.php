<?php

namespace App\Http\Controllers;

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
        return $this->view('site.public_profiles.institution.pc', compact('item', 'pageTitle'));
    }

//    public function institutionStrategicDocuments(Request $request, Institution $item){
//        return $this->view('site.public_profiles.institution', compact('item'));
//    }

    public function institutionLegislativeInitiatives(Request $request, Institution $item){
        return $this->view('site.public_profiles.institution', compact('item'));
    }

    public function institutionPris(Request $request, Institution $item){
        return $this->view('site.public_profiles.institution', compact('item'));
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
    public function userProfile(Request $request, User $item){

        $pageTitle = trans_choice('custom.profiles', 1).' '.__('custom.of').' '.$item->fullName();
        $this->setBreadcrumbsFull(
            array(
                ['name' => $pageTitle, 'url' => route('user.profile', $item)],
                ['name' => __('site.base_info'), 'url' => '']
            )
        );
        return $this->view('site.public_profiles.user', compact('item', 'pageTitle'));
    }
}
