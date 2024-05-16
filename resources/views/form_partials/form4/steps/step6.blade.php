<h5>II. 6. {{ __('custom.forms4.text91') }}</h5>

@php( $loop = count(data_get($state, 'changes', [[]])) )
@for($a=0; $a<$loop; $a++)
    <h6>{{ __('custom.forms4.text21') }} {{ $a+1 }}</h6>
    <p>{{ __('custom.forms4.text92') }}</p>
    @include('form_partials.textarea', ['name' => "impact[$a][conclusion]", 'class' => 'mb-3'])
@endfor

<h5 class="mt-3">II. 7. {{ __('custom.forms4.text93') }}</h5>
@include('form_partials.textarea', ['name' => 'conclusions', 'class' => 'mb-2'])

<h5 class="mt-3">II. 8. {{ __('custom.forms4.text94') }}</h5>
<p>{{ __('custom.forms4.text95') }}</p>
<p>{{ __('custom.forms4.text96') }}</p>
@include('form_partials.textarea', ['name' => 'distribution', 'class' => 'mb-2'])

<h5 class="mt-3">II. 9. {{ __('custom.forms4.text97') }}</h5>
<p>{{ __('custom.forms4.text98') }}</p>
@include('form_partials.textarea', ['name' => 'recommendations', 'class' => 'mb-2'])

<h5 class="mt-3">II. 10.	{{ __('custom.forms4.text99') }}</h5>
<p>{{ __('custom.forms4.text100') }}</p>
@include('form_partials.textarea', ['name' => 'sources', 'class' => 'mb-2'])

<h5 class="mt-3">III. {{ __('custom.forms1.applications') }}</h5>
<p>{{ __('custom.forms4.text101') }}</p>
@include('form_partials.textarea', ['name' => 'applications', 'class' => 'mb-2'])
