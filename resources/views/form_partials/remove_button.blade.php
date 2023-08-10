@if(!isset($readOnly))
<button type="button" class="btn btn-danger btn-sm float-end" onclick="$(this).closest('tr').remove()">&times;</button>
@endif