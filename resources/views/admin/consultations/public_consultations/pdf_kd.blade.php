<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 22px;
    }
    table, th, td {
        border: 2px solid black;
        border-collapse: collapse;
        padding: 10px 15px;
    }
</style>
<div style="width: 100%;">
    <div style="width: 50%; border: 2px solid #000;font-size: 18px; font-weight: bold; margin-bottom: 50px;padding: 5px 10px;">
        Образецът на консултационен документ влиза в сила от 01 януари 2021 г.
    </div>
    <div>
        <table width="100%" style="border-collapse:collapse;">
            <tbody>
            <tr>
                <td colspan="2" style="background: #b7b5b5; font-weight: bold; text-align: center;">{{ mb_strtoupper(__('custom.dynamic_structures.type.CONSULT_DOCUMENTS')) }}</td>
            </tr>
                @php($foundedGroups = [])
                @php($currentGroup = 0)
                @if(isset($kdRows) && sizeof($kdRows))
                    @foreach($kdRows as $i => $row)
    {{--                        <div class="row">--}}
                                {{--            @php($currentGroup = $row->dynamic_structure_groups_id ? $row->group : null)--}}
                                @php($currentGroup = $row->dynamic_structure_groups_id ? $row->group->ord : $currentGroup + 1)
                                @if($row->dynamic_structure_groups_id && !in_array($row->dynamic_structure_groups_id, $foundedGroups))
                                    @php($foundedGroups[] = $row->dynamic_structure_groups_id)
                                    <tr>
                                        <td colspan="2" style="text-align: left;">
                                            <b>{{ $row->group->ord }}. {{ mb_strtoupper($row->group->label) }}</b>
                                        </td>
                                    </tr>
                                @endif

                                @php($value = isset($kdValues) && sizeof($kdValues) ? $kdValues[$row->id] ?? '' : '')
                                @if($row->dynamic_structure_groups_id)
                                    <tr>
                                        <td style="text-align: center; width: 30px !important;"><b>{{ ($currentGroup ? $currentGroup.'.' : '').$row->ord }}</b></td>
                                        <td style="text-align: left;">
                                            {{ $row->label }}<br>
                                            {!! $value !!}
                                        </td>
                                    </tr>
    {{--                                <div class="col-2">--}}
    {{--                                    {{ ($currentGroup ? $currentGroup.'.' : '').$row->ord }}--}}
    {{--                                </div>--}}
    {{--                                <div class="col-4">--}}
    {{--                                    {{ $row->label }}--}}
    {{--                                </div>--}}
    {{--                                <div class="col-6">--}}
    {{--                                    {!! $value !!}--}}
    {{--                                </div>--}}
                                @else
                                    <tr>
                                        <td colspan="2"  style="text-align: left;">
                                            <b>{{ mb_strtoupper($currentGroup.'. '.$row->label) }}</b><br>
                                            {!! $value !!}
                                        </td>
                                    </tr>
    {{--                                <div class="col-6">--}}
    {{--                                    {{ $currentGroup.'. '.$row->label }}--}}
    {{--                                </div>--}}
    {{--                                <div class="col-6">--}}
    {{--                                    {!! $value !!}--}}
    {{--                                </div>--}}
                                @endif
    {{--                        </div>--}}
    {{--                    </tr>--}}
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

