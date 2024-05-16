<p>
    <b>{{ __('custom.part_before_evaluation_description') }}</b>
</p>
<h3>{{ __('custom.part_before_evaluation') }}</h3>
<div>
    <div class="row">
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'institution'])</h5>
        </div>
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'regulatory_act'])</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 0, 'label' => 'forms.not_included_in_program'])
        </div>
        <div class="col-sm-6">
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 1, 'label' => 'forms.included_in_program'])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h5>@include('form_partials.text', ['name' => 'contact_person'])</h5>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'phone'])</h5>
        </div>
        <div class="col-sm-6">
            <h5>@include('form_partials.text', ['name' => 'email'])</h5>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-12">
            <h4>1. {{ __('forms.problem_to_solve') }}</h4>
            {!! __('custom.forms1.problem_to_solve') !!}
            @include('form_partials.shared.problems')
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-12">
            <h4>2. {{ __('forms.goal_goals') }}</h4>
            {!! __('custom.forms.goals') !!}
            @include('form_partials.shared.goals')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h4>3. {{ __('forms.interested_parties') }}</h4>
            {!! __('custom.forms.interested_groups') !!}
            @include('form_partials.array_textarea_number', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party', 'class_textarea' => 'summernote'])
        </div>
    </div>
</div>
