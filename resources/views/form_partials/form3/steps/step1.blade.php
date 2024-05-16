<p>
    <b>{{ __('custom.forms3.text1') }}</b>
</p>
<h3>{{ __('forms.form3') }}</h3>

<h4 class="mt-3">1. {{ __('forms.problem_to_solve') }}</h4>
<div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="mt-3">1. {{ __('forms.problem_to_solve') }}</h5>
        {!! __('custom.forms3.text2') !!}
        @include('form_partials.array_textarea', ['name' => 'problem_to_solve[]', 'buttonLabel' => 'custom.problem'])
    </div>
</div>

<h4>2. {{ __('forms.interested_parties') }}</h4>
{!! __('custom.forms3.text3') !!}
@include('form_partials.array_textarea_number', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party', 'class_textarea' => 'summernote'])
