<div class="row">
    <div class="col-sm-12">
        <h4>3. {{ __('custom.forms2.text1') }}</h4>
        @include('form_partials.shared.expenses')

        <h4>4. {{ __('custom.forms2.text2') }}</h4>
        @include('form_partials.textarea', ['name' => "consultations", 'label' => 'forms.consultations', 'value' => Arr::get($state, 'consultations')])
        {!! __('custom.forms2.text3') !!}

        <h4 class="mt-5">5. {{ __('custom.forms2.text4') }}</h4>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        @include('form_partials.text', ['name' => 'effective_from', 'type' => 'text', 'label' => 'forms.effective_from', 'value' => Arr::get($state, 'effective_from'), 'class' => 'datepicker'])
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('custom.forms2.text5') }}</h6>
        @include('form_partials.textarea', ['name' => 'responsibility', 'value' => Arr::get($state, 'responsibility')])
        {!! __('custom.forms2.text6') !!}
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-12">
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
