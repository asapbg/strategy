<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StrategicDocumentChildStoreRequest;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicActType;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\StrategicDocumentType;
use App\Observers\StrategicDocumentChildObserver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StrategicDocumentChildController extends AdminController
{
    public function popupForm(StrategicDocument $sd, $doc = null)
    {
        if ($doc) {
            $doc = StrategicDocumentChildren::find((int)$doc);
        }
        $strategicDocumentTypes = StrategicDocumentType::with('translations')->orderByTranslation('name')->get();
        $strategicActTypes = StrategicActType::with('translations')->orderByTranslation('name')->get();
        $legalActTypes = LegalActType::StrategyCategories()->with('translations')->get();
        $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')
            ->where('nomenclature_level_id', '=', $sd->strategic_document_level_id)
            ->get();

        return $this->view('admin.strategic_documents.popup_form',
                compact('sd', 'doc', 'strategicDocumentTypes', 'legalActTypes', 'strategicActTypes', 'authoritiesAcceptingStrategic')
            )
            ->render();
    }

    public function create(Request $request)
    {
        $r = new StrategicDocumentChildStoreRequest();
        $validator = Validator::make($request->all(), $r->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $validated = $validator->validated();
        $sd = StrategicDocument::find((int)$validated['sd']);
        if (!$sd) {
            return response()->json(['main_error' => __('messages.records_not_found')], 200);
        }

        if ($request->user()->cannot('update', $sd)) {
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
            Log::error('Create strategic document child error:' . $e);
            return response()->json(['main_error' => __('messages.system_error')], 200);
        }
    }

    public function edit(Request $request, $item)
    {
        $item = StrategicDocumentChildren::find((int)$item);
        if (!$item) {
            return back()->with('warning', __('messages.record_not_found'));
        }
        if ($request->user()->cannot('update', $item->strategicDocument)) {
            return redirect(route('admin.home'))->with('warning', __('messages.unauthorized'));
        }

        $sdDocuments = $item->strategicDocument->documents;
        $documentTree = StrategicDocumentChildren::getTree($item->id);
        $canDeleteSd = $request->user()->can('delete', $item->strategicDocument);
        $strategicDocumentTypes = StrategicDocumentType::with('translations')->orderByTranslation('name')->get();
        $legalActTypes = LegalActType::StrategyCategories()->with('translations')->get();
        $strategicActTypes = StrategicActType::with('translations')->orderByTranslation('name')->get();
        $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')->get();
        return $this->view('admin.strategic_documents.documents.edit', compact(
                'item',
                'sdDocuments',
                'documentTree',
                'canDeleteSd',
                'strategicDocumentTypes',
                'legalActTypes',
                'strategicActTypes',
                'authoritiesAcceptingStrategic'
            )
        );
    }

    public function update(Request $request)
    {
        $r = new StrategicDocumentChildStoreRequest();
        $validator = Validator::make($request->all(), $r->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_OK);
        }

        $validated = $validator->validated();
        $validated['document_date_accepted'] = isset($validated['pris_act_id'])
            ? Pris::find($validated['pris_act_id'])->doc_date
            : ($validated['document_date_accepted'] ?? Carbon::now());
        $validated['document_date_expiring'] = isset($validated['date_expiring_indefinite'])
            ? null
            : ($validated['document_date_expiring'] ?? Carbon::now());

        $item = StrategicDocumentChildren::find((int)$validated['id']);
        if (!$item) {
            return response()->json(['main_error' => __('messages.record_not_found')], Response::HTTP_OK);
        }

        if ($request->user()->cannot('update', $item->strategicDocument)) {
            return response()->json(['main_error' => __('messages.unauthorized')], Response::HTTP_OK);
        }

        DB::beginTransaction();

        try {

            if ($validated['accept_act_institution_type_id'] == AuthorityAcceptingStrategic::COUNCIL_MINISTERS) {
                $validated['strategic_act_type_id'] = null;
                $validated['strategic_act_link'] = null;
                $validated['document_date'] = null;
            } else {
                $validated['pris_act_id'] = null;
                $validated['document_date'] = databaseDate($validated['document_date']);
            }
            $real_update = false;
            $translation = $item->translation;
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $this->storeTranslateOrNew(StrategicDocumentChildren::TRANSLATABLE_FIELDS, $item, $validated);
            $item->save();
            $dirty = $item->getChanges();
            $t_dirty = $translation?->getChanges() ?? [];
            unset($dirty['updated_at'], $t_dirty['updated_at']);
            if (count($dirty) || count($t_dirty)) {
                $real_update = true;
            }
            if ($real_update) {
                $observer = new StrategicDocumentChildObserver();
                $observer->sendEmails($item, "updated");
            }

            DB::commit();
            return response()->json(['success' => 1, 'success_message' => trans_choice('custom.strategic_documents.documents', 1) . ' ' . $item->title . ' ' . __('messages.updated_successfully_m')]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update strategic document child error:' . $e);
            return response()->json(['main_error' => __('messages.system_error')], 200);
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $item = StrategicDocumentChildren::find($id);

            if (!$item) {
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
            while ($searchToDelete) {
                $childrenToSearch = StrategicDocumentChildren::whereIn('parent_id', $childrenToSearch)->get();
                if ($childrenToSearch->count()) {
                    $childrenToSearch = $childrenToSearch->pluck('id')->toArray();
                    $toDelete = array_merge($toDelete, $childrenToSearch);

                } else {
                    $searchToDelete = false;
                }
            }

            if (sizeof($toDelete)) {
                $children = StrategicDocumentChildren::whereIn('id', $toDelete)->get();
                if ($children->count()) {
                    foreach ($children as $c) {
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
                ->with('success', trans_choice('custom.strategic_documents.documents', 1) . " " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }
}
