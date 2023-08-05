@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
@for ($a=0; $a<$loop; $a++)
<div>
    @php($label = __($buttonLabel) . ' ' . $a+1)
    @include('form_partials.textarea', ['value' => array_key_exists($nameSimple, $state) ? $state[$nameSimple][$a] : ''])
</div>
@endfor
@include('form_partials.add_button')