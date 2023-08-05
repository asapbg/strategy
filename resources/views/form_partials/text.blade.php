@include('form_partials.label')
<input type="text" id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm"
    value="{{ array_key_exists($name, $state) ? $state[$name] : '' }}">