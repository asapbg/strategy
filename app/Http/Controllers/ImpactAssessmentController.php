<?php

namespace App\Http\Controllers;

use App\Enums\PageModulesEnum;
use App\Models\Executor;
use App\Models\FormInput;
use App\Models\Page;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use PDF;

class ImpactAssessmentController extends Controller
{

    public function info()
    {
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
            ->where('system_name', '=', Page::IA_INFO)
            ->first();
        if(!$page){
            abort(404);
        }
        $pageTitle = trans_choice('custom.impact_assessment', 1);
//        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->setSeo($page->meta_title ?? $page->name, $page->meta_description ?? $page->short_content, $page->meta_keyword, array('title' => $page->meta_title ?? $page->name, 'img' => Page::DEFAULT_IMG));

        $this->composeBreadcrumbs(array(['name' => __('site.base_info'), 'url' => '']));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('impact_assessment.page', compact('page', 'pageTitle', 'library'));
    }

    public function index()
    {
        $pageTitle = trans_choice('custom.impact_assessment', 1);
        $this->composeBreadcrumbs(array(['name' => __('site.base_info'), 'url' => '']));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('impact_assessment.index', compact('pageTitle', 'library'));
    }

    public function forms()
    {
        $pageTitle = trans_choice('custom.impact_assessment', 1);
        $this->composeBreadcrumbs(array(['name' => __('site.impact_assessment.forms_and_templates'), 'url' => '']));
        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();

        return $this->view('impact_assessment.forms', compact('pageTitle', 'library'));
    }

    public function form($formName, Request $request)
    {
        $state = $this->getState($formName);
        $step = $request->input('step', 1);
        $steps = $this->getSteps($formName);
        $inputId = $request->input('inputId', 0);
        //$pageTitle = $formName ? __("forms.$formName") : trans_choice('custom.impact_assessment', 1);
        //dd(session()->all());
        $pageTitle = trans_choice('custom.impact_assessment', 1);
        $this->composeBreadcrumbs(array(
            ['name' => __('site.impact_assessment.forms_and_templates'), 'url' => route('impact_assessment.forms')],
            ['name' => __("forms.$formName"), 'url' => '']
        ));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('site.impact_assessment', compact('pageTitle', 'formName', 'state', 'step', 'steps', 'inputId', 'library'));
    }

    public function store($formName, Request $request)
    {
        $userId = app('auth')->id();
        $data = $request->except(['_token']);

        $dataDots = \Arr::dot(\Arr::except($data, ['step', 'currentStep']));
        $isDataEmpty = true;
        foreach($dataDots as $dd) {
            if (!empty($dd)) {
                $isDataEmpty = false;
                break;
            }
        }

        if (array_key_exists('add_entry', $data)) {
            $key = $this->getKeyDots($data['add_entry']);
            $value = data_get($data, $key);
            array_push($value, '');
            data_set($data, $key, $value);
            unset($data['add_entry']);
        }
        if (array_key_exists('add_array_entry', $data)) {
            $key = $this->getKeyDots($data['add_array_entry']);
            $value = data_get($data, $key, [[]]);
            array_push($value, []);
            data_set($data, $key, $value);
            unset($data['add_array_entry']);
        }

        $inputId = $request->input('inputId', false);
        $submit = $request->input('submit');
        $state = $this->getState($formName, $inputId);

        $data = array_merge($state, $data);
        if ($inputId) $data['inputId'] = $inputId;
        session(["forms.$formName" => $data]);

//        if (!$isDataEmpty && (($userId && !$inputId) || $submit)) {
        //Always save data
        if (!$isDataEmpty && (($userId))) {
            $fi = FormInput::find($inputId);
            if ($fi) {
                $state = $this->getState($formName, $inputId);
            } else {
                $fi = new FormInput([
                    'form' => $formName,
                    'user_id' => $userId,
                    'by_admin' => auth()->user() && auth()->user()->user_type = User::USER_TYPE_INTERNAL,
                ]);
            }
            $fi->data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $fi->save();
            $inputId = $fi->id;
        }
        $step = $request->input('step', 1);
        $currentStep = $request->input('currentStep', 1);
        $rules = config("validation.$formName.step$currentStep");

        if ($currentStep <= $step || $submit) {
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {

                return redirect()
                    ->route('impact_assessment.form', ['form' => $formName, 'step' => $currentStep, 'inputId' => $inputId])
                    ->withInput()->withErrors($validator->errors());
            }
        }

        if ($submit) {
            session(["forms.$formName" => []]);
            if($userId){
                return redirect()->route('profile', ['tab' => 'form_inputs']);
            } else{
                return view('impact_assessment.submitted', compact('formName', 'inputId'));
            }

        }
        return redirect()->route('impact_assessment.form', ['form' => $formName, 'step' => $step, 'inputId' => $inputId]);
    }

