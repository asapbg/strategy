@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>2.{{ $n+1 }}. {{ __('custom.forms.to_problem') }} {{ $n+1 }}:</h5>
    </div>
</div>
@php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('custom.forms.variant') }} {{ $m+1 }}:</h6>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][description]", 'label' => 'forms.description', 'value' => Arr::get($state, "variants.$n.$m.description"), 'class' => 'mb-3'])

        @include('form_partials.textarea', ['name' => "variants[$n][$m][positive_impact]", 'label' => 'forms.positive_impact_3years', 'value' => Arr::get($state, "variants.$n.$m.positive_impact")])
        <p class="mb-3"><i>{{ __('custom.forms.text1') }}</i></p>

        @include('form_partials.textarea', ['name' => "variants[$n][$m][negative_impact]", 'label' => 'forms.negative_impact_3years', 'value' => Arr::get($state, "variants.$n.$m.negative_impact")])
        <p class="mb-3"><i>{{ __('custom.forms.text1') }}</i></p>

        <h6>{{ __('custom.forms.text2') }}</h6>
        @include('form_partials.textarea', ['name' => "variants[$n][$m][small_mid_impact]", 'label' => 'forms.small_mid_impact', 'value' => Arr::get($state, "variants.$n.$m.small_mid_impact")])
        {!! __('custom.forms.text3') !!}

        @include('form_partials.textarea', ['name' => "variants[$n][$m][admin_weight]", 'label' => 'forms.admin_weight', 'value' => Arr::get($state, "variants.$n.$m.admin_weight")])
        {!! __('custom.forms.text4') !!}

        @include('form_partials.textarea', ['name' => "variants[$n][$m][risks]", 'label' => 'forms.potential_risks', 'value' => Arr::get($state, "variants.$n.$m.risks")])
        {!! __('custom.forms.text5') !!}
    </div>
</div>
@endfor
@endfor
