<h5>II. 4. {{ __('custom.forms4.text54') }}</h5>
<p>{{ __('custom.forms4.text55') }}</p>

@php( $loop = count(data_get($state, 'changes', [[]])) )
@for($a=0; $a<$loop; $a++)
<h6>{{ __('custom.forms4.text56') }} {{ $a+1 }}</h6>

<h6>1. {{ __('custom.forms4.text57') }}</h6>
<p>{{ __('custom.forms4.text58') }}</p>
@include('form_partials.textarea', ['name' => "collected_data[$a][interested_parties]", 'class' => 'mb-3'])

<h6>2. {{ __('custom.forms4.text59') }}</h6>
<p>{{ __('custom.forms4.text60') }}</p>
@include('form_partials.textarea', ['name' => "collected_data[$a][consultations]", 'class' => 'mb-3'])

<h6>3. {{ __('custom.forms4.text61') }}</h6>
<p>{{ __('custom.forms4.text62') }}</p>
@include('form_partials.textarea', ['name' => "collected_data[$a][sources]", 'class' => 'mb-2'])

@endfor
