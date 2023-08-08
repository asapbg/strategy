@include('form_partials.label')
@if (isset($readOnly))
<p>{{ array_key_exists($name, $state) ? $state[$name] : '' }}</p>
@else
<textarea id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm">{{ isset($value) ? $value : (array_key_exists($name, $state) ? $state[$name] : '') }}</textarea>
@endif