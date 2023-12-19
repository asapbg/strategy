@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.ogp.sidemenu')
    <div class="col-lg-10 py-5 right-side-content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ $planArea->area->name }}</h2>
                <a href="{{ route('ogp.develop_new_action_plans.show', $planArea->ogp_plan_id) }}" title="{{ __('custom.back') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left read-more"></i> {{ __('custom.back') }}
                </a>
            </div>
        </div>
        @include('site.ogp.develop_new_action_plan.new_offer', ['planArea' => $planArea, 'offer' => $offer])
    </div>
</div>
@endsection
