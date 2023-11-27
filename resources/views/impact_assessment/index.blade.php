@extends('layouts.site', ['fullwidth' => true])

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @include('impact_assessment.sidebar')
            <div class="col-lg-9  col-md-8 home-results home-results-two pris-list py-5">
                <div class="row filter-results mb-2">
                    <h2 class="mb-4 col-12">
                        Търсене
                    </h2>

                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="consultation-text" class="form-label">Търсене в наименование</label>
                                <input type="text" class="form-control" id="consultation-text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 action-btn-wrapper">
                    <div class="col-md-3 col-sm-12">
                        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                    </div>
                    <div class="col-md-9 text-end col-sm-12">
                        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
                            Абониране</button>
                        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-body">
                                    <a href="#" class="consul-item">
                                    </a><p class="mb-1"><a href="#" class="consul-item">
                                        </a><a href="#" class="main-color text-decoration-none fs-5">Законодателна програма на Министерски съвет за периода 01 юли - 31 декември 2018</a>
                                    </p>
                                    <div class="meta-consul">
                                        <span>Наименование на закнопроект... </span>
                                        <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-body">
                                    <a href="#" class="consul-item">
                                    </a><p class="mb-1"><a href="#" class="consul-item">
                                        </a><a href="#" class="main-color text-decoration-none fs-5">Законодателна програма на Министерски съвет за периода 01 януари - 30 юни 2019</a>
                                    </p>
                                    <div class="meta-consul">
                                        <span>Наименование на закнопроект... </span>
                                        <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-body">
                                    <a href="#" class="consul-item">
                                    </a><p class="mb-1"><a href="#" class="consul-item">
                                        </a><a href="#" class="main-color text-decoration-none fs-5">Законодателна програма на Министерски съвет за периода 01 юли - 31 декември 2019</a>
                                    </p>
                                    <div class="meta-consul">
                                        <span>Наименование на закнопроект... </span>
                                        <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
