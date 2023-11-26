@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $data['title'] }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
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
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div style="font-size: 22px;">
@endif
            <table style="border-collapse:collapse;">
                <tr>
                    <td colspan="3" style="background: #d9d7d7; font-weight: bold; text-align: center;"><b>{{ mb_strtoupper($data['title']) }}</b></td>
                </tr>
                <tr>
                    <td><b>Коментар</b></td>
                    <td><b>Автор</b></td>
                    <td><b>Дата</b></td>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td>{{ htmlToText($row->content) }}</td>
                            <td>{{ $row->user_id ? $row->author->fullName() : 'Анонимен' }}</td>
                            <td>{{ displayDate($row->created_at) }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
