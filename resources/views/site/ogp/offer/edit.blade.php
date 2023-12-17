@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.ogp.sidemenu')
    <div class="col-lg-10 py-5 right-side-content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ __('ogp.proposal_by_area', ['name' => $ogpArea->name]) }}</h2>
            </div>
        </div>
        @include('site.ogp.develop_new_action_plan.new_offer', ['ogpArea' => $ogpArea, 'offer' => $offer])
        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                @include('site.ogp.develop_new_action_plan.commitments', ['item' => $offer])
            </div>
        </div>
    </div>
</div>
@endsection
