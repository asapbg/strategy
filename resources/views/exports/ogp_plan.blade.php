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
            <h3>{{ $data['title'] }}</h3>
            {{ htmlToText($data['content']) }}

            <h4>ТЕМАТИЧНИ ОБЛАСТИ И АНГАЖИМЕНТИ</h4>

            @if(isset($data['rows']) && $data['rows']->count())
                @foreach($data['rows'] as $key => $areas)
                    <h4>ТЕМАТИЧНА ОБЛАСТ {{ $key + 1 }} <br>
                        {{ mb_strtoupper($areas->area->name) }}</h4>
                    <h4>Мерки</h4>
                    @if($areas->arrangements && $areas->arrangements->count())
                        @foreach($areas->arrangements as $keyA => $arrangement)
                            <p><strong>{{ ($keyA + 1).' '.htmlToText($arrangement->content) }}</strong></p>
                        @endforeach
                    @endif
                @endforeach
            @endif
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
