@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-2 right-side-content">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ $plan->name }}</h2>
            </div>
{{--            @can('update', $plan)--}}
{{--                <div class="row edit-consultation m-0">--}}
{{--                    <div class="col-md-12 text-end">--}}
{{--                        <a href="{{ route('admin.ogp.plan.edit', ['id' => $plan->id]) }}" class="btn btn-sm btn-primary main-color">--}}
{{--                            <i class="fas fa-pen me-2 main-color"></i> {{ __('custom.edit_ogp_area') }}--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endcan--}}
            <div class="col-md-12 ">
                <a href="{{ route('ogp.national_action_plans.export', $plan->id) }}" class="btn btn-primary  main-color"><i class="fa-solid fa-download main-color me-2"></i>{{ __('custom.export_as_pdf') }}</a>
{{--                <button class="btn btn-primary  main-color">--}}
{{--                    <i class="fa-solid fa-download main-color me-2"></i>{{ __('custom.export_as_pdf') }}</button>--}}
{{--                <button class="btn rss-sub main-color">--}}
{{--                    <i class="fas fa-square-rss text-warning me-2"></i>RSS</button>--}}
{{--                <button class="btn rss-sub main-color">--}}
{{--                    <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>--}}
                @can('update', $plan)
                    <a href="{{ route('admin.ogp.plan.edit', ['id' => $plan->id]) }}" class="btn btn-primary main-color mt-md-0 mt-2">
                        <i class="fas fa-pen me-2 main-color"></i> {{ __('custom.edit_ogp_area') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-5">{{ __('custom.from_date') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ displayDate($plan->from_date) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-5">{{ __('custom.to_date') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ displayDate($plan->to_date) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-5">{{ __('custom.status') }}</h3>
                <span class="main-color text-decoration-none fs-18">
                    <span class="{{ $plan->status->css_class }} fs-16">{{ $plan->status->name }}</span>
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 pt-3">
                {!! $plan->content !!}
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                @if($plan->otherFilesByLang->count())
                    <ul class="p-0">
                        @foreach($plan->otherFilesByLang as $doc)
                            <li class="list-group-item">
                                @php($file_name = fileIcon($doc->content_type)." $doc->description | ".displayDate($doc->created_at))
                                @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <h4 class="custom-left-border mb-3">{{ trans_choice('ogp.arrangements', 2) }}</h4>
                <div class="accordion" id="accordionExample">
                    @foreach($plan->areas as $area)
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="heading_{{ $loop->iteration }}">
                                <button class="accordion-button text-dark fs-18 fw-600" type="button"
                                        data-toggle="collapse" data-target="#collapse-{{ $loop->iteration }}"
                                        aria-controls="collapse-{{ $loop->iteration }}">
                                    {{ __('ogp.subject_area') }} - {{ $area->area->name }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $loop->iteration }}" @class(["accordion-collapse", "collapse"])
                                 aria-labelledby="heading_{{ $loop->iteration }}" data-parent="#accordionExample" style=""
                            >
                                @foreach($area->arrangements as $a)
                                    <div class="accordion-body">
                                        <div class="custom-card p-3 mb-2 pb-0">
                                            <div class="row ">
                                                <div class="document-info-body">
    {{--                                                <div class="row mb-3">--}}
    {{--                                                    <div class="col-m-12">--}}
    {{--                                                        <a href="{{ route('ogp.develop_new_action_plans.area', ['plan' => $area->ogp_plan_id, 'planArea' => $area->ogp_area_id]) }}" class="float-end text-decoration-none">{{ __('custom.view') }} <i class="fas fa-arrow-right read-more"></i></a>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                @foreach($area->arrangements as $a)--}}
                                                    <hr class="custom-hr mb-2">
                                                    <h3 class="fs-18">{{ __('ogp.ogp_plan_arrangement_description') }}</h3>
                                                    <hr class="custom-hr mb-2">
                                                    <p class="custom-left-border" style="font-size: 28px;">
                                                        <strong>{{ trans_choice('custom.arrangement', 1) }}:</strong> {{ $a->name }}
                                                    </p>
                                                    <p>
                                                        <strong>{{ __('ogp.deadline') }}:</strong>
                                                        @if(is_null($a->from_date) && is_null($a->to_date))
                                                            {{ __('ogp.unlimited') }}
                                                        @else
                                                            @if(!is_null($a->from_date))
                                                                {{ displayDate($a->from_date) }}
                                                            @endif
                                                            @if(!is_null($a->to_date))
                                                                @if(!is_null($a->from_date))- @endif {{ displayDate($a->to_date) }}
                                                            @endif
                                                        @endif
                                                    </p>
                                                    @if($a->responsible_administration)
                                                        <p>
                                                            <strong>{{ __('ogp.responsible_administration') }}:</strong> {!! $a->responsible_administration !!}
                                                        </p>
                                                    @endif
                                                    @if($a->problem)
                                                        <p>
                                                            <strong>{{ __('ogp.problem') }}:</strong> {!! $a->problem !!}
                                                        </p>
                                                    @endif
                                                    @if($a->content)
                                                        <p>
                                                            <strong>{{ __('ogp.action_content') }}:</strong> {!! $a->content !!}
                                                        </p>
                                                    @endif
                                                    @if($a->solving_problem)
                                                        <p>
                                                            <strong>{{ __('ogp.solving_problem') }}:</strong> {!! $a->solving_problem !!}
                                                        </p>
                                                    @endif
                                                    @if($a->values_initiative)
                                                        <p>
                                                            <strong>{{ __('ogp.values_initiative') }}:</strong> {!! $a->values_initiative !!}
                                                        </p>
                                                    @endif
                                                    @if($a->extra_info)
                                                        <p>
                                                            <strong>{{ __('ogp.extra_info') }}:</strong> {!! $a->extra_info !!}
                                                        </p>
                                                    @endif
                                                    @if($a->npo_partner)
                                                        <p>
                                                            <strong>{{ __('ogp.npo_partner') }}:</strong> {!! $a->npo_partner !!}
                                                        </p>
                                                    @endif
                                                    @if($a->interested_org)
                                                        <p>
                                                            <strong>{{ __('ogp.interested_org') }}:</strong> {!! $a->interested_org !!}
                                                        </p>
                                                    @endif

                                                    @if($a->actions->count())
                                                        <hr class="custom-hr mb-2 mt-5">
                                                        <h3 class="fs-18">{{ __('ogp.ogp_plan_actions') }}</h3>
                                                        <hr class="custom-hr mb-2">
                                                        @foreach($a->actions as $action)
                                                            <p>
                                                                <strong>{{ $action->name }}</strong> {{ $action->from_date }} - {{ $action->to_date }}
                                                            </p>
                                                        @endforeach
    {{--                                                    <hr class="custom-hr mb-2 mt-5">--}}
                                                    @endif

{{--                                                    @if($a->contact_names || $a->contact_positions || $a->contact_phone_email)--}}
                                                    @if($a->contact_names)
                                                        <hr class="custom-hr mb-2 mt-5">
                                                        <h3 class="fs-18">{{ __('ogp.ogp_plan_contacts') }}</h3>
                                                        <hr class="custom-hr mb-2">
                                                        {!! $a->contact_names !!}
{{--                                                        @if($a->contact_names)--}}
{{--                                                            <p>--}}
{{--                                                                <strong>{{ __('ogp.contact_names') }}:</strong> {!! $a->contact_names !!}--}}
{{--                                                            </p>--}}
{{--                                                        @endif--}}
{{--                                                        @if($a->contact_positions)--}}
{{--                                                            <p>--}}
{{--                                                                <strong>{{ __('ogp.contact_positions') }}:</strong> {!! $a->contact_positions !!}--}}
{{--                                                            </p>--}}
{{--                                                        @endif--}}
{{--                                                        @if($a->contact_phone_email)--}}
{{--                                                            <p>--}}
{{--                                                                <strong>{{ __('ogp.contact_phone_email') }}:</strong> {!! $a->contact_phone_email !!}--}}
{{--                                                            </p>--}}
{{--                                                        @endif--}}
                                                    @endif
    {{--                                                @endforeach--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if(!empty($plan->self_evaluation_published_at) && \Carbon\Carbon::parse($plan->self_evaluation_published_at)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d'))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ __('ogp.national_plan_evaluation_section') }}</h4>
                    <div class="accordion" id="accordionExampleEvaluation">
                        @foreach($plan->areas as $area)
                            @php($hasEvaluation = false)
                            @php($uniqueEvaluation = $area->arrangements->unique('evaluation')->pluck('evaluation')->toArray())
                            @php($uniqueEvaluationStatus = $area->arrangements->unique('evaluation_status')->pluck('evaluation_status')->toArray())
                                <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="heading_evaluation_{{ $loop->iteration }}">
        {{--                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"--}}
                                        <button class="accordion-button text-dark fs-18 fw-600" type="button"
                                                data-toggle="collapse" data-target="#collapse-evaluation-{{ $loop->iteration }}"
                                                aria-controls="collapse-{{ $loop->iteration }}">
                                            {{ __('ogp.subject_area_no', ['number' => $loop->iteration]) }} - {{ $area->area->name }}
                                        </button>
                                    </h2>
        {{--                            @class(["accordion-collapse", "collapse", "show" => $loop->first])--}}
                                    <div id="collapse-evaluation-{{ $loop->iteration }}" @class(["accordion-collapse", "collapse"])
                                        aria-labelledby="heading_evaluation_{{ $loop->iteration }}" data-parent="#accordionExampleEvaluation" style="">
                                        @if($area->arrangements->count() && ((sizeof($uniqueEvaluation) > 1 || !is_null($uniqueEvaluation[0])) || (sizeof($uniqueEvaluationStatus) > 1 || !is_null($uniqueEvaluationStatus[0]))))
                                            @php($hasEvaluation = true)
                                            @foreach($area->arrangements as $a)
                                                <div class="accordion-body">
                                                    <div class="custom-card p-3 mb-2 pb-0">
                                                        <div class="row ">
                                                            <div class="document-info-body">
                                                                <hr class="custom-hr mb-2">
                                                                <h3 class="fs-18">{{ trans_choice('custom.arrangement', 1) }}: {{ $a->name }}</h3>
                                                                <hr class="custom-hr mb-2">
                                                                @if($a->evaluation)
                                                                    <p>
                                                                        <strong>{{ __('ogp.evaluation') }}:</strong> {!! $a->evaluation !!}
                                                                    </p>
                                                                @endif
                                                                @if($a->evaluation_status)
                                                                    <p>
                                                                        <strong>{{ __('custom.status') }}:</strong> {!! $a->evaluation_status !!}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(!$hasEvaluation)
                                            <div class="col-12 ps-3 my-2">{{ __('ogp.no_evaluation_found') }}</div>
                                        @endif
                                    </div>
                                </div>
    {{--                        @endif--}}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($plan->report_title) && !empty($plan->report_evaluation_published_at) && \Carbon\Carbon::parse($plan->report_evaluation_published_at)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d'))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ $plan->report_title }}</h4>
                    {!! $plan->report_content !!}
                    @if($plan->reportEvaluationByLang->count())
                        <div class="col-12 mb-2">
                            <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ trans_choice('custom.documents', 2) }}</p>
                            <ul class="list-group list-group-flush p-0">
                                @foreach($plan->reportEvaluationByLang as $doc)
                                    <li class="list-group-item px-0">
                                        @php($file_name = fileIcon($doc->content_type)." $doc->description | ".displayDate($doc->created_at))
                                        @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
{{--                    <div class="accordion" id="accordionExampleEvaluation">--}}
{{--                        @foreach($plan->areas as $row)--}}
{{--                            @php($hasEvaluation = false)--}}
{{--                            @php($uniqueEvaluation = $row->arrangements->unique('evaluation')->pluck('evaluation')->toArray())--}}
{{--                            @php($uniqueEvaluationStatus = $row->arrangements->unique('evaluation_status')->pluck('evaluation_status')->toArray())--}}
{{--                                <div class="accordion-item mb-2">--}}
{{--                                    <h2 class="accordion-header" id="heading_evaluation_{{ $loop->iteration }}">--}}
{{--        --}}{{--                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"--}}
{{--                                        <button class="accordion-button text-dark fs-18 fw-600" type="button"--}}
{{--                                                data-toggle="collapse" data-target="#collapse-evaluation-{{ $loop->iteration }}"--}}
{{--                                                aria-controls="collapse-{{ $loop->iteration }}">--}}
{{--                                            {{ __('ogp.subject_area_no', ['number' => $loop->iteration]) }} - {{ $row->area->name }}--}}
{{--                                        </button>--}}
{{--                                    </h2>--}}
{{--        --}}{{--                            @class(["accordion-collapse", "collapse", "show" => $loop->first])--}}
{{--                                    <div id="collapse-evaluation-{{ $loop->iteration }}" @class(["accordion-collapse", "collapse"])--}}
{{--                                    aria-labelledby="heading_evaluation_{{ $loop->iteration }}" data-parent="#accordionExampleEvaluation" style="">--}}
{{--                                        @if($row->arrangements->count() && ((sizeof($uniqueEvaluation) > 1 || !is_null($uniqueEvaluation[0])) || (sizeof($uniqueEvaluationStatus) > 1 || !is_null($uniqueEvaluationStatus[0]))))--}}
{{--                                            @php($hasEvaluation = true)--}}
{{--                                            @foreach($row->arrangements as $a)--}}
{{--                                                <div class="accordion-body">--}}
{{--                                                    <div class="custom-card p-3 mb-2 pb-0">--}}
{{--                                                        <div class="row ">--}}
{{--                                                            <div class="document-info-body">--}}
{{--                                                                <hr class="custom-hr mb-2">--}}
{{--                                                                <h3 class="fs-18">{{ trans_choice('custom.arrangement', 1) }}: {{ $a->name }}</h3>--}}
{{--                                                                <hr class="custom-hr mb-2">--}}
{{--                                                                @if($a->evaluation)--}}
{{--                                                                    <p>--}}
{{--                                                                        <strong>{{ __('ogp.evaluation') }}:</strong> {!! $a->evaluation !!}--}}
{{--                                                                    </p>--}}
{{--                                                                @endif--}}
{{--                                                                @if($a->evaluation_status)--}}
{{--                                                                    <p>--}}
{{--                                                                        <strong>{{ __('custom.status') }}:</strong> {!! $a->evaluation_status !!}--}}
{{--                                                                    </p>--}}
{{--                                                                @endif--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}
{{--                                        @if(!$hasEvaluation)--}}
{{--                                            <div class="col-12 ps-3 my-2">{{ __('ogp.no_evaluation_found') }}</div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--    --}}{{--                        @endif--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
                </div>
            </div>
        @endif

        @if($plan->developPlan)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ __('custom.develop_plan_information') }}</h4>
                    <a href="{{ route('ogp.national_action_plans.develop_plan', ['id' => $plan->id]) }}" class="main-color text-decoration-none fs-18" target="_blank">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-arrow-right-from-bracket me-2 main-color"></i> {{ $plan->developPlan->name }}
                        </span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
