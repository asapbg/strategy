<label>
    <input type="radio" name="{{ $name }}" class="@error($name){{ 'is-invalid' }}@enderror"
        {{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }} value="{{ $value }}">
    {{ __(isset($label) ? $label : "forms.$name") }}
</label>