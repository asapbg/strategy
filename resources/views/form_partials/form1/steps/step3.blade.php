<div class="row">
    <div class="col-sm-12">
        <h4>6. {{ __('custom.forms1.select_variant') }}:</h4>
    </div>
</div>
@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
<div class="row">
    <div class="col-sm-12">
        {!! __('custom.forms1.select_variant_description') !!}
    </div>
</div>
@for($n=0; $n<$loop; $n++)
    <div class="row @if($n) mt-3 @endif">
        <div class="col-md-3">
            <h5>{{ __('forms.on_problem') . ' ' . $n+1 . ': ' . __('forms.variant') }}</h5>
        </div>
        <div class="col-md-9">
            @include('form_partials.text', ['name' => 'chosen_variants[]', 'label' => '', 'nameDots' => "chosen_variants.$n", 'value' => old("chosen_variants.$n", data_get($state, "chosen_variants.$n"))])
        </div>
    </div>
@endfor
<div class="row mb-5">
    <div class="col-sm-12 mt-5">
        <h4>6.1. {{ __('custom.forms1.change_administration_weight') }}:</h4>
        {!! __('custom.forms1.change_administration_weight_description') !!}
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 0, 'label' => 'forms.will_increase', 'clickSubmit' => true])
        <br>
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 1, 'label' => 'forms.will_decrease', 'clickSubmit' => true])
        <br>
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 2, 'label' => 'forms.no_effect', 'clickSubmit' => true])
        @if((Arr::has($state, 'change_admin_weight') && in_array(data_get($state, 'change_admin_weight'), [0, 1])) || in_array(old('change_admin_weight'), [0,1]))
            <br>
            @include('form_partials.textarea', ['name' => 'change_admin_weight_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.2. {{ __('custom.forms1.step3.create_services') }}</h4>
        {!! __('custom.forms1.step3.create_services_description') !!}
        <div>
            @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true, 'class' => 'me-3'])
            @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
        </div>

        @if((Arr::has($state, 'affects_regulatory_acts') && data_get($state, 'affects_regulatory_acts') == 1) || old('affects_regulatory_acts') == 1)
            <br>
            @include('form_partials.textarea', ['name' => 'affects_regulatory_acts_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.3. {{ __('custom.forms1.step3.create_variants') }}</h4>
        {!!  __('custom.forms1.step3.create_variants_description') !!}
        <div>
            @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true, 'class' => 'me-3'])
            @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
        </div>

        @if((Arr::has($state, 'affects_registry') && data_get($state, 'affects_registry') == 1)  || old('affects_registry') == 1)
            <br>
            @include('form_partials.textarea', ['name' => 'affects_registry_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12  mt-4">
        <h4>6.4. {{ __('custom.forms1.step3.create_micro') }}</h4>
        {!!  __('custom.forms1.step3.create_micro_description') !!}
        @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 1, 'label' => 'forms.act_affects_smc'])
        <br>
        @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 0, 'label' => 'forms.act_not_affects_smc'])
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.5. {{ __('custom.forms1.step3.risk') }}:</h4>
        {!! __('custom.forms1.step3.risk_description') !!}
        @include('form_partials.textarea', ['name' => 'potential_risks', 'label' => ''])
    </div>
</div>
