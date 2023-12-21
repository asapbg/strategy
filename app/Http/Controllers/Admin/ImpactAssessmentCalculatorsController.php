<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CalcTypesEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        if(in_array($type, [CalcTypesEnum::MULTICRITERIA->value])){
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
            case CalcTypesEnum::COST_EFFECTIVENESS->value:
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
                $diskont = $data['diskont'] / 100;
                $investmentCosts = $data['investment_costs'];

                $results['nvp_b'] = 0;
                $results['nvp_c'] = 0;
                foreach ($data['year'] as $key => $value){
                    //=================================
                    //** NVP
                    //=================================
                    //* регулаторните ползи на проекта в година 't'
                    // Incoming / (1 + (diskont/100))^ където '^' е на степен 't' //integer
                    $nvpB = $data['incoming'][$key] > 0 ? $data['incoming'][$key] / ((1 + ($diskont)) ** ($key + 1)) : 0;
                    $results['nvp_b'] += (int)round($nvpB, 0, PHP_ROUND_HALF_DOWN);
                    $results[$key]['nvp_b'] = (int)round($nvpB, 0, PHP_ROUND_HALF_DOWN);
                    //* регулаторните разходи на проекта в година 't' //integer
                    // Costs / (1 + (diskont/100))^ където '^' е на степен 't'
                    $nvpC = $data['costs'][$key] > 0 ? $data['costs'][$key] / ((1 + ($diskont)) ** ($key + 1)) : 0;
                    $results[$key]['nvp_c'] = (int)round($nvpC, 0, PHP_ROUND_HALF_DOWN);
                    $results['nvp_c'] += (int)round($nvpC, 0, PHP_ROUND_HALF_DOWN);
                }
                $results['nvp_c'] = $results['nvp_c'] + $investmentCosts;
                //=================================
                //** NVP нетна настояща стойност
                //=================================
                $results['nvp'] = $results['nvp_b'] - $results['nvp_c'];
                $results['nvp_result'] = $results['nvp'] > 0 ? 'Атрактивен' : ( $results['nvp'] < 0 ? ' Неатрактивен' : 'Граничен случай');
                $results['nvp_result_class'] = $results['nvp'] > 0 ? 'success' : ( $results['nvp'] < 0 ? 'danger' : 'secondary');
                //=================================
                //** BCR съотношение „ползи / разходи“
                //=================================
                $results['bcr'] = round(($results['nvp_b'] / $results['nvp_c']), 2);
                $results['bcr_result'] = $results['bcr'] > 1 ? 'Атрактивен' : ( $results['bcr'] < 1 ? ' Неатрактивен' : 'Граничен случай');
                $results['bcr_result_class'] = $results['bcr'] > 1 ? 'success' : ( $results['bcr'] < 1 ? 'danger' : 'secondary');
                //=================================
                //** Сравняване на анюализираните стойности на разходите и ползите.
                //=================================
                //* CRF -  капиталовъзстановителният фактор
                //((diskont/100) * ((1 + (diskont/100)) ** (Y - 1)) / ((1+(diskont/100) ** Y) -1))
                $y = sizeof($data['year']) + 1;
                $results['y'] = sizeof($data['year']) + 1;
                $crf = ($diskont * ((1 + $diskont) ** ($y - 1)) / (((1+$diskont) ** $y) -1));
                $results['crf'] = round($crf, 4);
                //* AVC = PVC * CRF(round to 4 decimal)-  анюализираната стойност на регулаторните разходи
                //* PVC - сборът от настоящите стойности на регулаторните разходи;
                $results['pvc'] = $results['nvp_c'];
                $results['avc'] = (int)round(($results['pvc'] * $results['crf']), 0);
                //* AVB = PVB * CRF(round to 4 decimal) -  анюализираната стойност на регулаторните ползи
                //* PVB - сборът от настоящите стойности на регулаторните ползи;
                $results['pvb'] = $results['nvp_b'];
                $results['avb'] = (int)round(($results['pvb'] * $results['crf']), 0);
                $results['compare_result'] = $results['avb'] > $results['avc'] ? 'Атрактивен' : ( $results['avb'] < $results['avc'] ? ' Неатрактивен' : 'Граничен случай');
                $results['compare_result_class'] = $results['avb'] > $results['avc'] ? 'success' : ( $results['avb'] < $results['avc'] ? 'danger' : 'secondary');
                break;
            case CalcTypesEnum::COST_EFFECTIVENESS->value:
                $diskont = $data['diskont'] / 100;
                $investmentCosts = $data['investment_costs'];

                $results['nvp_b'] = 0;
                $results['nvp_c'] = 0;
                foreach ($data['year'] as $key => $value){
                    //=================================
                    //** NVP
                    //=================================
                    //* регулаторните ползи на проекта в година 't'
                    // Incoming / (1 + (diskont/100))^ където '^' е на степен 't' //integer
                    $nvpB = $data['incoming'][$key] > 0 ? $data['incoming'][$key] / ((1 + ($diskont)) ** ($key + 1)) : 0;
                    $results['nvp_b'] += (int)round($nvpB, 0, PHP_ROUND_HALF_DOWN);
                    $results[$key]['nvp_b'] = (int)round($nvpB, 0, PHP_ROUND_HALF_DOWN);
                    //* регулаторните разходи на проекта в година 't' //integer
                    // Costs / (1 + (diskont/100))^ където '^' е на степен 't'
                    $nvpC = $data['costs'][$key] > 0 ? $data['costs'][$key] / ((1 + ($diskont)) ** ($key + 1)) : 0;
                    $results[$key]['nvp_c'] = (int)round($nvpC, 0, PHP_ROUND_HALF_DOWN);
                    $results['nvp_c'] += (int)round($nvpC, 0, PHP_ROUND_HALF_DOWN);
                }
                $results['nvp_c'] = $results['nvp_c'] + $investmentCosts;
                //=================================
                //** CER съотношение „разходи / ефективност (ползи / разходи)“
                //=================================
                //** CER = B/C
                $results['cer_b_c'] = round(($results['nvp_b'] / $results['nvp_c']), 2);
                //** CER = C/B
                $results['cer_c_b'] = round(($results['nvp_c'] / $results['nvp_b']), 2);
                break;
        }
//        Log::error($results);
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
            ),
            CalcTypesEnum::COST_EFFECTIVENESS->value => array(
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
