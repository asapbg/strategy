<?php

namespace App\Http\Controllers;

use App\Models\Executor;
use App\Models\FormInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use PDF;

class ImpactAssessmentController extends Controller
{
    public function index()
    {
        $pageTitle = trans_choice('custom.impact_assessment', 2);
        return $this->view('impact_assessment.index', compact('pageTitle'));
    }

    public function forms()
    {
        $pageTitle = __('site.impact_assessment.forms_and_templates');
        return $this->view('impact_assessment.forms', compact('pageTitle'));
    }

    public function form($formName, Request $request)
    {
        $state = $this->getState($formName);
        $step = $request->input('step', 1);
        $steps = $this->getSteps($formName);
        $inputId = $request->input('inputId', 0);
        $pageTitle = $formName ? __("forms.$formName") : trans_choice('custom.impact_assessment', 2);
        //dd(session()->all());
        return $this->view('site.impact_assessment', compact('pageTitle', 'formName', 'state', 'step', 'steps', 'inputId'));
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

        if (!$isDataEmpty && (($userId && !$inputId) || $submit)) {
            $fi = FormInput::find($inputId);
            if ($fi) {
                $state = $this->getState($formName, $inputId);
            } else {
                $fi = new FormInput([
                    'form' => $formName,
                    'user_id' => $userId,
                ]);
            }
            $fi->data = json_encode($data);
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
                    ->withErrors($validator->errors());
            }
        }

        if ($submit) {
            session(["forms.$formName" => []]);
            return view('impact_assessment.submitted', compact('formName', 'inputId'));
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
        $pageTitle = __("forms.$formName");
        return view('impact_assessment.show', compact('formName', 'steps', 'state', 'readOnly', 'pageTitle'));
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
        if (!$inputId) $inputId = app('request')->input('inputId', 0);
        if ($inputId) {
            $item = FormInput::find($inputId);
            $state = json_decode($item->data, true);
        }
        return $state;
    }

    public static function getSteps($formName)
    {
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
        $paginate = $request->filled('paginate') ? $request->get('paginate') : 5;
        $contractor_name = $request->get('contractor_name');
        $executor_name = $request->get('executor_name');
        $contract_subject = $request->get('contract_subject');
        $services_description = $request->get('services_description');
        $contract_date = $request->get('contract_date');

        $executors = Executor::joinTranslation(Executor::class)
            ->whereLocale(app()->getLocale())
            ->when($contractor_name, function ($query, $contractor_name) {
                return $query->where('contractor_name', 'ILIKE', "%$contractor_name%");
            })
            ->when($contractor_name, function ($query, $contractor_name) {
                return $query->where('contractor_name', 'ILIKE', "%$contractor_name%");
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
            ->when($contract_date, function ($query, $contract_date) {
                return $query->where('contract_date', databaseDate($contract_date));
            })
            ->whereActive(true)
            ->orderBy('executors.id', 'desc')
            ->paginate($paginate);

        return $this->view('impact_assessment.executors', compact('executors'));
    }
}
