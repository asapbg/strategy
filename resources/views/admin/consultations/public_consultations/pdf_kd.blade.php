<div class="row">
    <div class="col-12">{{ __('custom.dynamic_structures.type.CONSULT_DOCUMENTS') }}</div>
</div>
@php($foundedGroups = [])
@if(isset($kdRows) && sizeof($kdRows))
    @foreach($kdRows as $i => $row)
        <div class="row">
            @php($currentGroup = $row->dynamic_structure_groups_id ? $row->group : null)
            @if($row->dynamic_structure_groups_id && !in_array($row->dynamic_structure_groups_id, $foundedGroups))
                @php($foundedGroups[] = $row->dynamic_structure_groups_id)
                <div class="col-12">{{ $row->group->ord }}. {{ $row->group->label }}</div>
            @endif

            @php($value = isset($kdValues) && sizeof($kdValues) ? $kdValues[$row->id] ?? '' : '')
            @if($row->dynamic_structure_groups_id)
                <div class="col-2">
                    {{ ($currentGroup ? $currentGroup->ord.'.' : '').$row->ord }}
                </div>
                <div class="col-4">
                    {{ $row->label }}
                </div>
                <div class="col-6">
                    {!! $value !!}
                </div>
            @else
                <div class="col-6">
                    {{ $row->ord.'. '.$row->label }}
                </div>
                <div class="col-6">
                    {!! $value !!}
                </div>
            @endif
        </div>
    @endforeach
@endif
