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
            <h3 style="text-align: center;margin-bottom: 100px;">{{ $data['title'] }}</h3>
            {{ htmlToText($data['content']) }}
            <br>
            <h4>ТЕМАТИЧНИ ОБЛАСТИ</h4>

            @if(isset($data['rows']) && $data['rows']->count())
                @foreach($data['rows'] as $key => $areas)
                    <h4 style="background-color: #bce4ec; text-align: center;">ТЕМАТИЧНА ОБЛАСТ {{ $key + 1 }} <br>
                        {{ mb_strtoupper($areas->area->name) }}
                    </h4>
                    @if($areas->arrangements && $areas->arrangements->count())
                    <h5>{{ $key + 1 }}.1 Мерки</h5>
                        @foreach($areas->arrangements as $keyA => $arrangement)
                            <h5>{{ $key + 1 }}.1.{{ $keyA + 1 }} {{ $arrangement->name }}</h5>
                            <p><strong>{{ __('custom.period') }}:</strong> {{ $arrangement->from_date }} - {{ $arrangement->to_date }}</p>
                            <p><strong>{{ __('ogp.responsible_administration') }}:</strong> {!! $arrangement->responsible_administration !!}</p>
                            <p><strong>{{ __('ogp.npo_partner') }}:</strong> {!! $arrangement->npo_partner !!}</p>
                            {!! $arrangement->content !!}
{{--                            {{ htmlToText($arrangement->content) }}--}}
                        @endforeach
                    @endif
                @endforeach
            @endif
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
