<h5>II. 5. {{ __('custom.forms4.text63') }}</h5>

@php( $loop = count(data_get($state, 'changes', [[]])) )
@for($a=0; $a<$loop; $a++)
<h6>{{ __('custom.forms4.text56') }} {{ $a+1 }}</h6>
<p>{{ __('custom.forms4.text64') }}</p>

<h6>А1. {{ __('custom.forms4.text65') }}</h6>
@include('form_partials.textarea', ['name' => "analysis[$a][answers_info]", 'class' => 'mb-2'])

<h6 class="mt-3">А2.	{{ __('custom.forms4.text66') }}</h6>
<p>{{ __('custom.forms4.text67') }}:</p>

<h6>§. {{ __('custom.forms4.text68') }}</h6>
<p>{{ __('custom.forms4.text69') }}:</p>
<p>
    &check; {{ __('custom.forms4.text70') }}
    <br>
    &check; {{ __('custom.forms4.text71') }}
</p>

<h6>§. {{ __('custom.forms4.text72') }}</h6>
<p>{{ __('custom.forms4.text73') }}</p>

<h6>{{ __('custom.forms4.text74') }}</h6>
<p>{{ __('custom.forms4.text75') }}</p>
<p>
    &check; {{ __('custom.forms4.text76') }}
    <br>
    &check; {{ __('custom.forms4.text77') }}
    <br>
    &check; {{ __('custom.forms4.text78') }}
</p>

<h6>{{ __('custom.forms4.text79') }}</h6>
<p>{{ __('custom.forms4.text80') }}</p>
<p>
    &check; {{ __('custom.forms4.text81') }}
    <br>
    &check; {{ __('custom.forms4.text82') }}
    <br>
    &check; {{ __('custom.forms4.text83') }}
    <br>
    &check; {{ __('custom.forms4.text84') }}
</p>

<h6>§. {{ __('custom.forms4.text85') }}</h6>
<p>{{ __('custom.forms4.text86') }}</p>

<h6>§. {{ __('custom.forms4.text87') }}</h6>
<p>{{ __('custom.forms4.text88') }}</p>

<h6>{{ __('custom.forms4.text89') }}</h6>
<p>{{ __('custom.forms4.text90') }}</p>
@include('form_partials.textarea', ['name' => "analysis[$a][results]", 'class' => 'mb-2'])

@endfor
