@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Публицаии')

@section('content')

    <div class="row">
        @includeIf('site.publications.sidemenu')

        <div class="col-lg-10  py-5 right-side-content">

            @php
                $current_page = 1;
                if (isset($publications)) {
                    $current_type = "publications";
                    $current_page = ($publications->count() > 0 ) ? $publications->currentPage() : 1;
                }
                if (isset($news)) {
                    $current_type = "news";
                    $current_page = ($news->count() > 0 ) ? $news->currentPage() : 1;
                }
            @endphp
            <form action="{{ url()->current() }}" id="search-form" METHOD="GET">
                <input type="hidden" name="search" value="true">
                <input type="hidden" name="page" class="current_page" value="{{ $current_page }}">
                <input type="hidden" name="sort" class="sort" value="DESC">
                <input type="hidden" id="model_type" value="{{ $current_type }}">
                <div class="row filter-results mb-2">
                    <h2 class="mb-4">
                        {{ __('custom.search') }}
                    </h2>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="categories" class="form-label">{{ trans_choice('custom.categories', 1) }}:</label>
                                <select class="form-select select2" name="categories[]" id="categories" multiple aria-label="{{ trans_choice('custom.categories', 1) }}">
                                    <option value="">{{ __('custom.any') }}</option>
                                    @foreach($publicationCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="keywords" class="form-label">{{ __('custom.search_in_title_content') }}</label>
                                <input type="text" id="keywords" name="keywords" class="form-control"
                                       value="{{ request()->offsetGet('keywords') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('custom.published_after_f') }}:</label>
                                <div class="input-group">
                                    <input type="text" name="published_from" autocomplete="off"
                                           id="published_from" class="form-control datepicker"
                                           value="{{ request()->offsetGet('published_from') }}">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('custom.published_before_f') }}:</label>
                                <div class="input-group">
                                    <input type="text" name="published_till" autocomplete="off"
                                           id="published_till" class="form-control datepicker"
                                           value="{{ request()->offsetGet('published_till') }}">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 action-btn-wrapper">
                    <div class="col-md-4">
                        <span id="searchBtn" class="btn rss-sub main-color search-btn">
                            <i class="fas fa-search main-color"></i> {{ __('custom.searching') }}
                        </span>
                        <span class="btn rss-sub main-color search-btn clear">
                            <i class="fas fa-eraser"></i> {{ __('custom.clearing') }}
                        </span>
                    </div>
                    <div class="col-md-8 text-end">
{{--                        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>--}}
{{--                        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>--}}
                        @canany(['manage.*', 'manage.publications'])
                            <a href="{{ route('admin.publications.edit', ['type' => $type]) }}" class="btn btn-success text-success" target="_blank">
                                <i class="fas fa-circle-plus text-success me-1"></i>{{ trans_choice('custom.adding', 1) }}
                            </a>
                        @endcan
                    </div>
                </div>

{{--                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">--}}
{{--                    <div class="col-md-3">--}}
{{--                        <p class="mb-0 cursor-pointer sort_search">--}}
{{--                            <input type="hidden" disabled class="order_by" name="order_by" value="publication_category_id">--}}
{{--                            <i class="fa-solid fa-sort me-2"></i> {{ trans_choice('custom.categories', 1) }}--}}
{{--                        </p>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3 cursor-pointer sort_search">--}}
{{--                        <input type="hidden" disabled class="order_by" name="order_by" value="title">--}}
{{--                        <p class="mb-0">--}}
{{--                            <i class="fa-solid fa-sort me-2"></i>{{ __('custom.title_content') }}--}}
{{--                        </p>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <p class="mb-0 cursor-pointer sort_search">--}}
{{--                            <input type="hidden" disabled class="order_by" name="order_by" value="published_at">--}}
{{--                            <i class="fa-solid fa-sort me-2"></i>{{ __('custom.published_at_f') }}--}}
{{--                        </p>--}}
{{--                    </div>--}}
{{--                </div>--}}

                @includeIf('partials.results-select', ['per_page_array' => [10,50,100,150,200]])

            </form>

            @if(isset($publications))
                <div id="publications-results">
                    @includeIf('site.publications.publications')
                </div>
            @endif

            @if(isset($news))
                <div id="news-results" class="row">
                    @includeIf('site.publications.news')
                </div>
            @endif

        </div>

    </div>
@endsection
@if(request()->has('categories'))
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#search-form").find('.select2').val(@json(request()->offsetGet('categories'))).trigger('change');
        });
    </script>
@endpush
@endif
