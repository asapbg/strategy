@if(!isset($readOnly))
<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">Премахни</button>
@endif
