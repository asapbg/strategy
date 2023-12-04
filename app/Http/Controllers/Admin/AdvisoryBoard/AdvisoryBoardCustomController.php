<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardCustomRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardCustomRequest;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\File;
use DB;
use Illuminate\Http\JsonResponse;
use Log;

class AdvisoryBoardCustomController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardCustomRequest $request
     * @param AdvisoryBoard                   $item
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardCustomRequest $request, AdvisoryBoard $item, AdvisoryBoardCustom $section)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $section);
            $custom_sections_db = AdvisoryBoardCustom::where('advisory_board_id', $item->id);

            if (!$fillable['order']) {
                $fillable['order'] = 1 + $custom_sections_db->count();
            }

            if ($fillable['order'] && $fillable['order'] != 9999) {
                $custom_sections_db->where('order', '>=', $fillable['order'])->update(['order' => DB::raw('"order" + 1')]);
            }

            // Put at first position
            if ($fillable['order'] == 9999) {
                $fillable['order'] = 1;
                $custom_sections_db->update(['order' => DB::raw('"order" + 1')]);
            }

            $fillable['advisory_board_id'] = $item->id;

            $section->fill($fillable);
            $section->save();

            $validated['advisory_board_custom_id'] = $section->id;

            foreach (config('available_languages') as $lang) {
                $validated['body_' . $lang['code']] = htmlspecialchars_decode($validated['body_' . $lang['code']]);
            }

            $this->storeTranslateOrNew(AdvisoryBoardCustom::TRANSLATABLE_FIELDS, $section, $validated);

            foreach (config('available_languages') as $language) {
                if (isset($validated['file_' . $language['code']])) {
                    foreach ($validated['file_' . $language['code']] as $key => $file) {
                        $name_key = 'file_name_' . $language['code'];
                        $description_key = 'file_description_' . $language['code'];

                        $this->uploadFile(
                            $section,
                            $file,
                            File::CODE_AB_FUNCTION,
                            DocTypesEnum::AB_CUSTOM_SECTION,
                            $validated[$description_key][$key] ?? '',
                            $language['code'],
                            $validated[$name_key][$key] ?? null
                        );
                    }
                }
            }

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryBoardCustom $advisoryBoardCustom
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryBoardCustom $advisoryBoardCustom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryBoardCustom $advisoryBoardCustom
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryBoardCustom $advisoryBoardCustom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardCustomRequest $request
     * @param \App\Models\AdvisoryBoardCustom                                         $advisoryBoardCustom
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvisoryBoardCustomRequest $request, AdvisoryBoardCustom $advisoryBoardCustom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryBoardCustom $advisoryBoardCustom
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvisoryBoardCustom $advisoryBoardCustom)
    {
        //
    }
}
