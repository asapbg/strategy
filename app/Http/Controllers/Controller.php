<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

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
    private ?string $route_name;

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

    /**
     * Generate the breadcrumbs
     */
    protected function breadcrumbs()
    {
        $breadcrumbs = [];

        $exclude_routes = [
            "admin.home",
            "admin.activity-logs.show"
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

        $breadcrumbs['heading'] = $this->breadcrumb_title ?? $heading ?? '';
        $url = '';

        foreach ($segments as $segment) {

            if (
                $segment == 'view'
                || ($segment == "profile" && in_array("users", $segments))
                || $segment == "users" && in_array("profile", $segments)
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

            if (!empty($display_name) && !is_numeric($display_name)) {
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
}
