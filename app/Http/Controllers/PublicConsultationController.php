<?php

namespace App\Http\Controllers;

use App\Models\Consultations\PublicConsultation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationController extends Controller
{
    public function index()
    {
        $pk = PublicConsultation::with(['translation'])->get();
        $pageTitle = __('site.menu.public_consultation');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.public_consultations.index', compact('pk', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
        $item = PublicConsultation::with(['translation', 'actType', 'actType.translation', 'contactPersons',
            'polls', 'polls.questions', 'polls.questions.answers'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->title;
        $this->setBreadcrumbsTitle($pageTitle);
        $documents = $item->lastDocumentsByLocaleAndSection();
        return $this->view('site.public_consultations.view', compact('item', 'pageTitle', 'documents'));
    }
}
