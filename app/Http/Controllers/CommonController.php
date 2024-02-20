<?php

namespace App\Http\Controllers;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Http\Requests\PageFileUploadRequest;
use App\Models\AdvisoryBoard;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\Page;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use App\Services\FileOcr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\FilesystemException;

class CommonController extends Controller
{

    /**
     * @params entityId required
     *         model required
     *         booleanType required (Ex: active, status)
     *         status required
     * Toggle Model's active database field
     * @param Request $request
     */
    public function toggleBoolean(Request $request)
    {
        if (
            !$request->filled('entityId')
            || !$request->filled('model')
            || !$request->filled('booleanType')
            || !$request->filled('status')
        ) {
            return back();
        }
        $entityId = $request->get('entityId');
        $booleanType = $request->get('booleanType');
        $model = "\App\Models\\".$request->get('model');
        if (!class_exists($model)) {
            $model = "\App\\".$request->get('model'); //Spatie\Permission\Models
            if (!class_exists($model)) {
                $model = "Spatie\Permission\Models\\".$request->get('model');
                if (!class_exists($model)) {
                    return back();
                }
            }
        }
        $status = $request->get('status');

        $entity = $model::find($entityId);
        $entity->$booleanType = $status;
        $entity->save();
    }

    /**
     * @params entityId required
     *         model required
     *         permission required
     *         status required
     * Toggle Model's permissions
     * @param Request $request
     */
    public function togglePermissions(Request $request)
    {
        if (
            !$request->filled('entityId')
            || !$request->filled('model')
            || !$request->filled('permission')
            || !$request->filled('status')
        ) {
            return back();
        }
        $entityId = request()->get('entityId');
        $permission = request()->get('permission');
        $model = "\App\Models\\".request()->get('model');
        if (!class_exists($model)) {
            $model = "\App\\".request()->get('model');
            if (!class_exists($model)) {
                return back();
            }
        }
        $status = request()->get('status');
        $entity = $model::find($entityId);

        if ($status == 0) {
            $entity->revokePermissionTo($permission);
        }
        else {
            $entity->givePermissionTo($permission);
        }
    }

    /**
     * Fix the primary key sequence for a given table
     *
     * @param $table
     */
    public static function fixSequence($table)
    {
        $primary_key_info = DB::select(DB::raw("SELECT a.attname AS name, format_type(a.atttypid, a.atttypmod) AS type FROM pg_class AS c JOIN pg_index AS i ON c.oid = i.indrelid AND i.indisprimary JOIN pg_attribute AS a ON c.oid = a.attrelid AND a.attnum = ANY(i.indkey) WHERE c.oid = '" . $table . "'::regclass"));
        $primary_key_type = 'number';
        $primary_key_name = 'id';
        if (array_key_exists('0', $primary_key_info)) {
            $primary_key_type = $primary_key_info[0]->type;
            $primary_key_name = $primary_key_info[0]->name;
        }
        if (strpos($primary_key_type, 'character') === false) {
            $max_id = DB::table($table)->max($primary_key_name);
            $next_id = $max_id + 1;
            $sequence_key_name = $table . '_' . $primary_key_name . '_seq';
            DB::statement("ALTER SEQUENCE $sequence_key_name RESTART WITH $next_id");
        }
    }

    public function uploadFile(PageFileUploadRequest $request, $objectId, $typeObject) {
        try {
            $validated = $request->validated();
            $fileNameToStore = round(microtime(true)).'.'.$validated['file']->getClientOriginalExtension();
            // Upload File
            $pDir = match ((int)$typeObject) {
                File::CODE_OBJ_PAGE => File::PAGE_UPLOAD_DIR,
                File::CODE_OBJ_PUBLICATION => File::PUBLICATION_UPLOAD_DIR,
                default => '',
            };
            $validated['file']->storeAs($pDir, $fileNameToStore, 'public_uploads');
            $item = new File([
                'id_object' => $objectId,
                'code_object' => $typeObject,
                'filename' => $fileNameToStore,
                'content_type' => $validated['file']->getClientMimeType(),
                'path' => $pDir.$fileNameToStore,
                'description' => $validated['description'],
                'sys_user' => $request->user()->id,
            ]);
            $item->save();

            $route = match ((int)$typeObject) {
                File::CODE_OBJ_PAGE => route('admin.page.edit', Page::find($objectId)) . '#ct-files',
                File::CODE_OBJ_PUBLICATION => route('admin.publications.edit', Publication::find($objectId)) . '#ct-files',
                default => '',
            };
            return redirect($route)->with('success', 'Файлът/файловте са качени успешно');
        } catch (\Exception $e) {
            logError('Upload file', $e->getMessage());
            return back()->with(['danger' => 'Възникна грешка. Презаредете страницата и опитайте отново.']);
        }
    }

