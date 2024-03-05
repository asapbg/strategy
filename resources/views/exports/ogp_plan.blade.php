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
            <h3 style="text-align: center">{{ $data['title'] }}</h3>
            {{ htmlToText($data['content']) }}
            <br>
            <h4>ТЕМАТИЧНИ ОБЛАСТИ И АНГАЖИМЕНТИ</h4>

            @if(isset($data['rows']) && $data['rows']->count())
                @foreach($data['rows'] as $key => $areas)
                    <h4 style="background-color: #bce4ec; text-align: center;">ТЕМАТИЧНА ОБЛАСТ {{ $key + 1 }} <br>
                        {{ mb_strtoupper($areas->area->name) }}
                    </h4>
                    @if($areas->offers && $areas->offers->count())
                        @foreach($areas->offers as $keyA => $offer)
                            @php($commentsCnt = $offer->comments->count())
                            <p><strong>Предложение {{ ($keyA + 1)}}</strong> - Подкрепям ({{ $offer->likes_cnt }}) / Не подкрепям ({{ $offer->dislikes_cnt }})</p>
                            <hr>
                            {!! $offer->content !!}
{{--                            {{ htmlToText($offer->content) }}--}}
                            @if($commentsCnt)
                                <br>
                                <p><strong>Коментари ({{ $commentsCnt }})</strong>:</p>
                                @foreach($offer->comments()->orderBy('created_at', 'asc')->get() as $keyC => $comment)
                                    <p>({{ ($keyC + 1) }}) Автор: {{ $comment->author->fullname() }} ({{ displayDateTime($comment->created_at) }})</p>
                                    {!! $comment->content !!}
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
