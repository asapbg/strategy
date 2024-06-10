@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-2 right-side-content">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ $item->name ?? __('ogp.no_develop_plan') }}</h2>
            </div>
            @can('updateDevelopPlan', $item)
                <div class="row edit-consultation m-0">
                    <div class="col-md-12 text-end">
                        <a target="_blank" href="{{ route('admin.ogp.plan.develop.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-primary main-color">
                            <i class="fas fa-pen me-2 main-color"></i> {{ __('custom.edit') }}
                        </a>
                    </div>
                </div>
            @endcan
{{--            <div class="col-md-12 text-start">--}}
{{--                <button class="btn btn-primary  main-color">--}}
{{--                    <i class="fa-solid fa-download main-color me-2"></i>Експорт</button>--}}
{{--                <button class="btn rss-sub main-color">--}}
{{--                    <i class="fas fa-square-rss text-warning me-2"></i>RSS</button>--}}
{{--                <button class="btn rss-sub main-color">--}}
{{--                    <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>--}}
{{--            </div>--}}
        </div>
        @if($item)
            <div class="row mb-5">
                <div class="col-md-12 mb-4">
                    <h3 class="mb-2">{{ __('custom.develop_plan_calendar') }}</h3>
                </div>
                @if(isset($schedules) && sizeof($schedules))
                    <div class="col-md-5 px-5" id="calendar-list">
                        @foreach($schedules as $s)
                            <div>
                                <p class="custom-left-border mb-1 @if(!$loop->first) mt-3 @endif">
                                    <i class="fas bi-calendar text-primary me-2"></i>
                                    <strong>{{ displayDate($s['start']). (!$s['oneDay'] ? ' - '.displayDate($s['end']) : '') }}</strong> - {{ $s['title'] }}
                                </p>
                                @if(!empty($s['description_html']))
                                    <strong>{{ __('custom.description') }}</strong>: {!! $s['description_html'] !!}
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="col-md-7">
                    <div id="calendar"></div>
                </div>
            </div>
        @endif

        @if($item && $item->versionAfterConsultation())
            <div class="row mb-5">
                <div class="row table-light">
                    <div class="col-12 mb-2">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ trans_choice('custom.files', 2) }}</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $item->versionAfterConsultation()->id }}" data-url="{{ route('modal.file_preview', ['id' => $item->versionAfterConsultation()->id]) }}">
                                    {!! fileIcon($item->versionAfterConsultation()->content_type) !!} {{ $item->versionAfterConsultation()->description }} - {{ displayDate($item->versionAfterConsultation()->created_at) }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($item)
            <div class="row mb-3">
                <h3 class="mb-2 col-12 mb-3">{{ trans_choice('custom.areas', 2) }}</h3>
                @if($item->areas->count())
                    @foreach($item->areas as $area)
                        <div class="col-md-12 mb-3">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-img-holder p-2">
                                        <i class="bi bi-clipboard2-plus main-color"></i>
                                    </div>
                                    <div class="consult-body">
                                        <div class="consul-item">
                                            <div class="consult-item-header d-flex justify-content-between">
                                                @php($editRoute = isset($nationalPlanSection) ? route('ogp.national_action_plans.develop_plan.area', ['id' => $item->plan->id, 'planArea' => $area->id]) : route('ogp.develop_new_action_plans.area', ['plan' => $area->ogp_plan_id, 'planArea' => $area->id]))
                                                <div class="consult-item-header-link">
                                                    <a href="{{ $editRoute }}" class="text-decoration-none" title="{{ $area->name }}">
                                                        <h3>{{ $area->area->name }}</h3>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
                                                    @can('updateDevelopPlan', $item)
                                                        <a target="_blank" href="{{ route('admin.ogp.plan.develop.edit', ['id' => $item->id]).'#area-tab-'.$area->id }}">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="status mt-2">
                                                <span class="text-dark">{{ trans_choice('ogp.proposals', 2) }}: <span class="text-secondary">{{ $area->offers->count() }}</span></span>
                                            </div>
                                            <div class="meta-consul mt-2">
                                                <span class="text-secondary">
                                                    <span class="text-dark">{{ __('custom.proposal_period') }}: </span> {{ displayDate($item->from_date_develop) }} - {{ displayDate($item->to_date_develop) }}
                                                </span>
                                                <a href="{{ $editRoute }}" title="{{ $area->name }}">
                                                    <i class="fas fa-arrow-right read-more"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@if($item)
    @push('scripts')
        <script type="text/javascript">
            $(function () {
                {{--let events = <?php echo json_encode($itemsCalendar); ?>;--}}
                let events = <?php echo json_encode($schedules ?? []); ?>;
                var Calendar = FullCalendar.Calendar;

                var calendarEl = document.getElementById('calendar');
                var calendar = new Calendar(calendarEl, {
                    headerToolbar: {
                        left  : 'prev,next today',
                        center: 'title',
                    },
                    themeSystem: 'standard',
                    displayEventTime: false,
                    locale: 'bg-BG',
                    //Random default events
                    events: events,
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
@endif
