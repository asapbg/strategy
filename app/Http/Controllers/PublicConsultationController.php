<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationController extends Controller
{
    public function index()
    {
//        return $this->view('templates.public_consultation_list');
        $pk = PublicConsultation::with(['translation'])->get();
        $pageTitle = __('site.menu.public_consultation');
        return $this->view('site.public_consultations.index', compact('pk', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.public_consultations_view');
        $item = PublicConsultation::with(['translation', 'actType', 'actType.translation', 'contactPersons',
            'pollsInPeriod', 'pollsInPeriod.questions', 'pollsInPeriod.questions.answers', 'timeline', 'timeline.object', 'importerInstitution', 'importerInstitution.links', 'importerInstitution.links.translations'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->title;
        $this->setBreadcrumbsTitle($pageTitle);
        $documents = $item->lastDocumentsByLocaleAndSection();
        $timeline = [];
        if( $item->timeline->count() ) {
            foreach ($item->timeline as $t) {
                if(!isset($timeline[$t->event_id])) { $timeline[$t->event_id] = [];}
                $timeline[$t->event_id][] = $t;
            }
        }

        return $this->view('site.public_consultations.view', compact('item', 'pageTitle', 'documents', 'timeline'));
    }

    public function addComment(StoreCommentRequest $request)
    {
        $validated = $request->validated();
        $pc = PublicConsultation::find($validated['id']);
        if( !$pc->inPeriodBoolean ){
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $pc->comments()->save(new Comments([
                'object_code' => Comments::PC_OBJ_CODE,
                'content' => $validated['content'],
                'user_id' => $request->user() ? $request->user()->id : null,
            ]));
            DB::commit();
            return redirect(route('public_consultation.view', ['id' => $pc->id]) )
                ->with('success', __('site.successful_send_comment'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save comment error: '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
