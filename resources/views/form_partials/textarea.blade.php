@if(isset($label) && $label)
    @include('form_partials.label')
@endif
@php
if (!isset($nameDots)) {
    $nameDots = str_replace('[', '.', $name);
    $nameDots = str_replace(']', '', $nameDots);
}
$value = isset($value) ? $value : (array_key_exists($name, $state) ? $state[$name] : '');
@endphp
@if (isset($readOnly))
<p>{{ $value }}</p>
@else
<textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}" class="form-control form-control-sm @error($nameDots){{ 'is-invalid' }}@enderror">{{ $value }}</textarea>
@endif