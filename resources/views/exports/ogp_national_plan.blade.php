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
                    <h4 style="background-color: #bce4ec; text-align: center; border-radius: 5px; padding:10px 20px;">ТЕМАТИЧНА ОБЛАСТ {{ $key + 1 }} <br>
                        {{ mb_strtoupper($areas->area->name) }}
                    </h4>
                    @if($areas->arrangements && $areas->arrangements->count())
                    <h5>{{ $key + 1 }}.1 Мерки</h5>
                        @foreach($areas->arrangements as $keyA => $arrangement)
                            <h5>{{ $key + 1 }}.1.{{ $keyA + 1 }} {{ $arrangement->name }}</h5>
                            <p><strong>{{ __('custom.period') }}:</strong> {{ $arrangement->from_date }} - {{ $arrangement->to_date }}</p>
                            <p><strong>{{ __('ogp.responsible_administration') }}:</strong> {!! $arrangement->responsible_administration !!}</p>
                            @if($arrangement->problem)
                                <p>
                                    <strong>{{ __('ogp.problem') }}:</strong> {!! $arrangement->problem !!}
                                </p>
                            @endif
                            @if($arrangement->content)
                                <p>
                                    <strong>{{ __('ogp.action_content') }}:</strong> {!! $arrangement->content !!}
                                </p>
                            @endif
                            @if($arrangement->solving_problem)
                                <p>
                                    <strong>{{ __('ogp.solving_problem') }}:</strong> {!! $arrangement->solving_problem !!}
                                </p>
                            @endif
                            @if($arrangement->values_initiative)
                                <p>
                                    <strong>{{ __('ogp.values_initiative') }}:</strong> {!! $arrangement->values_initiative !!}
                                </p>
                            @endif
                            @if($arrangement->extra_info)
                                <p>
                                    <strong>{{ __('ogp.extra_info') }}:</strong> {!! $arrangement->extra_info !!}
                                </p>
                            @endif
                            @if($arrangement->npo_partner)
                                <p>
                                    <strong>{{ __('ogp.npo_partner') }}:</strong> {!! $arrangement->npo_partner !!}
                                </p>
                            @endif
                            @if($arrangement->interested_org)
                                <p>
                                    <strong>{{ __('ogp.interested_org') }}:</strong> {!! $arrangement->interested_org !!}
                                </p>
                            @endif

                            @if($arrangement->actions->count())
                                <h5> <strong>{{ __('ogp.ogp_plan_actions') }} </strong></h5>
                                @foreach($arrangement->actions as $action)
                                    <p>
                                        <strong>{{ $action->name }}</strong> {{ $action->from_date }} - {{ $action->to_date }}
                                    </p>
                                @endforeach
                                <div class="margin-bottom:10px;"></div>                                               <hr class="custom-hr mb-2 mt-5">
                            @endif
                            @if($arrangement->contact_names)
                                <p> <strong>{{ __('ogp.ogp_plan_contacts') }}</strong> </p>
                                {!! $arrangement->contact_names !!}
                                <div class="margin-bottom:10px;"></div>
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
