<div class="row mb-5">
    <div class="col-sm-12">
        <h4>7. {{ __('custom.forms1.consultations') }}:</h4>
        @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 1, 'label' => 'forms.conducted_consultations', 'clickSubmit' => true])
        <br>
        @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 0, 'label' => 'forms.not_conducted_consultations', 'clickSubmit' => true])
        @if((Arr::has($state, 'conducted_consultations') && data_get($state, 'conducted_consultations') == 0) || old('conducted_consultations', 1) == 0)
        {!! __('custom.forms1.consultations_description') !!}
            @include('form_partials.textarea', ['name' => 'not_conducted_consultations_text', 'label' => ''])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>8. {{ __('custom.forms1.act') }}</h4>
        @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true])
        <br>
        @if((Arr::has($state, 'is_from_eu') && data_get($state, 'is_from_eu') == 1) || old('is_from_eu', 0) == 1)
        {!! __('custom.forms1.act_description') !!}
            @include('form_partials.textarea', ['name' => 'is_from_eu_text', 'label' => '', 'class' => 'mb-2'])
        @endif
        @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>9. {{ __('custom.forms1.full_evaluation') }}</h4>
        {!! __('custom.forms1.full_evaluation_description') !!}
        @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 1, 'label' => 'forms.yes'])
        <br>
        @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 0, 'label' => 'forms.no'])
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>10. {{ __('custom.forms1.applications') }}:</h4>
        {!! __('custom.forms1.applications_description') !!}
        @include('form_partials.textarea', ['name' => 'applications', 'label' => '', 'placeholder' => 'forms.field_required'])

        <h4 class="mt-3">11. {{ __('custom.forms1.source_information') }}:</h4>
        {!! __('custom.forms1.applications_description') !!}
        @include('form_partials.textarea', ['name' => 'info_sources', 'label' => '', 'placeholder' => 'forms.field_required'])
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>12. {{ __('custom.forms1.name') }}:</h4>
        <div class="col-md-4">
            @include('form_partials.text', ['name' => 'name', 'label' => 'forms.name'])
        </div>
        <div class="col-md-4">
            @include('form_partials.text', ['name' => 'job', 'label' => 'forms.job'])
        </div>
        <div class="col-md-4">
            @include('form_partials.date', ['name' => 'date', 'label' => 'forms.date'])
        </div>
    </div>
</div>
