@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer'])

{{--<div class="row">--}}
{{--    <div id="calendar" class="datepicker"></div>--}}
{{--</div>--}}
<div class="row mb-2">
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
                        <hr>
                        <p>
                            {!! $meeting->description !!}
                        </p>
                        @if($meeting->siteFiles->count())
                            @foreach($meeting->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file, 'debug' => true])
                            @endforeach
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

{{--@push('scripts')--}}
{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function (){--}}
{{--            var dateToHilight = <?php echo json_encode($itemsCalendar);?>;--}}
{{--            var datesArr = Object.entries(dateToHilight);--}}

{{--            jQuery(document).ready(function() {--}}

{{--                // An array of dates--}}
{{--                var eventDates = {};--}}
{{--                for(let i=0; i < datesArr.length; i++){--}}
{{--                    eventDates[datesArr[i][1]] = datesArr[i][1].toString();--}}
{{--                }--}}

{{--                // datepicker--}}
{{--                jQuery('#calendar').datepicker({--}}
{{--                    beforeShowDay: function( date ) {--}}
{{--                        console.log(date);--}}
{{--                        var highlight = eventDates[date];--}}
{{--                        if( highlight ) {--}}
{{--                            return [true, "event", highlight];--}}
{{--                        } else {--}}
{{--                            return [true, '', ''];--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
