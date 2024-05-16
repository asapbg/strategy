@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>3.{{ $n+1 }}. {{ __('custom.forms.to_problem') }} {{ $n+1 }}:</h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">{{ __('custom.forms2.action_variant') }}</div>
    <div class="col-sm-4">{{ __('custom.forms.total_year_minus') }}</div>
    <div class="col-sm-4">{{ __('custom.forms.total_year_plus') }}</div>
</div>
@php($loop2 = Arr::get($state, "variants.$n", false) ? count(Arr::get($state, "variants.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-4">
        <h6>{{ __('custom.forms.variant') }} {{ $m+1 }}:</h6>
    </div>
    <div class="col-sm-4">
        @include('form_partials.textarea', ['name' => "expenses[$n][$m][expenses]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.expenses")])
    </div>
    <div class="col-sm-4">
        @include('form_partials.textarea', ['name' => "expenses[$n][$m][benefits]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.benefits")])
    </div>
</div>
@endfor
<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        {!! __('custom.forms.text6') !!}
    </div>
</div>
@endfor
