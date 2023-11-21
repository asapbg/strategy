@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $data['title'] }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            td {
                padding:10px 0 !important;
            }
        </style>
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div style="font-size: 22px;">
@endif
            <table class="table table-striped table-responsive" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2"><strong>{{ $data['title'] }}</strong></th>
                </tr>
                <tr>
                    <th>Коментар</th>
                    <th>Автор</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td>{{ htmlToText($row->content) }}</td>
                            <td>{{ $row->user_id ? $row->author->fullName() : 'Анонимен' }}</td>
                            <td>{{ displayDate($row->created_at) }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
