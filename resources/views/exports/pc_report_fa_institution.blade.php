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
                @php($colspan = 2)
                <tr>
                    <td colspan="{{ $colspan }}" style="background: #d9d7d7; font-weight: bold;"><b>{{ mb_strtoupper($data['title']) }}</b></td>
                </tr>
                <tr>
                    <td>{{ __('custom.name') }}</td>
                    <td>{{ trans_choice('custom.public_consultations', 2) }}</td>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->pc_cnt }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
