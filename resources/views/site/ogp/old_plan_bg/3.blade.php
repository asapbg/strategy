@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-2 right-side-content">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ $planName }}</h2>
            </div>
            {{--    <div class="col-md-12 text-start">--}}
            {{--        <a href="{{ route('ogp.national_action_plans.export.old', $planId) }}" class="btn btn-primary  main-color"><i class="fa-solid fa-download main-color me-2"></i>{{ __('custom.export_as_pdf') }}</a>--}}
            {{--    </div>--}}
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('custom.from_date') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ \App\Enums\OldNationalPlanEnum::fromDateByValue($planId) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('custom.to_date') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ \App\Enums\OldNationalPlanEnum::toDateByValue($planId) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('custom.status') }}</h3>
                <span class="main-color text-decoration-none fs-18">
                    <span class="{{ $status->css_class }} fs-16">{{ $status->name }}</span>
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 pt-3">
                @if(isset($planData) && isset($planData['content']) && !empty($planData['content']))
                    {!! $planData['content'] !!}
                @endif
            </div>
        </div>
        @if(isset($planData) && isset($planData['files']) && sizeof($planData['files']))
            <div class="row mb-5">
                <div class="col-md-12 pt-3">
                    @foreach($planData['files'] as $f)
                        <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-url="{{ route('modal.file_preview_static_page').'?path='.$f['path'] }}">
                            {!! $f['icon'] !!} {{ $f['name'] }}
                        </a>
{{--                        <a class="d-inline-block w-100" href="{{ route('ogp.national_action_plans.old.file').'?file='.$f['path'] }}" target="_blank">{!! $f['icon'] !!} {{ $f['name'] }}</a>--}}
                        {{--                <a download="{{ asset($f['path']) }}" href="#" target="_blank">{{ $f['name'] }}</a>--}}
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($planData) && isset($planData['arrangements']) && sizeof($planData['arrangements']))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ trans_choice('ogp.commitments', 2) }}</h4>
                    <div class="accordion" id="accordionExampleEvaluation">
                        @foreach($planData['arrangements'] as $area => $row)
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="heading_evaluation_{{ $loop->iteration }}">
                                    <button class="accordion-button text-dark fs-18 fw-600" type="button"
                                            data-toggle="collapse" data-target="#collapse-evaluation-{{ $loop->iteration }}"
                                            aria-controls="collapse-{{ $loop->iteration }}">
                                        {{ __('ogp.subject_area_no', ['number' => $loop->iteration]) }} - {{ $area }}
                                    </button>
                                </h2>
                                <div id="collapse-evaluation-{{ $loop->iteration }}" @class(["accordion-collapse", "collapse"])
                                aria-labelledby="heading_evaluation_{{ $loop->iteration }}" data-parent="#accordionExampleEvaluation" style="">
                                    @foreach($row as $a)
                                        <h4 class="custom-left-border my-3 fs-18 mx-3">{{ trans_choice('ogp.commitments', 1) }}: {{ $a['commitment'] }}</h4>
                                            @foreach($a['arrangements'] as $arrange)
                                                <div class="accordion-body">
                                                    <div class="custom-card p-3 mb-2 pb-0">
                                                        <div class="row ">
                                                            <div class="document-info-body">
                                                                <hr class="custom-hr mb-2">
                                                                <h3 class="fs-18">{{ trans_choice('custom.arrangement', 1) }}: {{ $arrange['Наименование'] }}</h3>
                                                                <hr class="custom-hr mb-2">
                                                                @foreach($arrange as $fname => $fvalue)
                                                                    @if($fvalue != 'Наименование')
                                                                        <p>
                                                                            <strong>{{ $fname }}:</strong> {!! $fvalue !!}
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(isset($planData['evaluations']) && sizeof($planData['evaluations']))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ __('ogp.self_evaluation_and_report') }}</h4>
                </div>
                <div class="col-md-12 pt-3">
                    @foreach($planData['evaluations'] as $f)
                        <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-url="{{ route('modal.file_preview_static_page').'?path='.$f['path'] }}">
                            {!! $f['icon'] !!} {{ $f['name'] }}
                        </a>
{{--                        <a class="d-inline-block w-100" href="{{ route('ogp.national_action_plans.old.file').'?file='.$f['path'] }}" target="_blank">{!! $f['icon'] !!} {{ $f['name'] }}</a>--}}
                        {{--                <a download="{{ asset($f['path']) }}" href="#" target="_blank">{{ $f['name'] }}</a>--}}
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
