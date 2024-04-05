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
            @php($colspan = $data['searchMeetings'] ? 9 : 8)
            <table style="border-collapse:collapse;">
                <tr>
                    <th colspan="{{ $colspan }}" style="background: #d9d7d7; font-weight: bold;"><b>{{ mb_strtoupper($data['title']) }}</b></th>
                </tr>
                <tr>
                    <th>{{ __('custom.name') }}</th>
                    <th>{{ trans_choice('custom.field_of_actions', 1) }}</th>
                    <th>{{ __('custom.type_of_governing') }}</th>
                    <th>{{ __('validation.attributes.act_of_creation') }}</th>
                    <th>{{ __('validation.attributes.advisory_chairman_type_id') }}</th>
                    <th>Представител на НПО</th>
                    <th>Мин. бр. заседания на година</th>
                    @if($data['searchMeetings'])
                        <th>Бр. заседания в периода</th>
                    @endif
                    <th>{{ __('custom.status') }}</th>

                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->policyArea?->name }}</td>
                            <td>{{ $row->authority?->name }}</td>
                            <td>{{ $row->advisoryActType?->name }}</td>
                            <td>{{ $row->advisoryChairmanType?->name }}</td>
                            <td>{{ $row->has_npo_presence ? 'Да' : 'Не' }}</td>
                            <td>{{ $row->meetings_per_year }}</td>
                            @if($data['searchMeetings'])
                                <td>{{ $row->meetings }}</td>
                            @endif
                            <td>{{ $row->active ? __('custom.active_m') : __('custom.inactive_m') }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
