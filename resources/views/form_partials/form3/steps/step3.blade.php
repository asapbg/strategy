<h4>5. {{ __('custom.forms3.text7') }}</h4>

<h5>5.1. {{ __('custom.forms3.text8') }}</h5>
<p><i>{{ __('custom.forms3.text9') }}</i></p>
@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
    <div class="row">
        <div class="col-sm-12">
            <h6 class="mt-3">5.1.{{ $n+1 }}. {{ __('custom.problem') }} {{ $n+1 }}:</h6>
        </div>
    </div>
    <table width="100%" class="table">
    <thead>
        <tr>
            <td></td>
            <td class="text-center">{{ __('forms.economic_impacts') }}</td>
            <td class="text-center">{{ __('forms.social_impact') }}</td>
            <td class="text-center">{{ __('forms.ecologic_impact') }}</td>
            <td class="text-center">{{ __('forms.specific_impact') . ' 1' }}</td>
            <td class="text-center">{{ __('forms.specific_impact') . ' 2' }}</td>
        </tr>
    </thead>
    @php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
        @for($m=0; $m<$loop2; $m++)
        <tr>
            <td>
                <h6>{{ __('custom.forms.variant') }} {{ $m+1 }}:</h6>
            </td>
            <td class="px-4">
                @include('form_partials.textarea', ['name' => "variants[$n][$m][economic_impacts]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.description"), 'pure_text_class' => 'text-center'])
            </td>
            <td class="px-4">
                @include('form_partials.textarea', ['name' => "variants[$n][$m][social_impact]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.positive_impact"), 'pure_text_class' => 'text-center'])
            </td>
            <td class="px-4">
                @include('form_partials.textarea', ['name' => "variants[$n][$m][ecologic_impact]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.negative_impact"), 'pure_text_class' => 'text-center'])
            </td>
            <td class="px-4">
                @include('form_partials.textarea', ['name' => "variants[$n][$m][specific_impact_1]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.small_mid_impact"), 'pure_text_class' => 'text-center'])
            </td>
            <td class="px-4">
                @include('form_partials.textarea', ['name' => "variants[$n][$m][specific_impact_2]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.admin_weight"), 'pure_text_class' => 'text-center'])
            </td>
        </tr>
    @endfor
    </table>
@endfor

<h4 class="mt-5">5.2. {{ __('custom.forms3.text10') }}</h4>
{!!  __('custom.forms3.text11') !!}
@include('form_partials.textarea', ['name' => 'quality_assessment', 'label' => '', 'value' => Arr::get($state, 'quality_assessment'), 'class' => 'mb-2'])
