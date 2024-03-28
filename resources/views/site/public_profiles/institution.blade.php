@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.public_profiles.institution_menu')
        <div class="col-lg-10 right-side-content py-5">
            <div class="col-md-12">
                <h2 class="mb-4">{{ __('custom.main_information') }}</h2>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-hashtag main-color me-1"></i>{{ __('custom.eik') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <span>{{ !empty($item->eik) && $item->eik != 'NA' ? $item->eik : '---' }}</span>
                </div>
            </div>
            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-arrow-right-from-bracket main-color me-1"></i>{{ __('site.section') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <a href="#" class="text-decoration-none">{{ $item->level?->name }}</a>
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-circle-check main-color me-1"></i>{{ __('custom.status') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <a href="#"><span class="pris-tag-active">{{ $item->active ? __('custom.active_f') : __('custom.inactive_f') }}</span></a>
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-copyright main-color me-1"></i>{{ __('custom.name') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ $item->name }}
                </div>
            </div>


            <div class="col-md-12 mt-5">
                <h2 class="mb-4">{{ __('site.correspondences_address') }}</h2>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-city main-color me-1"></i>{{ trans_choice('custom.areas', 1) }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <span>{{ $item->area ? $item->area->ime : '---' }}</span>
                </div>
            </div>
            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-city main-color me-1"></i>{{ trans_choice('custom.municipalities', 1) }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <span>{{ $item->municipal ? $item->municipal->ime : '---' }}</span>
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-house main-color me-1"></i>{{ trans_choice('custom.settlements', 1) }}
                </div>

                <div class="col-md-9 pris-left-column">
                    <span>{{ $item->settlement ? $item->settlement->ime : '---' }}</span>
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-location-dot main-color me-1"></i>{{ __('custom.address') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ $item->address }}
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-inbox main-color me-1"></i>{{ __('custom.zip_code') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ !empty($item->zip_code) ? $item->zip_code : '---' }}
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-phone main-color me-1"></i>{{ __('custom.phone') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ !empty($item->phone) ? $item->phone : '---' }}
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-fax main-color me-1"></i>{{ __('custom.fax') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ !empty($item->fax) ? $item->fax : '---' }}
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-envelope main-color me-1"></i>{{ __('custom.email') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {{ !empty($item->email) ? $item->email : '---' }}
                </div>
            </div>

            <div class="row pris-row pb-2 mb-2">
                <div class="col-md-3 pris-left-column">
                    <i class="fa-solid fa-circle-info main-color me-1"></i>{{ __('custom.extra_info') }}
                </div>

                <div class="col-md-9 pris-left-column">
                    {!! !empty($item->add_info) ? $item->add_info : '<p>---</p>' !!}
                </div>
            </div>
        </div>
    </div>
@endsection
