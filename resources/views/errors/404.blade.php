@extends('layouts.site')

@section('pageTitle')
    {{ __('custom.page_404_text') }}
@endsection

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> {{__('custom.home')}}</a></li>
        <li class="active">{{ __('custom.page_404_text') }}</li>
    </ol>
@endsection

@section('content')
    <div class="row py-5">
        <div class="col-md-2">
        </div>
        <form class="col-md-8" action="{{ route('search') }}">
            <h2 class="mb-3 text-center fs-3">{{ __('site.page_not_found') }}</h2>
            <h3 class="mb-5 fs-18 fw-normal text-center">{{ __('site.try_search') }} <a href="{{ route('site.home') }}">{{ __('site.home_page') }}</a></h3>

            <div class="d-flex justify-content-center search-not-found-wrapper">
                <input type="text" name="search" id="not-found-search" placeholder="{{ __('site.search_in_portal') }}">
                <button type="submit" class="btn btn-primary w-auto ms-2"><i class="fas fa-search me-1"></i>{{ __('custom.search') }}</button>
            </div>
        </form>
        <div class="col-md-2">
        </div>
    </div>
@endsection
