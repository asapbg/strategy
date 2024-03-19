@extends('layouts.site', ['fullwidth' => true])

@section('content')

    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 right-side-content py-5" id="listContainer">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h3 class="mb-2">{{ trans_choice('custom.lists', 1) }} {{ __('custom.with_long') }} {{ mb_strtolower(trans_choice('custom.events', 2)) }}</h3>
                </div>
                @php($newSection = false)
                @php($indx = 1)
                @if(isset($itemsCalendar) && sizeof($itemsCalendar))
                    <div class="col-md-5" id="myGroup">
                        @foreach($itemsCalendar as $event)
                            @if($newSection || $loop->first)
                                <div class="@if(!$loop->first) d-none @endif pagination-page" id="page-{{ $indx }}">
                            @endif

                                <div>
                                    <p class="custom-left-border mb-1 @if(!$loop->first) mt-3 @endif">
                                        <i class="fas bi-calendar text-primary me-2"></i>
                                        <strong>{{ displayDate($event['start']). (!$event['oneDay'] ? ' - '.displayDate($event['end']) : '') }}</strong> - {{ $event['title'] }}
                                    </p>
                                    @if(!empty($event['description_html']))
                                        <strong>{{ __('custom.description') }}</strong>: {!! $event['description_html'] !!}
                                    @endif
                                </div>
                            {{--                            <div class="col-12 mb-3">--}}
                            {{--                                <p class="mb-1"><span class="custom-left-border">{{ displayDate($event['start']) }}</span> ({{ $event['title'] }})</p>--}}
                            {{--                                @if(!empty($event['description']))--}}
                            {{--                                    {{ __('custom.description') }}: {!! $event['description'] !!}--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                            @php($newSection = ($indx % 10 == 0))
                            @php($indx += 1)
                            @if($newSection || $loop->last)
                                </div>
                            @endif
                        @endforeach
                            @if(sizeof($itemsCalendar) > 10)
                                <div class="col-md-12 mt-3">
                                    @php($newPage = false)
                                    @php($pageCnt = 1)
                                    @php($indx = 1)
                                    @foreach($itemsCalendar as $event)
                                        @if($newPage || $loop->first)
                                            <button class="btn custom-pagination-item px-2 @if($loop->first) active @endif" type="button" data-page="page-{{ $indx }}">
                                                {{ $pageCnt }}
                                            </button>
                                        @endif
                                        @php($newPage = $indx % 10 == 0)
                                        @php($pageCnt += $newPage ? 1 : 0)
                                        @php($indx += 1)
                                    @endforeach
                                </div>
                            @endif
                    </div>
                @endif
                <div class="col-md-7">
                    <div id="calendar"></div>
                </div>
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

            $('.custom-pagination-item').on('click', function (event){
                if(!$(event.target).hasClass('active')){
                    $('.pagination-page').addClass('d-none');
                    $('.custom-pagination-item').removeClass('active');
                    $(event.target).addClass('active')
                    $('#' + $(event.target).data('page')).removeClass('d-none');
                }
            });
        });
    </script>
@endpush
