@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $data['title'] }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            @page {
                margin: 20px 0 !important;
                /*padding: 20px 20px !important;*/
            }
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 10pt;
            }
            table, th, td {
                border: 2px solid black;
                border-collapse: collapse;
                padding: 4px 7px;
            }
        </style>
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div>
@endif
            <table style="border-collapse:collapse;">
                <tr><th colspan="6">{{ $data['title'] }}</th></tr>
                <tr>
                    <th>{{ __('custom.name') }}</th>
                    <th>{{ __('custom.pc_count') }}</th>
                    <th>{{ __('custom.pc_less_then_30_days_count') }}</th>
                    <th>{{ __('custom.pc_no_short_reason') }}</th>
                    <th>{{ __('custom.pc_missing_docs') }}</th>
                    <th>{{ __('custom.pc_no_proposal_report') }}</th>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td class="custom-left-border">{{ $row->name }}</td>
                            <td>{{ $row->pc_cnt }}</td>
                            <td>{{ $row->less_days_cnt }}</td>
                            <td>{{ $row->no_less_days_reason_cnt }}</td>
                            <td>
                                @if(isset($data['missingFiles']) && sizeof($data['missingFiles']) && isset($data['missingFiles'][$row->id]) && $data['missingFiles'][$row->id] > 0)
                                    {{ __('custom.yes') }}
                                @else
                                    {{ __('custom.no') }}
                                @endif
                            </td>
                            <td>{{ $row->has_report }}</td>
                        </tr>
                        @if(isset($data['consultationByType']) && sizeof($data['consultationByType']) && isset($data['consultationByType'][$row->id]))
                            @php($byActType = json_decode($data['consultationByType'][$row->id]->act_info, true))
                            @if($byActType)
                                <tr>
                                    <th colspan="6">{{ trans_choice('custom.act_type', 1) }}</th>
                                </tr>
                                @foreach($byActType as $act)
                                    <tr>
                                        <td>{{ $act['act_name'] }}</td>
                                        <td colspan="5">{{ $act['act_cnt'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
