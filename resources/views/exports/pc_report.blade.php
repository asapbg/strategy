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
                @php($colspan = 9)
                <tr>
                    <td colspan="{{ $colspan }}" style="background: #d9d7d7; font-weight: bold;"><b>{{ mb_strtoupper($data['title']) }}</b></td>
                </tr>
                <tr>
                    <td>{{ __('custom.name') }}</td>
                    <td>{{ trans_choice('custom.field_of_actions', 1) }}</td>
                    <td>{{ __('custom.status') }}</td>
                    <td>{{ trans_choice('custom.institution', 1) }}</td>
                    <td>{{ trans_choice('custom.act_type', 1) }}</td>
                    <td>Срок (дни)</td>
                    <td>Мотиви за кратък срок</td>
{{--                    <td>Липсващи документи</td>--}}
                    <td>{{ trans_choice('custom.comment', 2) }}</td>
                    <td>Справка/съобщение</td>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td>@if(isset($isPdf) && $isPdf)<a href="{{ route('public_consultation.view', $row->id) }}" target="_blank">{{ $row->title }}</a>@else {{ $row->title }}@endif</td>
                            <td>{{ $row->fieldOfAction?->name }}</td>
                            <td>{{ $row->inPeriod }}</td>
                            <td>{{ $row->importerInstitution?->name }}</td>
                            <td>{{ $row->actType?->name }}</td>
                            <td>{{ $row->daysCnt }}</td>
                            <td>{!! $row->short_term_reason !!}</td>
{{--                            <td>????</td>--}}
                            <td>{{ $row->comments->count() }}</td>
                            <td>@if($row->proposalReport->count()){{ 'Да' }}@else{{ 'Не' }}@endif</td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
