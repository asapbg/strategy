@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')
    <div class="col-lg-10 py-2 right-side-content">
        <div class="row mb-3">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ $planArea->area->name }}</h2>
                <a href="{{ url()->previous() }}" title="{{ __('custom.back') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left read-more"></i> {{ __('custom.back') }}
                </a>
            </div>
        </div>
        @if($offer)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="obj-title mb-4">{{ trans_choice('ogp.proposals', 1) }}</h3>
                    @include('site.ogp.develop_new_action_plan.ogp_are_offer_row_full', ['item' => $offer])
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
