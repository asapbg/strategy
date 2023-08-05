<label>
    <input type="radio" name="{{ $name }}"
        {{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }} value="{{ $value }}">
    @include('form_partials.label')
</label>