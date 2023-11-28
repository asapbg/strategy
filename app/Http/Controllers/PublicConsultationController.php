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
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        //Filter
        $filter = $this->filters($request);

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'created_at';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
//dd($sort, $sortOrd);
        $paginate = $requestFilter['paginate'] ?? PublicConsultation::PAGINATE;
        $pk = PublicConsultation::select('public_consultation.*')
            ->Active()
            ->with(['translation'])
            ->join('public_consultation_translations', function ($j){
                $j->on('public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
                    ->where('public_consultation_translations.locale', '=', app()->getLocale());
            })
            ->join('act_type', 'act_type.id', '=', 'public_consultation.act_type_id')
            ->join('act_type_translations', function ($j){
                $j->on('act_type_translations.act_type_id', '=', 'act_type.id')
                    ->where('act_type_translations.locale', '=', app()->getLocale());
            })
            ->join('field_of_actions', 'field_of_actions.id', '=', 'public_consultation.field_of_actions_id')
            ->join('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            ->paginate($paginate);
        $pageTitle = __('site.menu.public_consultation');
        return $this->view('site.public_consultations.index', compact('filter', 'sorter', 'pk', 'pageTitle'));
    }

    public function show(Request $request, int $id = 0)
    {
//        return $this->view('templates.public_consultations_view');
        $item = PublicConsultation::Active()->with(['translation', 'actType', 'actType.translation', 'contactPersons',
            'pollsInPeriod', 'pollsInPeriod.questions', 'pollsInPeriod.questions.answers', 'importerInstitution', 'importerInstitution.links',
            'importerInstitution.links.translations', 'fieldOfAction', 'fieldOfAction.translation'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->title;
        $this->setBreadcrumbsTitle($pageTitle);
        $documents = $item->lastDocumentsByLocaleAndSection(true);
        $timeline = $item->orderTimeline();
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

    private function sorters()
    {
        return array(
            'regNum' => ['class' => 'col-md-2', 'label' => __('custom.number')],
            'actType' => ['class' => 'col-md-3', 'label' => __('site.public_consultation.type_consultation')],
            'fieldOfAction' => ['class' => 'col-md-3', 'label' => trans_choice('custom.field_of_actions', 1)],
            'title' => ['class' => 'col-md-2', 'label' => __('custom.title')],
            'date' => ['class' => 'col-md-2', 'label' => __('custom.date')],
        );
    }

    private function filters($request)
    {
        return array(
            'name' => array(
                'type' => 'text',
                'label' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            ),
            'consultationNumber' => array(
                'type' => 'text',
                'label' => __('custom.consultation_number_'),
                'value' => $request->input('consultationNumber'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? PublicConsultation::PAGINATE,
                'col' => 'col-md-3'
            ),
        );
    }
}
