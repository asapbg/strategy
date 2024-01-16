<?php

namespace App\Http\Controllers;

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
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use App\Services\FileOcr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
     * @param File $file
     * @return bool|RedirectResponse
     * @throws FilesystemException
     */
    public function deleteFile(Request $request, File $file, $disk = 'public_uploads')
    {
        $user = $request->user();
        if( !$user->can('delete', $file) ) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $route = match ((int)$file->code_object) {
//            File::CODE_OBJ_PUBLICATION => url()->previous().'#ct-files',
//            File::CODE_OBJ_PAGE => url()->previous().'#ct-files',
            default => url()->previous().'#ct-files',
        };
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
                $requestData['reg_num'] = sizeof($explode) && isset($explode[0]) ? $explode[0] : '';
                $requestData['title'] = sizeof($explode) && isset($explode[1]) ? $explode[1] : $requestData['search'] ?? '';
                $data = PublicConsultation::select2AjaxOptions($requestData);
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
}
