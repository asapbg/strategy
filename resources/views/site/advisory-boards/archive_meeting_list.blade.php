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
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
                <div class="custom-card p-3">
                    <h3 class="mb-2 fs-4">{{ __('custom.meetings_and_decisions') }}</h3>

                    @foreach($items as $meeting)
                        <p class="fw-bold mt-3">Дата: <span class="fw-normal">{{ displayDate($meeting->next_meeting) }}</span></p>
                        <p>
                            {!! $meeting->description !!}
                        </p>
                        @if($meeting->siteFiles->count())
                            @foreach($meeting->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file, 'debug' => true, 'no_second_active_status' => true])
                            @endforeach
                        @endif
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>

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
