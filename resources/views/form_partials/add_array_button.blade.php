@if(!isset($readOnly))
<button class="btn btn-primary mb-2 mt-2" type="button" onclick="$(this).after('<input type=hidden name=add_array_entry value={{ $name }}>'); document.forms[0].submit();">
    {{ __('custom.add') }}
    {{ isset($buttonLabel) ? __($buttonLabel) : '' }}
</button>
@endif