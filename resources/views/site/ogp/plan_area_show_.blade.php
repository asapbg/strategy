@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-2 right-side-content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ $planArea->area->name }}</h2>
                <a href="{{ route('ogp.develop_new_action_plans.show', $planArea->ogp_plan_id) }}" title="{{ __('custom.back') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left read-more"></i> {{ __('custom.back') }}
                </a>
            </div>
        </div>
        @can('newOffer', $plan)
            @include('site.ogp.develop_new_action_plan.new_offer', ['planArea' => $planArea, 'offer' => null])
            <div class="row mb-4 mt-5">
                <div class="col-md-12">
                    <h2 class="obj-title">{{ __('ogp.template_new_proposal') }}</h2>
                    <p>{{ __('ogp.template_new_proposal_info') }}</p>

                    <button class="btn btn-success">
                        <i class="fa fa-solid fa-download me-1"></i> {{ __('ogp.download_template_new_proposal') }}
                    </button>
                </div>
            </div>
        @endcan
        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ __('ogp.list_all_proposals') }}</h2>
                <div class="accordion" id="accordionOffer">
                    @foreach($planArea->offers()->orderBy('created_at', 'desc')->get() as $item)
                        @include('site.ogp.develop_new_action_plan.ogp_are_offer_row')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
