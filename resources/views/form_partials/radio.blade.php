<label class="@error($name) text-danger @enderror @if(isset($class)){{ $class }}@endif" onclick="{{ isset($clickSubmit) ? '$(\'#ia-form\').submit();' : '' }}">
    <input type="radio" name="{{ $name }}" @if(isset($readOnly) && $readOnly){{ ' disabled ' }}@endif class="@error($name){{ 'is-invalid' }}@enderror"
{{--         @if(old($name, 0) == $value) checked @else{{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }}@endif value="{{ $value }}">--}}
{{ array_key_exists($name, $state) ? ($state[$name] == $value ? 'checked' : '') : '' }} value="{{ $value }}">
    {{ __(isset($label) ? $label : "forms.$name") }}
</label>
