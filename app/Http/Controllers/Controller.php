<?php

namespace App\Http\Controllers;

use App\Enums\PageModulesEnum;
use App\Enums\PublicationTypesEnum;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Models\File;
use App\Models\Page;
use App\Models\StrategicDocumentFile;
use App\Models\User;
use App\Models\UserSubscribe;
use App\Services\FileOcr;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use romanzipp\Seo\Structs\Meta;
use romanzipp\Seo\Structs\Meta\OpenGraph;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var string $title_singular */
    protected $title_singular;

    /** @var string $title_plural */
    protected $title_plural;

    /** @var string $breadcrumb_title */
    protected $breadcrumb_title;

    /** @var array $request */
    protected array $request = [];

    /** @var array $languages */
    protected array $languages = [];

    /** @var string $route_name */
    protected ?string $route_name;

    protected array $slider = [];
    protected array $customBreadcrumb = [];

    /**
     * Set pages titles in singular and plural according to controller / model
     * Get the request
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->route_name = $request->route()?->getName();
        $this->request = $request->all();
        $trans_lang = $this->getControllerHandleName();
        $this->languages = config('available_languages');

        $this->title_singular = trans_choice($trans_lang, 1);
        $this->title_plural   = trans_choice($trans_lang, 2);

        //Seo
        seo()->title(__('site.seo_title'));
        seo()->meta('keywords', __('site.seo_description'));
        seo()->meta('description', __('site.seo_keywords'));
        seo()->og('title', __('site.seo_title'));
        seo()->og('type', 'website');
        seo()->og('url', request()->url());
        seo()->og('image', asset('images/ms-2023.jpg'));
    }

    /**
     * Return view with given variables combined with the global ones
     *
     * @param string $view
     * @param array $variables
     * @return View
     */
    protected function view(string $view, array $variables = [])
    {
        return view($view)->with(
            array_merge(
                [
                    'title_singular'    => $this->title_singular,
                    'title_plural'      => $this->title_plural,
                    'languages'         => $this->languages,
                    'breadcrumbs'       => $this->breadcrumbs(),
                    'slider'       => $this->slider,
                ],
                $this->request,
                $variables
            )
        );
    }

    /**
     * Return back with all input data, if any, and display errors
     *
     * @param $error_type | 'success', 'warning', 'danger', 'info'
     * @param $error_msg
     *
     * @return RedirectResponse
     */
    protected function backWithError($error_type, $error_msg)
    {
        return back()->withInput(request()->all())->with($error_type, $error_msg);
    }


    protected function composedBreadcrumbs(){
        $breadcrumbs['links'] = $this->customBreadcrumb;
        $breadcrumbs['links_count'] = sizeof($this->customBreadcrumb);
        $breadcrumbs['heading'] = $this->customBreadcrumb[sizeof($this->customBreadcrumb) - 1]['name'];
        return $breadcrumbs;
    }

    /**
     * Generate the breadcrumbs
     */
    protected function breadcrumbs()
    {
        if(!empty($this->customBreadcrumb)){
            return $this->composedBreadcrumbs();
        }
        $breadcrumbs = [];

        $exclude_routes = [
            "site.home",
            "admin.home",
//            "admin.activity-logs.show"
        ];

        if (in_array($this->route_name, $exclude_routes)) {
            return $breadcrumbs;
        }

        $segments = request()->segments();
        $links_count = count(request()->segments())-1;
        $text = __('custom.list_with');

        if($links_count == -1) {
            return $breadcrumbs;
        }

        if ($links_count && $segments[$links_count] == "create") {
            array_pop($segments);
            $heading = array_key_exists($this->title_singular, trans('custom'))
                ? __('custom.creation_of').$this->title_singular
                : __('Create a new record');
            $segments[] = $heading;
        }
        if ($segments[$links_count] == "edit") {
            $links_count--;
            $segments = array_slice(request()->segments(), 0, $links_count);
            $heading = array_key_exists($this->title_singular, trans('custom'))
                ? __('custom.edit_of').$this->title_singular
                : __('Update a record');
            $segments[] = $heading;
        }

        if ($links_count && $segments[$links_count] == "view") {
            array_pop($segments);
            $heading = __('custom.view_of').$this->title_singular;
            $segments[] = $heading;
        }

        if ($links_count && $segments[$links_count] == "section") {
            array_pop($segments);
            $heading = __('custom.view_of').$this->title_singular;
            $segments[] = $heading;
        }

        if ($links_count && $this->route_name == "library.details") {
            array_pop($segments);
            $type = request()->route('type');
            $segments[] = ($type == PublicationTypesEnum::TYPE_LIBRARY->value) ? "publications" : "news";
            $segments[] = "";
        }

        $breadcrumbs['heading'] = $this->breadcrumb_title ?? $heading ?? '';
        $url = '';

        foreach ($segments as $segment) {

            if (
                $segment == 'view'
                || $segment == 'create-edit'
                || $segment == 'bg'
                || $segment == 'en'
                || ($segment == "profile" && in_array("users", $segments))
                || $segment == "users" && in_array("profile", $segments)
                || (is_numeric($segment) && !in_array('publications', $segments))
            ) {
                continue;
            }

            $url .= "/$segment";
            $name = str_replace("-", "_", $segment);
            $display_name = array_key_exists($name, trans('custom'))
                ? trans_choice("custom.$name", 2)
                : __(capitalize($name));

            if ($name == "admin") {
                $display_name = __("custom.home");
            }

            if ($name == "users") {
                $user_type = request()->offsetGet('type');
                $url .= "?type=$user_type";
                if ($user_type && isset(User::getUserTypes()[$user_type])) {
                    $display_name = trans_choice(User::getUserTypes()[$user_type], 2);
                }
            }

            if (strstr($display_name, 'custom')) {
                $display_name = $segment;
            }

            if (!empty($display_name) && (!is_numeric($display_name))) {
                $breadcrumbs['links'][] = [
                    'url'   => $url,
                    'name'  => $display_name
                ];
            }
        }

        $breadcrumbs['links_count'] = count($breadcrumbs['links']) - 1;

        if (isset($this->breadcrumb_title)) {
//            $breadcrumbs['links'][$links_count]['name'] = $this->breadcrumb_title;
            $breadcrumbs['links'][] = [
                'name' => $this->breadcrumb_title
            ];
        }

        if(in_array(request()->route()->getName(), ['admin.user.notification_show', 'admin.activity-logs.show']) && sizeof($breadcrumbs) && isset($breadcrumbs['links']) && sizeof($breadcrumbs['links'])){
            unset($breadcrumbs['links'][sizeof($breadcrumbs) - 1]);
        }
        return $breadcrumbs;
    }

    /**
     * Generate the translation term for the current model from the Controller's name
     *
     * @return string
     */
    private function getControllerHandleName(): string
    {
        $controller =  get_class($this);
        $expl = explode("\\", $controller);
        $c_name = $expl[count($expl)-1];
        $pieces = preg_split('/(?=[A-Z])/', $c_name);
        // remove first and last elements
        array_shift($pieces);
        array_pop($pieces);
        $model_lang = mb_strtolower(implode("_", $pieces), "UTF-8");
        if (mb_substr($model_lang, -1) == "y") {
            $model_lang = rtrim($model_lang, "y") . "ie";
        }
        if (mb_substr($model_lang, -1) != "s") {
            $model_lang .= "s";
        }
        $trans_lang = "custom.$model_lang";
        if ($model_lang == "users") {
            //$trans_lang = (request()->offsetGet('type') == User::TYPE_INTERNAL) ? 'custom.internal_users' : 'custom.external_users';
        }

        return $trans_lang;
    }

    /**
     * @param string $title
     * @return void
     */
    protected function setTitleSingular(string $title)
    {
        $this->title_singular = $title;
    }

    /**
     * @param string $title
     * @return void
     */
    protected function setTitlePlural(string $title)
    {
        $this->title_plural = $title;
    }

    /**
     * @param string $title
     * @return void
     */
    protected function setBreadcrumbsTitle(string $title)
    {
        $this->breadcrumb_title = $title;
    }

    /**
     * @param string $title
     * @return void
     */
    protected function setTitles(string $title)
    {
        $this->title_singular   = $title;
        $this->title_plural     = $title;
        $this->breadcrumb_title = $title;
    }

    /**
     * @param LanguageFileUploadRequest $request
     * @param $objectId
     * @param $typeObject
     * @param int $docType
     * @param bool $redirect
     * @return Application|RedirectResponse|Redirector|void
     */
    public function uploadFileLanguages(LanguageFileUploadRequest $request, $objectId, $typeObject, $docType = 0, $redirect = true) {
        try {
            $typeObjectToSave = $typeObject == File::CODE_OBJ_AB_PAGE ? File::CODE_OBJ_PAGE : $typeObject;
            $validated = $request->all();
            // Upload File
            $pDir = match ((int)$typeObject) {
                File::CODE_OBJ_AB_PAGE => File::PAGE_UPLOAD_DIR,
                File::CODE_OBJ_PAGE => File::PAGE_UPLOAD_DIR,
                File::CODE_OBJ_PRIS => File::PAGE_UPLOAD_PRIS,
                File::CODE_OBJ_PUBLICATION => File::PUBLICATION_UPLOAD_DIR,
                File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL => File::OP_GENERAL_UPLOAD_DIR,
                File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL => File::LP_GENERAL_UPLOAD_DIR,
                File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN => StrategicDocumentFile::DIR_PATH,
                File::CODE_OBJ_OGP => File::OGP_PLAN_UPLOAD_DIR,

                default => '',
            };

            $fileIds = [];
            foreach ($this->languages as $lang) {

                $default = $lang['default'];
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
                    'doc_type' => (int)$docType > 0 ? $docType : null,
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
                $fileIds[] = $newFile->id;
                $ocr = new FileOcr($newFile->refresh());
                $ocr->extractText();
            }

//            File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
//            File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);


            switch ((int)$typeObject) {
                case File::CODE_OBJ_PRIS:
                    $route = route('admin.pris.edit', ['item' => $objectId]) . '#ct-files';
                    break;
                case File::CODE_OBJ_PAGE:
                    $page = Page::find($objectId);
                    if($page && $page->module_enum && $page->module_enum == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value){
                        $route = route('admin.impact_assessments.library.edit', ['item' => $objectId, 'module' => $page->module_enum]) . '#ct-files';
                    } else{
                        $route = route('admin.page.edit', ['item' => $objectId]) . '#ct-files';
                    }
                    break;
                case File::CODE_OBJ_AB_PAGE:
                case File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN:
                    $route = url()->previous().'#ct-files';
                    break;
                case File::CODE_OBJ_OGP:
                    $route = route('admin.ogp.plan.edit', ['id' => $objectId]).'#report';
                    break;
                default:
                    $route = '';
            }
            if ($redirect) {
                return redirect($route)->with('success', 'Файлът/файловте са качени успешно');
            }
        } catch (Exception $e) {
            logError('Upload file', $e->getMessage());
            return $this->backWithError('danger', 'Възникна грешка при качването на файловете. Презаредете страницата и опитайте отново.');
        }
    }

    /**
     * @param string $title
     * @param string $img
     * @return void
     */
    protected function setSlider(string $title, string $img)
    {
        $this->slider = ['title' => $title, 'img' => $img];
    }

    /**
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     * @return void
     */
    protected function setSeo(string|null $title = '', string|null $description ='', string|null $keywords = '', array $fbTags = [])
    {
        seo()->clearStructs();
        seo()->title(!empty($title) ? $title : __('site.seo_title'));
        seo()->meta('description', !empty($description) ? substr($description, 0, 180) : __('site.seo_description'));
        seo()->meta('keywords', !empty($keywords) ? $keywords : __('site.seo_keywords'));

        seo()->og('title', isset($fbTags) && isset($fbTags['title']) && !empty($fbTags['title']) ? $fbTags['title'] : (!empty($title) ? $title : __('site.seo_title')));
        seo()->og('description', isset($fbTags) && isset($fbTags['description']) && !empty($fbTags['description']) ? $fbTags['description'] : (!empty($description) ? substr($description, 0, 180) : __('site.seo_description')));
        seo()->og('type', isset($fbTags) && isset($fbTags['type']) && !empty($fbTags['type']) ? $fbTags['type'] : 'website');
        seo()->og('url', isset($fbTags) && isset($fbTags['url']) && !empty($fbTags['url']) ? $fbTags['url'] : request()->url());
        seo()->og('image', isset($fbTags) && isset($fbTags['img']) && !empty($fbTags['img']) ? asset($fbTags['img']) : asset('images/ms-2023.jpg'));
        seo()->og('image:secure', isset($fbTags) && isset($fbTags['img']) && !empty($fbTags['img']) ? asset($fbTags['img']) : asset('images/ms-2023.jpg'));
    }


    protected function setBreadcrumbsFull(array $segments)
    {
        $this->customBreadcrumb = $segments;
    }

    protected function hasSubscription($item = null, $modelClass = null, $filter = [], $channel = UserSubscribe::CHANNEL_EMAIL){
        $hasSubscription = false;
        $user = auth()->user();
        if($item && $user){
            if($user->subscriptions()->where(function ($q) use($channel, $item){
                $q->where('subscribable_id', '=', $item->id)
                    ->where('subscribable_type', '=', get_class($item))
                    ->where('channel', '=', $channel)
                    ->where('is_subscribed', true);
            })->count()) {
                $hasSubscription = true;
            };
        } elseif ($modelClass && $user){
            if($user->subscriptions()->where(function ($q) use($channel, $modelClass, $filter){
                    if(empty($filter)){
                        $q->whereNull('search_filters');
                    } else{
                        $q->where('search_filters', '=', json_encode($filter));
                    }
                    $q->where('subscribable_type', '=', $modelClass)
                        ->where('channel', '=', $channel)
                        ->whereNull('subscribable_id')
                        ->where('is_subscribed', true);
                })->count() ) {
                $hasSubscription = true;
            };
        }
        return $hasSubscription;
    }
}
