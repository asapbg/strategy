@include('form_partials.label')
@if (isset($readOnly))
    <p>{{ array_key_exists($name, $state) ? $state[$name] : '' }} </p>
@else
<input type="text" id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm"
    value="{{ array_key_exists($name, $state) ? $state[$name] : '' }}">
@endif