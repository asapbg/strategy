@if(isset($isPdf) && $isPdf)
    <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{trans_choice('custom.polls', 1)}}: {{ $poll->name }}</title>
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
        <table class="table table-hover table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th style="background-color: #bce4ec; font-weight: bold;">{{trans_choice('custom.polls', 1)}}</th>
                <th colspan="2" style="background-color: #bce4ec; font-weight: bold;">{{ $poll->name }}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($poll) && $poll->questions->count() > 0)
                @foreach($poll->questions as $q)
                    <tr>
                        <td colspan="" style="background-color: #bce4ec; font-weight: bold;">{{ trans_choice('custom.questions', 1) }}: {{$q->name}}</td>
                        <td colspan="2" style="background-color: #bce4ec; font-weight: bold;">{{ trans_choice('custom.users', 2) }}: {{ isset($statistic) && isset($statistic[$q->id]) ? $statistic[$q->id]['users'] : 0 }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #bce4ec; font-weight: bold;">{{ trans_choice('custom.answers', 1) }}</td>
                        <td style="background-color: #bce4ec; font-weight: bold;">{{ trans_choice('custom.users', 2) }}</td>
                        <td style="background-color: #bce4ec; font-weight: bold;">%</td>
                    </tr>
                    @if($q->answers->count())
                        @foreach($q->answers as $key => $a)
                            @php($percents = 0)
                            @if(isset($statistic) && isset($statistic[$q->id]) && isset($statistic[$q->id]['options'][$a->id]))
                                @php($percents = ($statistic[$q->id]['options'][$a->id] * 100) / $statistic[$q->id]['users'])
                            @endif
                            <tr>
                                <td>{{ $a->name }}</td>
                                <td>{{ isset($statistic) && isset($statistic[$q->id]) ? $statistic[$q->id]['users'] : 0 }}</td>
                                <td>{{ $percents }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
@if(isset($isPdf) && $isPdf)
</div>
</body>
</html>
@endif
