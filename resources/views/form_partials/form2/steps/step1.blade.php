<p class="@if(isset($readOnly) && $readOnly) mt-5 @endif">
    <b>
        {{ __('custom.forms2.full_evaluation_description') }}
    </b>
</p>
<h3>{{ __('custom.forms2.full_evaluation') }}</h3>

<div class="row mb-4">
    <div class="col-sm-4">
        <div>@include('form_partials.text', ['name' => 'institution'])</div>
    </div>
    <div class="col-sm-4">
        <div>@include('form_partials.text', ['name' => 'regulatory_act'])</div>
    </div>
    <div class="col-sm-4">
        <div>@include('form_partials.text', ['name' => 'period_assessment'])</div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <h5>
            {{ __('custom.forms2.action_level_need') }}
        </h5>
        @include('form_partials.radio', ['name' => 'level', 'value' => 0, 'label' => 'forms.national', 'class' => 'me-2'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 1, 'label' => 'forms.european', 'class' => 'me-2'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 2, 'label' => 'forms.national_and_european', 'class' => 'me-2'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 3, 'label' => 'forms.international', 'class' => 'me-2'])
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div>@include('form_partials.text', ['name' => 'contact_person'])</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div>@include('form_partials.text', ['name' => 'phone'])</div>
    </div>
    <div class="col-sm-6">
        <div>@include('form_partials.text', ['name' => 'email'])</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h4>1. {{ __('custom.forms2.problem_goals_action') }}</h4>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h5>1.1 {{ __('csutom.forms2.problems_to_solve') }}:</h5>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('form_partials.shared.problems')
    </div>
</div>
