<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardFileRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardFileRequest;
use App\Models\AdvisoryBoard;
use App\Models\File;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class AdvisoryBoardFileController extends AdminController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardFileRequest $request
     * @param AdvisoryBoard                 $item
     *
     * @return JsonResponse
     */
    public function ajaxStore(StoreAdvisoryBoardFileRequest $request, AdvisoryBoard $item): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            foreach (config('available_languages') as $lang) {
                $this->upload(
                    $validated['file_' . $lang['code']],
                    $lang['code'], $validated['object_id'],
                    $validated['doc_type_id'],
                    $validated['file_description_' . $lang['code']],
                    $validated['file_name_' . $lang['code']],
                    $validated['resolution_council_ministers_' . $lang['code']],
                    $validated['state_newspaper_' . $lang['code']],
                    $validated['effective_at'],
                );
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => __('messages.system_error')], 500);
        }
    }

    private function upload(
        $file,
        string $language,
        int $id_object,
        string $doc_type,
        string $description = null,
        ?string $custom_name = null,
        ?string $resolution = null,
        ?string $state_newspaper = null,
        ?string $effective_at = null
    )
    {
        if (!$file) {
            return;
        }

        $version = File::where('locale', '=', $language)
            ->where('id_object', '=', $id_object)
            ->where('doc_type', '=', $doc_type)
            ->where('code_object', '=', File::CODE_AB_FUNCTION)
            ->count();

        $store_name = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
        $dir = File::ADVISORY_BOARD_UPLOAD_DIR . $id_object . DIRECTORY_SEPARATOR;

        $sub_dir = match ((int)$doc_type) {
            DocTypesEnum::AB_SECRETARIAT->value => File::ADVISORY_BOARD_SECRETARIAT_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            default => '',
        };

        $full_dir = $dir . $sub_dir;

        $file->storeAs($full_dir, $store_name, 'public_uploads');

        $newFile = new File([
            'id_object' => $id_object,
            'code_object' => File::CODE_AB_FUNCTION,
            'filename' => $store_name,
            'doc_type' => $doc_type,
            'content_type' => $file->getClientMimeType(),
            'path' => $full_dir . $store_name,
            'description_' . $language => $description ?? __('custom.public_consultation.doc_type.' . $doc_type, [], $language),
            'sys_user' => auth()->user()->id,
            'locale' => $language,
            'version' => ($version + 1) . '.0',
            'custom_name' => $custom_name,
            'resolution_council_ministers' => $resolution,
            'state_newspaper' => $state_newspaper,
            'effective_at' => Carbon::parse($effective_at),
        ]);

        $newFile->save();

        return $newFile;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     *
     * @return JsonResponse
     */
    public function ajaxEdit(AdvisoryBoard $item, File $file): JsonResponse
    {
        $this->authorize('update', [AdvisoryBoard::class, $item]);

        return response()->json($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardFileRequest $request
     * @param AdvisoryBoard                  $item
     *
     * @return JsonResponse
     */
    public function ajaxUpdate(UpdateAdvisoryBoardFileRequest $request, AdvisoryBoard $item): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            if (!isset($validated['file'])) {
                foreach (config('available_languages') as $lang) {
                    if (isset($validated['file_name_' . $lang['code']])) {
                        File::find($validated['file_id'])->update(['custom_name' => $validated['file_name_' . $lang['code']], 'description_bg' => $validated['file_description_' . $lang['code']]]);

                        break;
                    }
                }

                DB::commit();
                return response()->json(['status' => 'success']);
            }

            $file = File::find($validated['file_id']);

            //Add file and attach
            $updated_file = $this->upload(
                $validated['file'],
                $file->locale,
                $file->id_object,
                $file->doc_type,
                $validated['file_description_' . $file->locale],
                $validated['file_name_' . $file->locale],
            );

            if (\Illuminate\Support\Facades\File::exists(public_path('files' . DIRECTORY_SEPARATOR . $updated_file->path))) {
                $file->delete();
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => __('messages.system_error')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     *
     * @return RedirectResponse
     */
    public function destroy(AdvisoryBoard $item, File $file)
    {
        $this->authorize('delete', [AdvisoryBoard::class, $item]);

        $route = redirect()->back()->getTargetUrl() . $file->advisoryBoardTab;

        try {
            $file_name = $file->custom_name ?? $file->filename;

            $file->delete();

            return redirect()->to($route)
                ->with('success', __('custom.file') . " $file_name " . __('messages.deleted_successfully_m'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect($route)->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param AdvisoryBoard $item
     * @param File          $file
     *
     * @return RedirectResponse
     */
    public function restore(AdvisoryBoard $item, File $file)
    {
        $this->authorize('restore', [AdvisoryBoard::class, $item]);

        $route = redirect()->back()->getTargetUrl() . $file->advisoryBoardTab;

        try {
            $file_name = $file->custom_name ?? $file->filename;

            $file->restore();

            return redirect()->to($route)
                ->with('success', __('custom.file') . " $file_name " . __('messages.restored_successfully_m'));
        } catch (Exception $e) {
            Log::error($e);
            return redirect($route)->with('danger', __('messages.system_error'));
        }
    }
}
