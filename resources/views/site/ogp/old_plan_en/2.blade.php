@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-5 right-side-content">
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
                @if(isset($planData) && isset($planData['content']) && sizeof($planData['content']))
                    @foreach($planData['content'] as $c)
                        <h4 class="custom-left-border mb-3 @if(!$loop->first) mt-3 @endif">{{ $c['title'] }}</h4>
                        {!! $c['content'] !!}
                    @endforeach
                @endif
            </div>
        </div>
        @if(isset($planData) && isset($planData['files']) && sizeof($planData['files']))
            <div class="row mb-5">
                <div class="col-md-12 pt-3">
                    @foreach($planData['files'] as $f)
                        <a class="d-inline-block w-100" href="{{ route('ogp.national_action_plans.old.file').'?file='.$f['path'] }}" target="_blank">{!! $f['icon'] !!} {{ $f['name'] }}</a>
                        {{--                <a download="{{ asset($f['path']) }}" href="#" target="_blank">{{ $f['name'] }}</a>--}}
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($planData) && isset($planData['arrangements']) && sizeof($planData['arrangements']))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="custom-left-border mb-3">{{ trans_choice('ogp.commitments', 2) }}</h4>
                    <div class="accordion" id="accordionExample">
                        @foreach($planData['arrangements'] as $a)
                            <div class="accordion-body">
                                <div class="custom-card p-3 mb-2 pb-0">
                                    <div class="row ">
                                        <div class="document-info-body">
                                            <hr class="custom-hr mb-2">
                                            <h3 class="fs-18">{{ __('ogp.ogp_plan_arrangement_commitments') }}</h3>
                                            <hr class="custom-hr mb-2">
                                            <p>
                                                <strong>{{ trans_choice('ogp.commitments', 1) }}:</strong> {{ $a['name'] ?? '---' }}
                                            </p>
                                            @if(isset($a['leading_institution']) && !empty($a['leading_institution']))
                                                <p>
                                                    <strong>{{ __('ogp.leading_institution') }}:</strong>
                                                    {!! $a['leading_institution'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['other_person']) && !empty($a['other_person']))
                                                <p>
                                                    <strong>{{ __('ogp.other_person') }}:</strong>
                                                    {!! $a['other_person'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['problem_question']) && !empty($a['problem_question']))
                                                <p>
                                                    <strong>{{ __('ogp.problem_question') }}:</strong>
                                                    {!! $a['problem_question'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['base_goal']) && !empty($a['base_goal']))
                                                <p>
                                                    <strong>{{ __('ogp.base_goal') }}:</strong>
                                                    {!! $a['base_goal'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['challenge']) && !empty($a['challenge']))
                                                <p>
                                                    <strong>{{ __('ogp.challenge') }}:</strong>
                                                    {!! $a['challenge'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['progress']) && sizeof($a['progress']))
                                                <p>
                                                    <strong>{{ __('ogp.progress') }}:</strong>
                                                </p>
                                                @foreach($a['progress'] as $k => $v)
                                                    <p>
                                                        <strong>{{ $k }}:</strong>
                                                        {!! $v !!}
                                                    </p>
                                                @endforeach
                                            @endif
                                            @if(isset($a['check_arrangements']) && sizeof($a['check_arrangements']))
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="4">{{ __('ogp.check_arrangements') }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('custom.name') }}</th>
                                                            <th>Нов или съществуващ ангажимент</th>
                                                            <th>Начална дата</th>
                                                            <th>Крайна дата</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($a['check_arrangements'] as $ca)
                                                            <tr>
                                                                <td>{!! $ca['name'] !!}</td>
                                                                <td>{!! $ca['exist'] !!}</td>
                                                                <td>{!! $ca['from_date'] !!}</td>
                                                                <td>{!! $ca['to_date'] !!}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <p>
                                                    <strong>{{ __('ogp.progress') }}:</strong>
                                                </p>
                                                @foreach($a['progress'] as $k => $v)
                                                    <p>
                                                        <strong>{{ $k }}:</strong>
                                                        {!! $v !!}
                                                    </p>
                                                @endforeach
                                            @endif




                                            @if(isset($a['institution']) && !empty($a['institution']))
                                                <p>
                                                    <strong>{{ __('ogp.responsible_institution') }}:</strong>
                                                    {!! $a['institution'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['end_deadline']) && !empty($a['end_deadline']))
                                                <p>
                                                    <strong>{{ __('ogp.deadline') }}:</strong>
                                                    {!! $a['end_deadline'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['finance']) && !empty($a['finance']))
                                                <p>
                                                    <strong>{{ __('ogp.finance') }}:</strong>
                                                    {!! $a['finance'] !!}
                                                </p>
                                            @endif
                                            @if(isset($a['indicators']) && !empty($a['indicators']))
                                                <p>
                                                    <strong>{{ __('ogp.indicators') }}:</strong>
                                                    {!! $a['indicators'] !!}
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
