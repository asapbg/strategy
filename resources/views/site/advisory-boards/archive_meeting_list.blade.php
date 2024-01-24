<div class="row">
    <div class="col-md-6">
        <div id="calendar"></div>
    </div>
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'class' => 'col-md-6'])
</div>



<div class="row mb-2 mt-4">
    <div class="col-md-6 mt-2">
        <div class="info-consul text-start">
            <p class="fw-600">
                {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}
            </p>
        </div>
    </div>
    @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
</div>

<div class="row">
    @if(isset($items) && $items->count() > 0)
        @php($year = null)
        @php($newYear = false)
        @foreach($items as $meeting)
            @php($newYear = ($loop->first || \Carbon\Carbon::parse($meeting->next_meeting)->format('Y') != $year) ? true : false)
            @php($year = ($loop->first || \Carbon\Carbon::parse($meeting->next_meeting)->format('Y') != $year) ? \Carbon\Carbon::parse($meeting->next_meeting)->format('Y') : $year)
            @if($newYear && !$loop->first)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($newYear)
                    <div class="row p-1">
                        <div class="accordion" id="accordionExample">
                            <div class="card custom-card">
                                <div class="card-header" id="heading{{ $year }}">
                                    <h2 class="mb-0">
                                        <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start @if(!$loop->first) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $year}}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $year }}">
                                            <i class="me-1 bi bi-calendar fs-18"></i>  {{ __('custom.meetings_and_decisions') }} - {{ $year }} {{ __('custom.year_short') }}
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse{{ $year }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $year }}" data-parent="#accordionExample">
                                    <div class="card-body">
            @endif

            <p class="fw-bold mt-3 custom-left-border" style="font-size: 20px;">{{ __('custom.meeting_at') }}: <span class="fw-normal">{{ displayDate($meeting->next_meeting) }} {{ __('custom.year_short') }}</span></p>
            <p>
                {!! $meeting->description !!}
            </p>

             @if(isset($meeting->decisions) && $meeting->decisions->count() > 0)
                @foreach($meeting->decisions as $information)
                    <div class="col-12">
                        <p>
                            {{ __('custom.meeting_date') . ':' . ' ' . \Carbon\Carbon::parse($information->date_of_meeting)->format('d.m.Y') }}
                        </p>
                    </div>
                    @if(!empty($information->agenda))
                    <div class="col-12">
                        <p>
                            {{ __('validation.attributes.agenda') . ':' . ' ' . $information->agenda }}
                        </p>
                    </div>
                    @endif
                    @if(!empty($information->proto))
                    <div class="col-12">
                        <p>
                            {{ __('validation.attributes.protocol') . ':' . ' ' . $information->protocol }}
                        </p>
                    </div>
                    @endif
                    @if(!empty($information->decisions))
                    <div class="col-12">
                        <p>
                            {{ __('validation.attributes.decisions') . ':' }} {!! $information->decisions !!}
                        </p>
                    </div>
                    @endif
                    @if(!empty($information->suggestionss))
                    <div class="col-12">
                        <p>
                            {{ __('validation.attributes.suggestions') . ':' }} {!! $information->suggestions !!}
                        </p>
                    </div>
                    @endif
                    @if(!empty($information->other))
                    <div class="col-12">
                        <p>
                            {{ __('validation.attributes.other') . ':' }} {!! $information->other !!}
                        </p>
                    </div>
                    @endif
                @endforeach
            @endif

            @if($meeting->siteFiles->count())
                @foreach($meeting->siteFiles as $file)
                    @includeIf('site.partial.file', ['file' => $file, 'debug' => true, 'no_second_active_status' => true])
                @endforeach
            @endif
            @if(!$loop->last)
                <hr>
            @endif

            @if($loop->last)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @endif
        @endforeach
    @endif
</div>

{{--<div class="row">--}}
{{--    @if(isset($items) && $items->count() > 0)--}}
{{--        {{ $items->onEachSide(0)->appends(request()->query())->links() }}--}}
{{--    @endif--}}
{{--</div>--}}

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
                        delay: { "show": 50, "hide": 50 }
                    });
                },
            });

            calendar.render();
        });
    </script>
@endpush
