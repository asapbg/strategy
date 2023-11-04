<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreOperationalProgramRequest;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.operational_programs.index';
    const EDIT_ROUTE = 'admin.consultations.operational_programs.edit';
    const STORE_ROUTE = 'admin.consultations.operational_programs.store';
    const LIST_VIEW = 'admin.consultations.operational_programs.index';
    const EDIT_VIEW = 'admin.consultations.operational_programs.edit';
    const SHOW_VIEW = 'admin.consultations.operational_programs.show';

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;

        $items = OperationalProgram::FilterBy($requestFilter)
            ->paginate($paginate);
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'editRouteName', 'listRouteName'));
    }

    public function show(Request $request, OperationalProgram $item)
    {
        if( $request->user()->cannot('view', $item) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $data = $item->getTableData();
        $columns = $item->id ?
            DynamicStructureColumn::whereIn('id', json_decode($item->active_columns))->get()
            : DynamicStructure::where('type', '=', DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value)->where('active', '=', 1)->first()->columns;
        $listRouteName = self::LIST_ROUTE;
        $months = $item->id ? extractMonths($item->from_date,$item->to_date) : [];
        $assessmentsFiles = $opinionsFiles = [];
        $assessments = $item->assessments->count() ? $item->assessments : [];
        if( !empty($assessments) ) {
            foreach ($assessments as $f) {
                $assessmentsFiles[$f->pivot->row_num.'_'.$f->pivot->row_month] = $f;
            }
        }
        $opinions = $item->opinions->count() ? $item->opinions : [];
        if( !empty($opinions) ) {
            foreach ($opinions as $f) {
                $opinionsFiles[$f->pivot->row_num.'_'.$f->pivot->row_month] = $f;
            }
        }
        return $this->view(self::SHOW_VIEW, compact('item', 'listRouteName', 'data', 'columns', 'months', 'assessmentsFiles', 'opinionsFiles'));
    }

    /**
     * @param Request $request
     * @param OperationalProgram $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, OperationalProgram $item)
    {
        if( ($item && $request->user()->cannot('update', $item))
            || (!$item->id && $request->user()->cannot('create', OperationalProgram::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $data = $item->getTableData();
        $columns = $item->id ?
            DynamicStructureColumn::whereIn('id', json_decode($item->active_columns))->orderBy('id')->get()
            : DynamicStructure::where('type', '=', DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value)->where('active', '=', 1)->first()->columns;
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $months = $item->id ? extractMonths($item->from_date,$item->to_date) : [];
        $assessmentsFiles = $opinionsFiles = [];
        $assessments = $item->assessments->count() ? $item->assessments : [];
        if( !empty($assessments) ) {
            foreach ($assessments as $f) {
                $assessmentsFiles[$f->pivot->row_num.'_'.$f->pivot->row_month] = $f;
            }
        }
        $opinions = $item->opinions->count() ? $item->opinions : [];
        if( !empty($opinions) ) {
            foreach ($opinions as $f) {
                $opinionsFiles[$f->pivot->row_num.'_'.$f->pivot->row_month] = $f;
            }
        }

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'data', 'columns', 'months', 'assessmentsFiles', 'opinionsFiles'));
    }

    public function store(StoreOperationalProgramRequest $request, $item = null)
    {
        $validated = $request->validated();
        $id = (int)$validated['id'];

        if( $request->isMethod('put') ) {
            $item = $this->getRecord($id);
            if( $request->user()->cannot('update', $item) ) {
                abort(Response::HTTP_FORBIDDEN);
            }
        } else {
            if( $request->user()->cannot('create', OperationalProgram::class) ) {
                abort(Response::HTTP_FORBIDDEN);
            }
            $item = new OperationalProgram();
        }

        DB::beginTransaction();
        try {
            if( !$id ) {
                $activeColumns = DynamicStructure::where('type', '=', DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value)
                    ->where('active', '=', 1)
                    ->first()->columns
                    ->pluck('id')
                    ->toArray();
                $item->active_columns = json_encode($activeColumns);
            }

            //update program
            if( isset($validated['save']) ) {
                $item->from_date = databaseDate('01.' . $validated['from_date']);
                $item->to_date = Carbon::parse('01.'.$validated['to_date'])->endOfMonth()->format('Y-m-d');
                $item->save();
            }

            //update program
            if( isset($validated['save']) ) {
                if (isset($validated['col']) && sizeof($validated['col'])) {
                    if (isset($validated['val']) && sizeof($validated['val'])) {
                        if (sizeof($validated['col']) === sizeof($validated['val'])) {
                            foreach ($validated['col'] as $k => $c) {
                                $item->records()->where('id', '=', (int)$c)->update(['value' => $validated['val'][$k]]);
                            }
                        }
                    }
                }
            }

            //Add new row
            if( isset($validated['new_row']) ) {
                if (isset($validated['new_val_col']) && sizeof($validated['new_val_col'])) {
                    if (isset($validated['new_val']) && sizeof($validated['new_val'])) {
                        if (sizeof($validated['new_val_col']) === sizeof($validated['new_val'])) {
                            $rowNums = $item->records->pluck('row_num')->toArray();
                            $rowNums = empty($rowNums) ? 0 : max($rowNums);
                            foreach ($validated['new_val_col'] as $k => $dsColumnId) {
                                $newRows[] = array(
                                    'month' => $validated['month'],
                                    'operational_program_id' => $item->id,
                                    'dynamic_structures_column_id' => $dsColumnId,
                                    'value' => $validated['new_val'][$k],
                                    'row_num' => $rowNums + 1
                                );
                            }
                            OperationalProgramRow::insert($newRows);
                        }
                    }
                }
            }

            //update row files
            if( isset($validated['save']) ) {
                if( $item ) {
                    // Upload File
                    $months = $item->id ? extractMonths($item->from_date,$item->to_date) : [];
                    $rowsNums = $item->id ? $item->records->pluck('row_num')->unique()->toArray() : [];
                    if( sizeof($months) ) {
                        foreach ($months as $m) {
                            foreach ($rowsNums as $rn) {
                                foreach (['assessment', 'opinion'] as $typeFile) {
                                    $searchKey = 'file_'.$typeFile.'_'.$rn.'_'.(str_replace('.', '_',$m));
                                    if( isset($validated[$searchKey]) ) {
                                        $newFile = $validated[$searchKey];
                                        $currentFile = $item->{$typeFile.'s'}()->wherePivot('row_month', $m)->wherePivot('row_num', $rn)->first();
                                        if( $currentFile ) {
                                            //delete current file of this type
                                            $item->{$typeFile.'s'}()->wherePivot('row_month', $m)->wherePivot('row_num', $rn)->detach();
                                            $currentFile->delete();
                                        }
                                        //Add file and attach
                                        $docType = $typeFile == 'assessment' ? DocTypesEnum::PC_IMPACT_EVALUATION : DocTypesEnum::PC_IMPACT_EVALUATION_OPINION;
                                        $file = $this->uploadFile($item, $newFile, File::CODE_OBJ_OPERATIONAL_PROGRAM, $docType, $typeFile == 'assessment' ? __('validation.attributes.assessment') : __('validation.attributes.opinion'));
                                        $item->rowFiles()->attach($file->id ,['row_month' => $m, 'row_num' => $rn]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, $item) )
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function removeRow(Request $request, OperationalProgram $item, int $rowNum)
    {
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        if( $request->user()->cannot('update', $item) ) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $programId = $item->id;
        //TODO delete files also
        $item->records()->where('row_num', '=', $rowNum)->delete();
        return redirect(route(self::EDIT_ROUTE, $programId) )
            ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_f'));
    }

    public function publish(Request $request, OperationalProgram $item)
    {
        if( $request->user()->cannot('publish', $item) ) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $item->public = 1;
            $item->save();
            OperationalProgram::where('id', '<>', $item->id)->update(['public' => 0]);
            DB::commit();
            return redirect(route(self::LIST_ROUTE) )
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            logError('Publish operational program (ID '.$item->id.')', $e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array();
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = OperationalProgram::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $item;
    }
}
