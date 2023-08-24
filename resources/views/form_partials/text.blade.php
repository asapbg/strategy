@include('form_partials.label')
@php
if (!isset($nameDots)) {
    $nameDots = str_replace('[', '.', $name);
    $nameDots = str_replace(']', '', $nameDots);
}
@endphp
@if (isset($readOnly))
    <p>{{ array_key_exists($name, $state) ? $state[$name] : '' }} </p>
@else
<input type="{{ isset($type) ? $type : 'text' }}" id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm @error($nameDots){{ 'is-invalid' }}@enderror"
    value="{{ isset($value) ? $value : (array_key_exists($name, $state) ? $state[$name] : '') }}">
@endif