<table class="table table-sm sm-text table-bordered table-hover">
    <thead>
    <tr>
        <th colspan="3">{{ __('custom.dynamic_structures.type.CONSULT_DOCUMENTS') }}</th>
    </tr>
    </thead>
    <tbody>
    @php($foundedGroups = [])
    @if(isset($kdRows) && sizeof($kdRows))
        @foreach($kdRows as $i => $row)
            @php($currentGroup = $row->dynamic_structure_groups_id ? $row->group : null)
            @if($row->dynamic_structure_groups_id && !in_array($row->dynamic_structure_groups_id, $foundedGroups))
                @php($foundedGroups[] = $row->dynamic_structure_groups_id)
                <tr>
                    <td colspan="3">{{ $row->group->ord }}. {{ $row->group->label }}</td>
                </tr>
            @endif

            @php($value = isset($kdValues) && sizeof($kdValues) ? $kdValues[$row->id] ?? '' : '')
            @if($row->dynamic_structure_groups_id)
                <tr>
                    <td>{{ ($currentGroup ? $currentGroup->ord.'.' : '').$row->ord }}</td>
                    <td>{{ $row->label }}</td>
                    <td style="min-width: 600px;">{{ $value }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2">{{ $row->ord.'. '.$row->label }}</td>
                    <td style="min-width: 600px;">{{ $value }}</td>
                </tr>
            @endif
        @endforeach
    @endif
    </tbody>
</table>
