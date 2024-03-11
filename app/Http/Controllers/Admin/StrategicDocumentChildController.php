<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StrategicDocumentChildStoreRequest;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\File;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\StrategicDocumentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function React\Promise\all;

class StrategicDocumentChildController extends AdminController
{
    public function popupForm(Request $request, StrategicDocument $sd, $doc = null){
        if($doc){
            $doc = StrategicDocumentChildren::find((int)$doc);
        }
        $strategicDocumentTypes = StrategicDocumentType::with('translations')->orderByTranslation('name')->get();
        $legalActTypes = LegalActType::StrategyCategories()->with('translations')->get();
        $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')
            ->where('nomenclature_level_id', '=', $sd->strategic_document_level_id)->get();
//        $policyAreas = null;
//        switch ($sd->strategic_document_level_id){
//            case InstitutionCategoryLevelEnum::CENTRAL->value:
//                $policyAreas = \App\Models\FieldOfAction::Central()->with(['translations'])->orderByTranslation('name')->get();
//                break;
//            case InstitutionCategoryLevelEnum::AREA->value:
//                $policyAreas = \App\Models\FieldOfAction::Area()->with(['translations'])->orderByTranslation('name')->get();
//                break;
//            case InstitutionCategoryLevelEnum::MUNICIPAL->value:
//                $policyAreas = \App\Models\FieldOfAction::Municipal()->with(['translations'])->orderByTranslation('name')->get();
//                break;
//        }

        return $this->view('admin.strategic_documents.popup_form', compact('sd', 'doc', 'strategicDocumentTypes', 'legalActTypes', 'authoritiesAcceptingStrategic'))->render();
    }

    public function create(Request $request){
        $r = new StrategicDocumentChildStoreRequest();
        $validator = Validator::make($request->all(), $r->rules());
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();
        $sd = StrategicDocument::find((int)$validated['sd']);
        if(!$sd){
            return response()->json(['main_error' => __('messages.records_not_found')], 200);
        }

        if($request->user()->cannot('update', $sd)) {
            return response()->json(['main_error' => __('messages.unauthorized')], 200);
        }
        DB::beginTransaction();
        try {
            $validated['document_date_accepted'] = isset($validated['pris_act_id']) ? Pris::find($validated['pris_act_id'])->doc_date : ($validated['document_date_accepted'] ?? Carbon::now());
            $item = new StrategicDocumentChildren();
            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['strategic_document_id'] = $sd->id;
            $fillable['parent_id'] = $validated['doc'] ?? null;
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(StrategicDocumentChildren::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();
            return response()->json(['redirect_url' => isset($validated['doc']) && $validated['doc'] ? url()->previous() : route('admin.strategic_documents.document.edit', $item)]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create strategic document child error:'. $e);
            return response()->json(['main_error' => __('messages.system_error')], 200);
        }
    }

    public function edit(Request $request, $item){
        $item = StrategicDocumentChildren::find((int)$item);
        if(!$item){
            return back()->with('warning', __('messages.record_not_found'));
        }
        if($request->user()->cannot('update', $item->strategicDocument)) {
            return redirect(route('admin.home'))->with('warning', __('messages.unauthorized'));
        }

        $sdDocuments = $item->strategicDocument->documents;
        $documentTree = StrategicDocumentChildren::getTree($item->id);
        $canDeleteSd = $request->user()->can('delete', $item->strategicDocument);
        $strategicDocumentTypes = StrategicDocumentType::with('translations')->orderByTranslation('name')->get();
        $legalActTypes = LegalActType::StrategyCategories()->with('translations')->get();
        $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')->get();
//            ->where('nomenclature_level_id', '=', $item->strategic_document_level_id)->get();
        return $this->view('admin.strategic_documents.documents.edit', compact('item', 'sdDocuments', 'documentTree', 'canDeleteSd',
            'strategicDocumentTypes', 'legalActTypes', 'authoritiesAcceptingStrategic'));
    }

    public function update(Request $request){
        $r = new StrategicDocumentChildStoreRequest();
        $validator = Validator::make($request->all(), $r->rules());
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $r = $validated = $validator->validated();
        $validated['document_date_accepted'] = isset($validated['pris_act_id']) ? Pris::find($validated['pris_act_id'])->doc_date : ($validated['document_date_accepted'] ?? Carbon::now());
        $validated['document_date_expiring'] = isset($validated['date_expiring_indefinite']) ? null : ($validated['document_date_expiring'] ?? Carbon::now());
        $item = StrategicDocumentChildren::find((int)$validated['id']);
        if(!$item){
            return response()->json(['main_error' => __('messages.record_not_found')], 200);
        }

        if($request->user()->cannot('update', $item->strategicDocument)) {
            return response()->json(['main_error' => __('messages.unauthorized')], 200);
        }
        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(StrategicDocumentChildren::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();
            return response()->json(['success' => 1, 'success_message' => trans_choice('custom.strategic_documents.documents', 1).' '.$item->title.' '.__('messages.updated_successfully_m')]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update strategic document child error:'. $e);
            return response()->json(['main_error' => __('messages.system_error')], 200);
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $item = StrategicDocumentChildren::find($id);

            if(!$item){
                return back()->with('warnig', __('messages.records_not_found'));
            }

            if (request()->user()->cannot('update', $item->strategicDocument)) {
                return back()->with('warning', __('messages.unauthorized'));
            }

            $redirectToRootSd = $item->parent_id ? 0 : $item->strategicDocument->id;
            //delete children documents
            $searchToDelete = true;
            $childrenToSearch = [$item->id];
            $toDelete = [];
            while($searchToDelete){
                $childrenToSearch = StrategicDocumentChildren::whereIn('parent_id', $childrenToSearch)->get();
                if($childrenToSearch->count()){
                    $childrenToSearch = $childrenToSearch->pluck('id')->toArray();
                    $toDelete = array_merge($toDelete, $childrenToSearch);

                } else {
                    $searchToDelete = false;
                }
            }

            if(sizeof($toDelete)){
                $children = StrategicDocumentChildren::whereIn('id', $toDelete)->get();
                if($children->count()){
                    foreach ($children as $c){
                        $c->delete();
                        $c->files->each->delete();
                    }
                }
//                File::whereIn('id_object', $toDelete)->where('code_object', '=', File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN)->get()->delete();
            }


            $item->files->each->delete();
            $item->delete();
            DB::commit();

            return redirect($redirectToRootSd ? route('admin.strategic_documents.edit', $redirectToRootSd) : url()->previous())
                ->with('success', trans_choice('custom.strategic_documents.documents', 1)." ".__('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
