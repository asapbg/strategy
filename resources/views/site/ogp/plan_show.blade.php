@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-5 right-side-content">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ $plan->name }}</h2>
            </div>
            @can('update', $plan)
                <div class="row edit-consultation m-0">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.ogp.plan.edit', ['id' => $plan->id]) }}" class="btn btn-sm btn-primary main-color">
                            <i class="fas fa-pen me-2 main-color"></i> {{ __('custom.edit_ogp_area') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="col-md-12 text-start">
                <button class="btn btn-primary  main-color">
                    <i class="fa-solid fa-download main-color me-2"></i>Експорт</button>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-square-rss text-warning me-2"></i>RSS</button>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('ogp.start_of_execution') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ displayDate($plan->from_date) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('ogp.start_of_execution') }}</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18"></i>
                        {{ displayDate($plan->to_date) }}
                    </span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">{{ __('custom.status') }}</h3>
                <span class="main-color text-decoration-none fs-18">
                    <span class="{{ $plan->status->css_class }} fs-16">{{ $plan->status->name }}</span>
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <h3 class="mb-2 fs-5">{{ __('ogp.plan_sublevel') }}</h3>
                {!! $plan->content !!}
            </div>
        </div>
        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                <div class="accordion" id="accordionExample">
                    @foreach($plan->areas as $row)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_{{ $loop->iteration }}">
                            <button class="accordion-button text-dark fs-18 fw-600" type="button"
                                    data-toggle="collapse" data-target="#collapse-{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="collapse-{{ $loop->iteration }}">
                                {{ __('ogp.subject_area_no', ['number' => $loop->iteration]) }} - {{ $row->area->name }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $loop->iteration }}" @class(["accordion-collapse", "collapse", "show" => $loop->first])
                             aria-labelledby="heading_{{ $loop->iteration }}" data-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="custom-card p-3 mb-2 pb-0">
                                    <div class="row ">
                                        <div class="document-info-body">
                                            <div class="row mb-3">
                                                <div class="col-m-12">
                                                    <a href="{{ route('ogp.develop_new_action_plans.area', ['plan' => $row->ogp_plan_id, 'planArea' => $row->ogp_area_id]) }}" class="float-end text-decoration-none">{{ __('custom.view') }} <i class="fas fa-arrow-right read-more"></i></a>
                                                </div>
                                            </div>
                                            @foreach($row->arrangements as $a)
                                                {!! $a->content !!}
                                                @if($a->npo_partner)
                                                <p>
                                                    <strong>{{ __('ogp.npo_partner') }}</strong> {{ $a->npo_partner }}
                                                </p>
                                                @endif
                                                @if($a->responsible_administration)
                                                <p>
                                                    <strong>{{ __('ogp.responsible_administration') }}</strong> {{ $a->responsible_administration }}
                                                </p>
                                                @endif
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
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
