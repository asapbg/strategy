<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Http\Requests\StoreOperationalProgramRequest;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use App\Models\File;
use App\Models\StrategicDocuments\Institution;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.operational_programs.index';
    const EDIT_ROUTE = 'admin.consultations.operational_programs.edit';
    const STORE_ROUTE = 'admin.consultations.operational_programs.store';
    const LIST_VIEW = 'admin.consultations.operational_programs.index';
    const EDIT_VIEW = 'admin.consultations.operational_programs.edit';
    const SHOW_VIEW = 'admin.consultations.operational_programs.show';
//    const DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID = 12;
//    const DYNAMIC_STRUCTURE_COLUMN_TITLE_ID = 11;

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? OperationalProgram::PAGINATE;

        $items = OperationalProgram::FilterBy($requestFilter)
            ->orderBy('from_date', 'desc')
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
        $columns = DynamicStructureColumn::with(['translations'])->whereIn('id', json_decode($item->active_columns))->orderBy('ord', 'asc')->get();
        $listRouteName = self::LIST_ROUTE;
        $months = $item->id ? extractMonths($item->from_date,$item->to_date) : [];

        $rowFiles = [];
        $rFiles = $item->rowFiles->count() ? $item->rowFiles : [];
        if( !empty($rFiles) ) {
            foreach ($rFiles as $f) {
                if(!isset($rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale])){
                    $rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale] = array();
                }
                $rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale][] = $f;
            }
        }

        $institutions = Institution::simpleOptionsList()->pluck('name', 'id')->toArray();
        return $this->view(self::SHOW_VIEW, compact('item', 'listRouteName', 'data', 'columns',
            'months', 'institutions', 'rowFiles'));
    }

    /**
     * @param Request $request
     * @param OperationalProgram|null $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, OperationalProgram|null $item)
    {
        if( ($item->id && $request->user()->cannot('update', $item)) || (!$item->id && $request->user()->cannot('create', OperationalProgram::class)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $data = $item->getTableData();
        $columns = $item->id ?
            DynamicStructureColumn::with(['translations'])->whereIn('id', json_decode($item->active_columns))->orderBy('ord', 'asc')->get()
            : DynamicStructure::where('type', '=', DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value)->where('active', '=', 1)->first()->columns;
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $months = $item->id ? extractMonths($item->from_date,$item->to_date) : [];

        $rowFiles = [];
        $rFiles = $item->rowFiles->count() ? $item->rowFiles : [];
        if( !empty($rFiles) ) {
            foreach ($rFiles as $f) {
                if(!isset($rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale])){
                    $rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale] = array();
                }
                $rowFiles[$f->pivot->row_num.'_'.$f->pivot->row_month.'_'.$f->locale][] = $f;
            }
        }

        $institutions = optionsFromModel(Institution::simpleOptionsList());
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'data', 'columns',
            'months', 'institutions', 'rowFiles'));
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
                $now = Carbon::now()->format('Y-m-d');
                $item->from_date = databaseDate('01.' . $validated['from_date']);
                $item->to_date = Carbon::parse('01.'.$validated['to_date'])->endOfMonth()->format('Y-m-d');
                $item->actual = (int)($now > $item->from_date) && ($now < $item->to_date);
                $item->save();
            }

            //update program
            if( isset($validated['save']) ) {
                if (isset($validated['col']) && sizeof($validated['col'])) {
                    foreach ($validated['col'] as $rowKey => $colIds) {
                        if (isset($validated['val']) && sizeof($validated['val'])
                            && isset($validated['val'][$rowKey])
                            //&& (sizeof($validated['col'][$rowKey]) === sizeof($validated['val'][$rowKey]))
                        ) {
                            foreach ($colIds as $key => $id) {
                                $oldCol = $item->records()->where('id', '=', (int)$id)->first();
                                if($oldCol) {
                                    if($oldCol->dynamic_structures_column_id != config('lp_op_programs.op_ds_col_institution_id')) {
                                        $oldCol->update(['value' => $validated['val'][$rowKey][$key] ?? null]);
                                    } else{
                                        $oldCol->institutions()->sync($validated['val'][$rowKey][$key] ?? []);
                                    }
                                }
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
                                $newColValue = $dsColumnId == config('lp_op_programs.op_ds_col_institution_id') ? date('my') . substr(uniqid(), 9, 12) :  $validated['new_val'][$k];
                                $dbRecord = new OperationalProgramRow([
                                    'month' => $validated['month'],
                                    'operational_program_id' => $item->id,
                                    'dynamic_structures_column_id' => $dsColumnId,
                                    'value' => $newColValue,
                                    'row_num' => $rowNums + 1
                                ]);
                                $dbRecord->save();
                                if( $dsColumnId == config('lp_op_programs.op_ds_col_institution_id') ) {
                                    $dbRecord->institutions()->sync($validated['new_val'][$k]);
                                }
                            }
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
                                    foreach (config('available_languages') as $lang){
                                        $searchKey = 'file_'.$typeFile.'_'.$rn.'_'.(str_replace('.', '_',$m)).'_'.$lang['code'];
                                        if( isset($validated[$searchKey]) ) {
                                            $newFile = $validated[$searchKey];
                                            $currentFile = $item->{$typeFile.'s'}()->wherePivot('row_month', $m)->wherePivot('row_num', $rn)->where('locale', $lang['code'])->first();
                                            if( $currentFile ) {
                                                //delete current file of this type
                                                $item->rowFiles()->detach($currentFile->id);
                                                $currentFile->delete();
                                            }
                                            //Add file and attach
                                            $docType = $typeFile == 'assessment' ? DocTypesEnum::PC_IMPACT_EVALUATION : DocTypesEnum::PC_IMPACT_EVALUATION_OPINION;
                                            $file = $this->uploadFile($item, $newFile, File::CODE_OBJ_OPERATIONAL_PROGRAM, $docType, ($typeFile == 'assessment' ? __('validation.attributes.assessment') : __('validation.attributes.opinion')), $lang['code']);
                                            $item->rowFiles()->attach($file->id ,['row_month' => $m, 'row_num' => $rn]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //Upload files
            if( isset($validated['save_files']) || isset($validated['stay_in_files']) ) {
                foreach ($request->all() as $k => $v){
                    if(!in_array($k, ['a_file_bg', 'a_file_en', 'a_description_bg', 'a_description_en', 'formats'])){
                        $request->offsetUnset($k);
                    }
                }
                foreach ($request->all() as $k => $v){

                    if(in_array($k, ['a_file_bg', 'a_file_en', 'a_description_bg', 'a_description_en', 'formats'])){
                        $request->request->add([str_replace('a_', '', $k) => $v]);
                        if($k != 'formats'){
                            $request->offsetUnset($k);
                        }
                    }
                }
                $langReq = LanguageFileUploadRequest::createFrom($request);
                $this->uploadFileLanguages($langReq, $item->id, File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL, false);
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

    public function deleteFile(Request $request, OperationalProgram $program, File $file)
    {
        if( !$program || !$file ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $request->user()->cannot('update', $program) ) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $program->rowFiles()->detach($file->id);
            $file->delete();
            DB::commit();
            return redirect(route(self::EDIT_ROUTE, $program) )
                ->with('success', trans_choice('custom.legislative_program', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Legislative program file (fileId '.$file->id.') error: '.$e);
            return back()->with('danger', __('messages.system_error'));
        }
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
            DB::commit();
            return redirect(route(self::LIST_ROUTE) )
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            logError('Publish operational program (ID '.$item->id.')', $e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    public function unPublish(Request $request, OperationalProgram $item)
    {
        if( $request->user()->cannot('unPublish', $item) ) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $item->public = 0;
            $item->save();
            DB::commit();
            return redirect(route(self::LIST_ROUTE) )
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            logError('Publish operational program (ID '.$item->id.')', $e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Delete existing program record
     *
     * @param OperationalProgram $item
     * @return RedirectResponse
     */
    public function destroy(Request $request, OperationalProgram $item)
    {
        if($request->user()->cannot('delete', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        try {
            $item->delete();
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.operational_program', 1)." ".__('messages.deleted_successfully_f'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return redirect(url()->previous())->with('danger', __('messages.system_error'));

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
