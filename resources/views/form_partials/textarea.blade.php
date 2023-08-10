@include('form_partials.label')
@php
if (!isset($nameDots)) {
    $nameDots = str_replace('[', '.', $name);
    $nameDots = str_replace(']', '', $nameDots);
}
@endphp
@if (isset($readOnly))
<p>{{ array_key_exists($name, $state) ? $state[$name] : '' }}</p>
@else
<textarea id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm @error($nameDots){{ 'is-invalid' }}@enderror">{{ isset($value) ? $value : (array_key_exists($name, $state) ? $state[$name] : '') }}</textarea>
@endif