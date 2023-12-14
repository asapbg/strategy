@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Публицаии')

@section('content')

    <div class="row">
        @includeIf('site.publications.sidemenu')

        <div class="col-lg-10  py-5 right-side-content">

            @php
                $current_page = 1;
                if (isset($publications)) {
                    $current_page = ($publications->count() > 0 ) ? $publications->currentPage() : 1;
                }
                if (isset($news)) {
                    $current_page = ($news->count() > 0 ) ? $news->currentPage() : 1;
                }
            @endphp
            <form action="{{ url()->current() }}" id="search-form" METHOD="GET">
                <input type="hidden" name="search" value="true">
                <input type="hidden" name="page" class="current_page" value="{{ $current_page }}">
                <input type="hidden" name="sort" class="sort" value="DESC">
                <div class="row filter-results mb-2">
                    <h2 class="mb-4">
                        Търсене
                    </h2>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Категория:</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Всички</option>
                                    <option value="1">Оценка на въздействието</option>
                                    <option value="1">Стратегическо планиране</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="title" class="form-label">Търсене в Заглавие/Съдържание</label>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="{{ request()->offsetGet('title') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Публикувана след:</label>
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
                                <label for="exampleFormControlInput1" class="form-label">Публикувана преди:</label>
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
                        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                    </div>
                    <div class="col-md-8 text-end">
                        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS Абониране</button>
                        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                        <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
                    </div>
                </div>

                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer sort_search">
                            <input type="hidden" disabled class="order_by" name="order_by" value="publication_category_id">
                            <i class="fa-solid fa-sort me-2"></i> Категория
                        </p>
                    </div>
                    <div class="col-md-3 cursor-pointer sort_search">
                        <input type="hidden" disabled class="order_by" name="order_by" value="title">
                        <p class="mb-0">
                            <i class="fa-solid fa-sort me-2"></i>Заглавие/Съдържание
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer sort_search">
                            <input type="hidden" disabled class="order_by" name="order_by" value="published_at">
                            <i class="fa-solid fa-sort me-2"></i>Публикувана на
                        </p>
                    </div>
                </div>

                @includeIf('partials.results-select', ['per_page_array' => [5,10,50,100,150,200]])

            </form>

            @if(isset($publications))
                <div id="publications-results">
                    @includeIf('site.publications.publications')
                </div>
            @endif

            @if(isset($news))
                <div id="publications-results">
                    @includeIf('site.publications.news')
                </div>
            @endif

        </div>

    </div>
@endsection
