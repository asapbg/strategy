@if(isset($label) && $label)
    @include('form_partials.label')
@endif
@php
if (!isset($nameDots)) {
    $nameDots = str_replace('[', '.', $name);
    $nameDots = str_replace(']', '', $nameDots);
}
$value = isset($value) ? $value : (\Arr::has($state, $nameDots) ? data_get($state, $nameDots) : '');
@endphp
@if (isset($readOnly))
<p>{{ $value }}</p>
@else
<textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}" class="form-control form-control-sm @error($nameDots){{ 'is-invalid' }}@enderror">{{ $value }}</textarea>
@endif