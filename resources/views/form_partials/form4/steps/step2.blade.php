<h5>I. {{ __('custom.forms4.text11') }}</h5>
<p>{{ __('custom.forms4.text12') }}</p>
@include('form_partials.textarea', ['name' => 'resume'])

<h5 class="mt-3">II.	{{ __('custom.forms4.text13') }}</h5>
<p class="text-danger">
    <i>{{ __('custom.forms4.text14') }}</i>
</p>

<h5 class="mt-3">II. 1. {{ __('custom.forms4.text15') }}</h5>
<p>{{ __('custom.forms4.text16') }}</p>
@include('form_partials.textarea', ['name' => 'introduction'])

<h5 class="mt-3">II. 2. {{ __('custom.goals') }}</h5>
{!! __('custom.forms4.text17') !!}
{!! __('custom.forms4.text18') !!}
@include('form_partials.array_textarea', ['name' => 'goals[]', 'buttonLabel' => 'forms.goal'])

<h5 class="mt-3">II. 3. {{ __('custom.forms4.text19') }} </h5>
@include('form_partials.textarea', ['name' => 'scope_and_structure'])
{!! __('custom.forms4.text20') !!}
