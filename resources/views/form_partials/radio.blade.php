<label @error($name) class="text-danger"@enderror onclick="{{ isset($clickSubmit) ? '$(\'#ia-form\').submit();' : '' }}">
    <input type="radio" name="{{ $name }}" @if(isset($readOnly) && $readOnly){{ ' disabled ' }}@endif class="@error($name){{ 'is-invalid' }}@enderror"
        {{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }} value="{{ $value }}">
    {{ __(isset($label) ? $label : "forms.$name") }}
</label>