    /**
     * Download public file
     * @param Request $request
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \League\Flysystem\FilesystemException
     */
    public function downloadFile(Request $request, File $file, $disk = 'public_uploads')
    {
        //TODO Do we need other check here? Permission or else in some cases
        if( !in_array($file->code_object,
            [
                File::CODE_OBJ_PUBLICATION,
                File::CODE_OBJ_LEGISLATIVE_PROGRAM,
                File::CODE_OBJ_OPERATIONAL_PROGRAM,
                File::CODE_OBJ_PRIS,
                File::CODE_OBJ_PUBLIC_CONSULTATION,
                File::CODE_AB,
                File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL,
                File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL,
                File::CODE_OBJ_STRATEGIC_DOCUMENT,
            ]) ) {
            return back()->with('warning', __('custom.record_not_found'));
        }

        $path = match ($disk){
            default => str_replace('files'.DIRECTORY_SEPARATOR, '', $file->path)
        };

        if (Storage::disk('public_uploads')->has($path)) {
            return Storage::disk('public_uploads')->download($path, $file->filename);
        } else {
            return back()->with('warning', __('custom.record_not_found'));
        }
    }

    public function previewModalFile(Request $request, $id = 0)
    {
        $file = File::find($id);

        if (!$file) {
            return __('messages.record_not_found');
        }

        return fileHtmlContent($file);
    }

    /**
     * Delete public file
     * @param Request $request
     * @param $file
     * @return bool|RedirectResponse
     * @throws FilesystemException
     */
    public function deleteFile(Request $request, $file, $disk = 'public_uploads')
    {
        $sdFile = $request->get('is_sd_file') ?? 0;
        $user = $request->user();

        $file = $sdFile ? StrategicDocumentFile::find((int)$file) : File::find((int)$file);

        if( !$user->can('delete', $file) ) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $route = match ((int)$file->code_object) {
//            File::CODE_OBJ_PUBLICATION => url()->previous().'#ct-files',
            File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL => url()->previous(),
            File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL => url()->previous(),
            default => url()->previous().'#ct-files',
        };

        if( $file->code_object == File::CODE_OBJ_PUBLICATION ){
            $publication = Publication::where('file_id', '=', $file->id)
                ->where('id', '=', $file->id_object)
                ->first();
            if($publication) {
                $publication->file_id = null;
                $publication->save();
            }
        }

        $file->delete();

        if(!File::where('id', '<>', $file->id)->where('path', '=', $file->path)->count()){
            if (Storage::disk($disk)->has($file->path)) {
                Storage::disk($disk)->delete($file->path, $file->filename);
            }
        }
        return redirect($route)->with('success', 'Файлът е изтрит успешно');
    }

    /**
     * Download public file
     * @param Request $request
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \League\Flysystem\FilesystemException
     */
    public function downloadPageFile(Request $request, File $file)
    {
        if( $file->code_object != File::CODE_OBJ_PAGE ) {
            return back()->with('warning', __('custom.record_not_found'));
        }

        if (Storage::disk('public_uploads')->has($file->path)) {
            return Storage::disk('public_uploads')->download($file->path, $file->filename);
        } else {
            return back()->with('warning', __('custom.record_not_found'));
        }
    }

    public function modalInstitutions(Request $request)
    {
        $canSelect = (boolean)$request->input('select');
        $multipleSelect = (boolean)$request->input('multiple');
        $selectId = $request->input('dom') ?? 'institutions';
        $institutions = Institution::getTree($request->all());
//        $oldBootstrap = $request->input('admin') && $request->input('admin'); //ugly way to fix design for bootstrap
        $oldBootstrap = true; //ugly way to fix design for bootstrap
        return view('partials.institutions_tree', compact('institutions', 'canSelect', 'multipleSelect', 'oldBootstrap', 'selectId'));
    }

