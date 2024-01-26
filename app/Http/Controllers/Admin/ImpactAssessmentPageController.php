<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageStoreRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImpactAssessmentPageController extends AdminController
{
    public function info(Request $request){
        $page = Page::with('files')->BySysName(Page::IA_INFO)->first();
        if(!$page) {
            abort(404);
        }

        if($request->user()->cannot('update', $page)) {
            abort(403);
        }

        if($request->isMethod('put')){
            $rp = new PageStoreRequest();
            $validator = Validator::make($request->all(), $rp->rules());
            if($validator->fails()) {
                return back()->withErrors($validator->errors())->withInput();
            }
            if($this->savePage($validator->validated(), $page)){
                return redirect(route('admin.impact_assessment.info') )
                    ->with('success', trans_choice('custom.pages', 1)." ".__('messages.updated_successfully_m'));
            } else{
                return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
            }
        }

        return $this->editPage($page, 'admin.impact_assessment.info');
    }

    private function editPage($page, $storeRouteName){
        $translatableFields = Page::translationFieldsProperties();
        $item = $page;
        return $this->view('admin.impact_assessment.edit_page', compact('item', 'storeRouteName', 'translatableFields'));
    }

    private function savePage($validated, $item){
        DB::beginTransaction();
        try {
            if( empty($validated['slug']) ) {
                $validated['slug'] = Str::slug($validated['name_bg']);
            }
            $fillable = $this->getFillableValidated($validated, $item);
            $item->in_footer = (int)isset($validated['in_footer']);
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(Page::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
