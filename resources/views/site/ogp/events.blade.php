@extends('layouts.site', ['fullwidth' => true])

@section('content')

    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 right-side-content py-5" id="listContainer">
            <div class="row">
                <div class="col-md-7">
                    <div id="calendar"></div>
                </div>
                @if(isset($itemsCalendar) && sizeof($itemsCalendar))
                    <div class="col-12">
                        <h3 class="mt-4 mb-2 fs-4">{{ trans_choice('custom.lists', 1) }} {{ __('custom.with_long') }} {{ mb_strtolower(trans_choice('custom.events', 2)) }}</h3>
                        @foreach($itemsCalendar as $event)
                            <div>
                                <p class="custom-left-border mb-1 @if(!$loop->first) mt-3 @endif">
                                    <i class="fas bi-calendar text-primary me-2"></i>
                                    <strong>{{ displayDate($event['start']). (!$event['oneDay'] ? ' - '.displayDate($event['end']) : '') }}</strong> - {{ $event['title'] }}
                                </p>
                                @if(!empty($event['description_html']))
                                    {{ __('custom.description') }}: {!! $event['description_html'] !!}
                                @endif
                            </div>
                            {{--                            <div class="col-12 mb-3">--}}
                            {{--                                <p class="mb-1"><span class="custom-left-border">{{ displayDate($event['start']) }}</span> ({{ $event['title'] }})</p>--}}
                            {{--                                @if(!empty($event['description']))--}}
                            {{--                                    {{ __('custom.description') }}: {!! $event['description'] !!}--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function () {
            let events = <?php echo json_encode($itemsCalendar); ?>;
            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('calendar');
            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left  : 'prev,next today',
                    center: 'title',
                    // right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'standard',
                displayEventTime: false,
                {{--lang: '<?php echo app()->getLocale();?>',--}}
                locale: 'bg-BG',
                //Random default events
                events: <?php echo json_encode($itemsCalendar); ?>,
                eventDidMount: function(info) {

                    $(info.el).tooltip({
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body',
                        delay: { "show": 50, "hide": 50 },

                    });
                },
            });

            calendar.render();
        });
    </script>
@endpush
