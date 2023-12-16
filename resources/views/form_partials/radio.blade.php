<label @error($name) class="text-danger"@enderror onclick="{{ isset($clickSubmit) ? '$(\'#ia-form\').submit();' : '' }}">
    <input type="radio" name="{{ $name }}" @if(isset($readOnly) && $readOnly){{ ' disabled ' }}@endif class="@error($name){{ 'is-invalid' }}@enderror"
         @if(old($name, 0) == $value) checked @else{{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }}@endif value="{{ $value }}">
    {{ __(isset($label) ? $label : "forms.$name") }}
</label>
