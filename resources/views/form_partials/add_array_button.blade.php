@if(!isset($readOnly))
<button type="button" onclick="$(this).after('<input type=hidden name=add_array_entry value={{ $name }}>'); document.forms[0].submit();">
    {{ __('custom.add') }}
    {{ isset($buttonLabel) ? __($buttonLabel) : '' }}
</button>
@endif