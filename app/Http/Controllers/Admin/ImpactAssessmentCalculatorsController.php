<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CalcTypesEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ImpactAssessmentCalculatorsController extends Controller
{
    public function tools()
    {
        $pageTitle = __('site.impact_assessment.tools');
        return $this->view('impact_assessment.tools', compact('pageTitle'));
    }

    public function calc(Request $request, $type)
    {
        $pageTitle = __('site.calc.'.$type.'.title');
        if($request->isMethod('get')){
            return $this->view('impact_assessment.calc', compact('pageTitle', 'type'));
        }

        Session::forget('old');
        $rv = Validator::make($request->all(), $this->validationRules($type));
        if($rv->fails()){
            return redirect(route('impact_assessment.tools.calc', $type))->with('old', $request->all())->withErrors($rv->errors());
        }
        $validated = $rv->validated();
        if($type = CalcTypesEnum::COSTS_AND_BENEFITS->value){
            return redirect(route('impact_assessment.tools.calc', $type))->with('old', $request->all())->with('warning', 'Изчисленията са в процес на разработка');
        }
        $results = $this->methodCalculation($type, $validated);
        return redirect(route('impact_assessment.tools.calc', $type))->with('old', array_merge($request->all(), ['results' => $results]));
    }

    public function templates($type)
    {
        switch ($type)
        {
            case CalcTypesEnum::STANDARD_COST->value:
                $view = 'impact_assessment.calcs.'.$type.'.activity_block';
                break;
            case CalcTypesEnum::COSTS_AND_BENEFITS->value:
                $view = 'impact_assessment.calcs.'.$type.'.year_block';
                break;
            default:
                $view = '';
        }
        $returnHTML = view($view)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    private function methodCalculation(string $type, array $data, int $step = 1){
        $results = [];
        switch ($type){
            case CalcTypesEnum::STANDARD_COST->value:
                foreach ($data['items'] as $key => $value){
                    //((Salaray / 170 (hours per month)) * Hours) * (Firms * Per_year)
                    $val = number_format(((($data['salary'][$key] / 170) * $data['hours'][$key]) * ($data['firms'][$key] * $data['per_year'][$key])), 2, '.', '');
                    $results[$key] = [
                        'full' => trans_choice('custom.results', 1).':'.' '. $val . ' лв',
                        'pure_num' => $val
                    ];
                }
                break;
            case CalcTypesEnum::COSTS_AND_BENEFITS->value:
                $diskont = $data['items'];
                $investmentCosts = $data['investment_costs'];
                foreach ($data['year'] as $key => $value){
//                    //((Salaray / 170 (hours per month)) * Hours) * (Firms * Per_year)
//                    $val = number_format(((($data['salary'][$key] / 170) * $data['hours'][$key]) * ($data['firms'][$key] * $data['per_year'][$key])), 2, '.', '');
//                    $results[$key] = [
//                        'full' => trans_choice('custom.results', 1).':'.' '. $val . ' лв',
//                        'pure_num' => $val
//                    ];
                }
                break;
        }
        return $results;
    }

    private function validationRules(string $type, int $step = 1){
        $rules = array(
            CalcTypesEnum::STANDARD_COST->value => array(
                1 => [
                    'items' => ['required', 'array'],
                    'items.*' => ['required', 'string', 'min:1'],
                    'hours' => ['required', 'array'],
                    'hours.*' => ['required', 'integer', 'min:1'],
                    'salary' => ['required', 'array'],
                    'salary.*' => ['required', 'numeric', 'gt:0'],
                    'firms' => ['required', 'array'],
                    'firms.*' => ['required', 'integer', 'min:1'],
                    'per_year' => ['required', 'array'],
                    'per_year.*' => ['required', 'integer', 'min:1'],
                ]
            ),
            CalcTypesEnum::COSTS_AND_BENEFITS->value => array(
                1 => [
                    'diskont' => ['required', 'numeric', 'gt:0'],
                    'investment_costs' => ['required', 'numeric', 'min:0'],
                    'year' => ['required', 'array'],
                    'year.*' => ['nullable'],
                    'incoming' => ['required', 'array'],
                    'incoming.*' => ['required', 'numeric', 'min:0'],
                    'costs' => ['required', 'array'],
                    'costs.*' => ['required', 'numeric', 'min:0'],
                ]
            )
        );

        return isset($rules[$type]) ? ($rules[$type][$step] ?? []) : [];
    }
}