    public function getSelect2Ajax(Request $request, $type = '')
    {
        $requestData = $request->all();
        switch ($type) {
            case 'pris_doc':
                $explode = isset($requestData['search']) ? explode('/', $requestData['search']) : [];
                $requestData['year'] = sizeof($explode) && isset($explode[1]) ? $explode[1] : '';
                $requestData['doc_num'] = sizeof($explode) && isset($explode[0]) ? $explode[0] : '';
                $data = Pris::select2AjaxOptions($requestData);
                break;
            case 'lp_record':
                $data = LegislativeProgram::select2AjaxOptions($requestData);
                break;
            case 'lp_record_pc':
                $data = LegislativeProgram::select2AjaxOptionsFilterByInstitution($requestData);
                break;
            case 'op_record':
                $requestData['op_record'] = true;
                $data = OperationalProgram::select2AjaxOptions($requestData);
                break;
            case 'op_record_pc':
                $data = OperationalProgram::select2AjaxOptionsFilterByInstitution($requestData);
                break;
            case 'pc':
                $explode = isset($requestData['search']) ? explode('/', $requestData['search']) : [];
                if(sizeof($explode) == 2){
                    $requestData['reg_num'] = trim($explode[0]);
                    $requestData['title'] = trim($explode[1]);
                } elseif(sizeof($explode) == 1){
                    $requestData['title'] = trim($requestData['search']);
                }
//                $requestData['reg_num'] = sizeof($explode) && isset($explode[0]) ? $explode[0] : '';
//                $requestData['title'] = sizeof($explode) && isset($explode[1]) ? $explode[1] : $requestData['search'] ?? '';
                $data = PublicConsultation::select2AjaxOptions($requestData);
                break;
            case 'sd_parent_documents':
                $filter = array();
                $filter['search'] = $requestData['search'] ?? null;
                $filter['sd_document_id'] = $requestData['document'];
                if(isset($requestData['level'])) {
                    if((int)$requestData['level'] == InstitutionCategoryLevelEnum::CENTRAL->value && isset($requestData['policy'])){
                        $filter['field_of_action_id'] = (int)$requestData['policy'];
                    } else if((int)$requestData['level'] == InstitutionCategoryLevelEnum::AREA->value && isset($requestData['areaPolicy'])){
                        $filter['field_of_action_id'] = (int)$requestData['areaPolicy'];
                    } else if((int)$requestData['level'] == InstitutionCategoryLevelEnum::MUNICIPAL->value && isset($requestData['municipalityPolicy'])){
                        $filter['field_of_action_id'] = (int)$requestData['municipalityPolicy'];
                    } else{
                        $filter['field_of_action_id'] = 0;
                    }
                }

                $data = StrategicDocument::select2AjaxOptions($filter);
                break;
            case 'tag':
                $data = Tag::select2AjaxOptions($requestData);
                break;
            case 'adv_board':
                $data = AdvisoryBoard::select2AjaxOptions($requestData);
                break;
        }

        return response()->json($data);
    }

    public function commonHtml()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized: You do not have the super-admin role.');
        }

        return view('templates.common-html');
    }

    /**
     * @param Request $request
     * @param $objectId
     * @param $typeObject
     * @param $rowNum
     * @param $rowMonth
     * @return JsonResponse
     */
    public function uploadFileLpOp(Request $request, $objectId, $typeObject, $rowNum, $rowMonth) {

        $req = new LanguageFileUploadRequest();
        $validator = Validator::make($request->all(), $req->rules());
        if($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 200);
        }

        //$validated = $validator->validated();
        DB::beginTransaction();
        try {
            $typeObjectToSave = $typeObject;
            $validated = $request->all();
            // Upload File
            $pDir = match ((int)$typeObject) {
                File::CODE_OBJ_OPERATIONAL_PROGRAM => File::OP_GENERAL_UPLOAD_DIR,
                File::CODE_OBJ_LEGISLATIVE_PROGRAM => File::LP_GENERAL_UPLOAD_DIR,
                default => '',
            };

            switch (((int)$typeObject)){
                case File::CODE_OBJ_OPERATIONAL_PROGRAM:
                    $item = OperationalProgram::find($objectId);
                    $route = route('admin.consultations.operational_programs.edit', $item);
                    break;
                case File::CODE_OBJ_LEGISLATIVE_PROGRAM:
                    $item = LegislativeProgram::find($objectId);
                    $route = route('admin.consultations.legislative_programs.edit', $item);
                    break;
            }

            $fileIds = [];
            foreach ($this->languages as $lang) {
                $code = $lang['code'];

                if (!isset($validated['file_'.$code])) {
                    continue;
                }

                if (!isset($validated['file_'.$code])) {
                    $file = $validated['file_bg'];
                    $desc = $validated['description_bg'] ?? null;
                } else {
                    $file = isset($validated['file_'.$code]) && $validated['file_'.$code] ? $validated['file_'.$code] : $validated['file_bg'];
                    $desc = isset($validated['description_'.$code]) && !empty($validated['description_'.$code]) ? $validated['description_'.$code] : ($validated['description_'.config('app.default_lang')] ?? null);
                }

                $version = File::where('locale', '=', $code)->where('id_object', '=', $objectId)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
                $file->storeAs($pDir, $fileNameToStore, 'public_uploads');

                $newFile = new File([
                    'id_object' => $objectId,
                    'code_object' => $typeObjectToSave,
                    'filename' => $fileNameToStore,
                    'content_type' => $file->getClientMimeType(),
                    'path' => $pDir.$fileNameToStore,
                    'description_'.$code => $desc,
                    'sys_user' => $request->user()->id,
                    'locale' => $code,
                    'version' => ($version + 1).'.0',
                    'is_visible' => isset($validated['is_visible']) ? (int)$validated['is_visible'] : 0
                ]);
                $newFile->save();
                $ocr = new FileOcr($newFile->refresh());
                $ocr->extractText();
                $item->rowFiles()->attach($newFile->id ,['row_month' => $rowMonth, 'row_num' => $rowNum]);
                $item->save();
            }

            DB::commit();
            return response()->json(['redirect_url' => $route ?? url()->previous()], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            logError('Upload file', $e->getMessage());
            return response()->json(['main_error' => 'Възникна грешка при качването на файловете. Презаредете страницата и опитайте отново.'], 200);
        }
    }
}
