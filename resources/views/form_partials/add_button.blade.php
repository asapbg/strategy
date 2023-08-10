@if(!isset($readOnly))
<button class="btn btn-info" type="button" onclick="$(this).after('<input type=hidden name=add_entry value={{ $name }}>'); document.forms[0].submit();">
{{ __('custom.add') }}
{{ isset($buttonLabel) ? __($buttonLabel) : '' }}
</button>
@endif