    private function getKeyDots($key) {
        $key = \Str::endsWith($key, '[]')
            ? substr($key, 0, -2)
            : $key;
        $key = str_replace('[', '.', $key);
        $key = str_replace(']', '', $key);
        return $key;
    }

    public function show($formName, $inputId)
    {
        $state = $this->getState($formName, $inputId);
        $steps = $this->getSteps($formName);
        $readOnly = true;
        //$pageTitle = __("forms.$formName");
        $pageTitle = trans_choice('custom.impact_assessment', 1);
        $this->composeBreadcrumbs(array(['name' => __("forms.$formName"), 'url' => '']));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return view('impact_assessment.show', compact('formName', 'steps', 'state', 'readOnly', 'pageTitle', 'library'));
    }

    public function pdf($formName, $inputId)
    {
        $state = $this->getState($formName, $inputId);
        $steps = $this->getSteps($formName);
        $readOnly = true;
        $pdf = PDF::loadView('impact_assessment.pdf', compact('formName', 'steps', 'state', 'readOnly'));
        return $pdf->download("$formName.pdf");
    }

    private function getState($formName, $inputId = null)
    {
        $state = session("forms.$formName", []);
        if (!$inputId) {
            $inputId = app('request')->input('inputId', 0);
        }
        if ($inputId) {
            $item = FormInput::find($inputId);
            $state = json_decode($item->data, true);
        }
        return $state;
    }

    public static function getSteps($formName)
    {
        if ($formName == "library") {
            return 0;
        }
        return count(\File::allFiles(resource_path("views/form_partials/$formName/steps")));
    }

