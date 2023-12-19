@php($label = isset($label) ? $label : "forms.$name")
@if(trans()->has($label))
<label for="{{ $name }}" class="mb-2 mt-2 form-label fw-bold">{{ __($label) }}:</label>
@endif
