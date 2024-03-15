@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-5 right-side-content">
        <div class="row mb-3">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ $planArea->area->name }}</h2>
                <a href="{{ isset($nationalPlanSection) ? route('ogp.national_action_plans.develop_plan', ['id' => $plan->plan]) : route('ogp.develop_new_action_plans') }}" title="{{ __('custom.back') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left read-more"></i> {{ __('custom.back') }}
                </a>
            </div>
        </div>
        @can('newOffer', $plan)
            <div class="row mb-4">
                <div class="col-md-12">
{{--                    <h3 class="obj-title">{{ __('ogp.template_new_proposal') }}</h3>--}}
                    <p class="fs-18">{{ __('ogp.template_new_proposal_info') }}</p>

                    <button class="btn btn-success">
                        <i class="fa fa-solid fa-download me-1"></i> {{ __('ogp.download_template_new_proposal') }}
                    </button>
                </div>
            </div>
            @include('site.ogp.develop_new_action_plan.new_offer', ['planArea' => $planArea, 'offer' => null])
        @endcan
        @if($planArea->offers->count())
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="obj-title mb-4">{{ __('ogp.list_all_proposals') }}</h3>
                    @foreach($planArea->offers()->orderBy('created_at', 'desc')->get() as $item)
                        @include('site.ogp.develop_new_action_plan.ogp_are_offer_row')
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
