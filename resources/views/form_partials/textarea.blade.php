@if(isset($label) && $label)
    @include('form_partials.label')
@endif
@php
if (!isset($nameDots)) {
    $nameDots = str_replace('[', '.', $name);
    $nameDots = str_replace(']', '', $nameDots);
}
$value = old('', isset($value) ? $value : (\Arr::has($state, $nameDots) ? data_get($state, $nameDots) : ''));
@endphp
@if (isset($readOnly))
<p class="@if(isset($pure_text_class) && $pure_text_class){{ $pure_text_class }}@endif">{{ $value }}</p>
@else
<textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}" class="form-control form-control-sm @if(isset($class)) {{ $class }} @endif @error($nameDots){{ 'is-invalid' }}@enderror">{{ $value }}</textarea>
@error($nameDots)
    <div class="text-danger input-err">{{ $message }}</div>
@enderror
@endif
