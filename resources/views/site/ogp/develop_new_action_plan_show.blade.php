@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', __('custom.open_government_partnership'))

@section('content')
<div class="row">
    @include('site.ogp.sidemenu')
    <div class="col-lg-10 py-5 right-side-content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">{{ $ogpArea->name }}</h2>
            </div>
        </div>
        <div class="row mt-2 mb-3 align-items-center">
            <div class="col-md-8">
                <span class="obj-icon-info me-2">
                    <span class="{{ $ogpArea->status->css_class }} me-2">{{ $ogpArea->status->name }}</span>
                    <i class="far fa-calendar me-1 dark-blue"></i> {{ displayDate($ogpArea->from_date) }} - {{ displayDate($ogpArea->to_date) }}
                </span>
            </div>
            <div class="col-md-4 text-end">
                @can('update', $ogpArea)
                <a href="{{ route('admin.ogp.area.edit', ['id' => $ogpArea->id]) }}" class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i> {{ __('custom.edit_ogp_area') }}
                </a>
                @endcan
            </div>
        </div>
        @can('createOffer', $ogpArea)
            @include('site.ogp.develop_new_action_plan.new_offer')
            <div class="row mb-4 mt-5">
                <div class="col-md-12">
                    <h2 class="obj-title">{{ __('ogp.template_new_proposal') }}</h2>
                    <p>{{ __('ogp.template_new_proposal_info') }}</p>
                    <button class="btn btn-success">
                        <i class="fa fa-solid fa-download me-1"></i> {{ __('ogp.download_template_new_proposal') }}
                    </button>
                </div>
            </div>
        @endif

        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">{{ __('ogp.list_all_proposals') }}</h2>
                <div class="accordion" id="accordionExample">
                    @foreach($ogpArea->offers()->orderBy('created_at', 'desc')->get() as $item)
                        @include('site.ogp.develop_new_action_plan.ogp_are_offer_row')
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
