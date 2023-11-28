<?php

namespace App\Http\Controllers;

use App\Models\ActType;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends Controller
{
    public function index(Request $request, $category = '')
    {
        //Filter
        $requestFilter = $request->all();
        if( isset($requestFilter['legalАctТype']) && !empty($requestFilter['legalАctТype']) && !empty($category)) {
            $actType = LegalActType::find((int)$requestFilter['legalАctТype']);
            if( $actType && Str::slug($actType->name) != $category ){
                return redirect(route('pris.index', $request->query()));
            }
        }
        $filter = $this->filters($request);
        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'created_at';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Pris::PAGINATE;

        $items = Pris::select('pris.*')
            ->Published()
            ->with(['translations', 'actType', 'actType.translations', 'institution', 'institution.translations'])
            ->join('institution', 'institution.id', '=', 'pris.institution_id')
            ->join('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)->paginate($paginate);
        $pageTitle = __('site.menu.pris');

        $menuCategories = [];
        $actTypes = LegalActType::where('id', '<>', LegalActType::TYPE_ORDER)->get();
        if( $actTypes->count() ) {
            foreach ($actTypes as $act) {
                $menuCategories[] = [
                    'label' => $act->name,
                    'url' => route('pris.category', ['category' => Str::slug($act->name)]).'?legalАctТype='.$act->id,
                    'slug' => Str::slug($act->name)
                ];
            }
        }
        return $this->view('site.pris.index', compact('filter','sorter', 'items', 'pageTitle', 'menuCategories'));
    }

    public function show(Request $request, int $id = 0)
    {
        $item = Pris::Published()->with(['translation', 'actType', 'actType.translation', 'institution', 'institution.translation',
            'tags', 'tags.translation', 'changedDocs',
            'changedDocs.actType', 'changedDocs.actType.translation',
            'changedDocs.institution', 'changedDocs.institution.translation', 'files'])->find($id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $pageTitle = $item->actType->name.' '.__('custom.number_symbol').' '.$item->actType->doc_num.' '.__('custom.of').' '.$item->institution->name.' от '.$item->docYear.' '.__('site.year_short');
        $this->setBreadcrumbsTitle($pageTitle);
        return $this->view('site.pris.view', compact('item', 'pageTitle'));
    }

    private function sorters()
    {
        return array(
            'category' => ['class' => 'col-md-3', 'label' => trans_choice('custom.category', 1)],
            'importer' => ['class' => 'col-md-3', 'label' => trans_choice('custom.institutions', 1)],
            'docDate' => ['class' => 'col-md-3', 'label' => __('custom.date')],
            'docNum' => ['class' => 'col-md-3', 'label' => __('custom.number')],
        );
    }

    private function filters($request)
    {
        return array(
            'legalАctТype' => array(
                'type' => 'select',
                'options' => optionsFromModel(LegalActType::Pris()->get(), true),
                'multiple' => false,
                'default' => '',
                'label' => trans_choice('custom.legal_act_types', 1),
                'value' => $request->input('legalАctТype'),
                'col' => 'col-md-12'
            ),
            'filesContent' => array(
                'type' => 'text',
                'label' => __('custom.content'),
                'value' => $request->input('filesContent'),
                'col' => 'col-md-3'
            ),
            'about' => array(
                'type' => 'text',
                'label' => __('custom.pris_about'),
                'value' => $request->input('about'),
                'col' => 'col-md-3'
            ),
            'legalReason' => array(
                'type' => 'text',
                'label' => __('custom.pris_legal_reason'),
                'value' => $request->input('legalReason'),
                'col' => 'col-md-3'
            ),
            'tag' => array(
                'type' => 'select',
                'options' => optionsFromModel(Tag::get()),
                'multiple' => false,
                'default' => '',
                'label' => trans_choice('custom.tags', 2),
                'value' => $request->input('tag'),
                'col' => 'col-md-3'
            ),
            'institution' => array(
                'type' => 'subjects',
                'label' => trans_choice('custom.institutions', 1),
                'multiple' => false,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', trans_choice('custom.institutions', 1)),
                'value' => request()->input('institution'),
                'default' => '',
                'col' => 'col-md-3'
            ),
            'fromDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('fromDate'),
                'label' => __('custom.begin_date'),
                'col' => 'col-md-3'
            ),
            'toDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('toDate'),
                'label' => __('custom.end_date'),
                'col' => 'col-md-3'
            ),
            'docNum' => array(
                'type' => 'text',
                'label' => __('custom.document_number'),
                'value' => $request->input('docNum'),
                'col' => 'col-md-3'
            ),
            'newspaperNumber' => array(
                'type' => 'text',
                'label' => __('custom.newspaper_number'),
                'value' => $request->input('newspaperNumber'),
                'col' => 'col-md-3'
            ),
            'newspaperYear' => array(
                'type' => 'text',
                'label' => __('custom.newspaper_year'),
                'value' => $request->input('newspaperYear'),
                'col' => 'col-md-3'
            ),
            'changes' => array(
                'type' => 'text',
                'label' => __('custom.change_docs'),
                'value' => $request->input('changes'),
                'col' => 'col-md-3'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Pris::PAGINATE,
                'col' => 'col-md-3'
            ),

        );
    }
}