    /**
     * Display a list of contractors and executors
     *
     * @param Request $request
     * @return View
     */
    public function executors(Request $request)
    {
        $requestFilter = $request->all();
        $locale = currentLocale();
        $is_search = $request->has('search');
        $sort = ($request->offsetGet('sort'))
            ? $request->offsetGet('sort')
            : "DESC";
        $order_by = ($request->offsetGet('order_by'))
            ? $request->offsetGet('order_by')
            : "id";
        $sort_table = (in_array($order_by, Executor::TRANSLATABLE_FIELDS))
            ? "executor_translations"
            : "executors";
        $paginate = $request->filled('paginate') ? $request->get('paginate') : 50;
        $institutions = $request->get('institutions');
        $executor_name = $request->get('executor_name');
        $contract_subject = $request->get('contract_subject');
        $services_description = $request->get('services_description');
        $contract_date_from = $request->get('contract_date_from');
        $contract_date_till = $request->get('contract_date_till');
        $eik = $request->get('eik');
        $use_price = $request->get('use_price');
        $p_min = $request->get('p_min');
        $p_max = $request->get('p_max');

        $executors = Executor::select('executors.*')
            ->with(['translation','institution.translation'])
            ->whereLocale($locale)
            ->joinTranslation(Executor::class)
            ->when($institutions, function ($query, $institutions) {
                return $query->whereIn('institution_id', $institutions);
            })
            ->when($executor_name, function ($query, $executor_name) {
                return $query->where('executor_name', 'ILIKE', "%$executor_name%");
            })
            ->when($contract_subject, function ($query, $contract_subject) {
                return $query->where('contract_subject', 'ILIKE', "%$contract_subject%");
            })
            ->when($services_description, function ($query, $services_description) {
                return $query->where('services_description', 'ILIKE', "%$services_description%");
            })
            ->when($contract_date_from, function ($query, $contract_date_from) {
                return $query->where('contract_date', '>=', databaseDate($contract_date_from));
            })
            ->when($contract_date_till, function ($query, $contract_date_till) {
                return $query->where('contract_date', '<=', databaseDate($contract_date_till));
            })
            ->when($use_price, function ($query) use($p_min, $p_max) {
                return $query->whereRaw("((price >= $p_min AND price <= $p_max) OR price IS NULL)");
            })
            ->when($eik, function ($query, $eik) {
                return $query->where('eik', $eik);
            })
            ->whereActive(true)
            ->orderBy("$sort_table.$order_by", $sort)
            ->paginate($paginate);

        $prices = Executor::selectRaw('MAX(price) as max_price, MIN(price) as min_price')->first();

        $min_price = $prices->min_price;
        $max_price = $prices->max_price;

        if ($is_search) {
            return $this->view('impact_assessment.executors-results', compact('executors', 'requestFilter'));
        }

        $institutions = Institution::select('institution.id', 'institution_translations.name')
            ->joinTranslation(Institution::class)
            ->with('translation')
            ->whereLocale($locale)
            ->whereIn('institution.id', Executor::selectRaw('DISTINCT(institution_id)')->get()->pluck('institution_id')->toArray())
            ->orderBy('name')
            ->get();

//        $pageTitle = __('List of individuals and legal entities');
        $pageTitle = trans_choice('custom.impact_assessment', 1);
        $this->composeBreadcrumbs(array(['name' => __('List of individuals and legal entities'), 'url' => '']));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();

        return $this->view('impact_assessment.executors',
            compact('executors', 'min_price', 'max_price', 'p_min', 'p_max', 'is_search', 'paginate','pageTitle', 'institutions','library', 'requestFilter'));
    }

    public function libraryView(Request $request, $slug = ''){
        $page = Page::with(['files' => function($q) {
            $q->where('locale', '=', app()->getLocale());
        }])
        ->where('slug', '=', $slug)
        ->first();

        if(!$page){
            return back()->with('warning', __('custom.record_not_found'));
        }
        $pageTitle = trans_choice('custom.impact_assessment', 1);
//        $this->setSeo($page->meta_title, $page->meta_description, $page->meta_keyword);
        $this->setSeo($page->meta_title ?? $page->name, $page->meta_description ?? $page->short_content, $page->meta_keyword, array('title' => $page->meta_title ?? $page->name, 'img' => Page::DEFAULT_IMG));

        $this->composeBreadcrumbs(array(
            ['name' => __('custom.library'), 'url' => ''],
            ['name' => $page->name, 'url' => '']
        ));

        $library = Page::with(['translations'])
            ->where('module_enum', '=', PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value)
            ->orderBy('order_idx', 'asc')
            ->get();
        return $this->view('impact_assessment.page', compact('page', 'pageTitle', 'library'));
    }

    /**
     * @param $item
     * @param $extraItems
     * @return void
     */
    private function composeBreadcrumbs($extraItems = []){
        $customBreadcrumbs = array(
            ['name' => trans_choice('custom.impact_assessment', 1), 'url' => route('impact_assessment.index')]
        );
        if(!empty($extraItems)){
            foreach ($extraItems as $eItem){
                $customBreadcrumbs[] = $eItem;
            }
        }
        $this->setBreadcrumbsFull($customBreadcrumbs);
    }
}
