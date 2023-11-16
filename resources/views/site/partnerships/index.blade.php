@extends('layouts.site')

@section('pageTitle', 'Списък на физическите и юридическите лица')

@section('content')
    <div class="col-lg-12  home-results home-results-two " style="padding: 0px !important;">
        <div class="row mb-2">
            <div class="col-12 mt-2">
                <div class="info-consul text-start">
                    <p class="fw-600">
                        Общо 98 резултата
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder">
                            <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                           title="Европейски механизъм за върховенството на закона">
                                            <h3>България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин</h3>
                                        </a>
                                    </div>
                                </div>
                                <span class="main-color">Категория:</span>
                                <a href="#" title="Оценка на въздействието" class="text-decoration-none mb-3">
                                    Координация на инициатива "Партньорство за открито управление"
                                </a>
                                <div class="meta-consul">
                                <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                                                title="Публикувано"></i> 30.06.2023 г.</span>
                                    <a href="#">
                                        <i class="fas fa-arrow-right read-more"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder">
                            <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                           title="Европейски механизъм за върховенството на закона">
                                            <h3>Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“</h3>
                                        </a>
                                    </div>
                                </div>
                                <span class="main-color">Категория:</span>
                                <a href="#" title="Оценка на въздействието" class="text-decoration-none mb-3">
                                    Координация на инициатива "Партньорство за открито управление"
                                </a>
                                <div class="meta-consul">
                                <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                                                title="Публикувано"></i> 08.09.2023 г.</span>
                                    <a href="#">
                                        <i class="fas fa-arrow-right read-more"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder">
                            <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                           title="Европейски механизъм за върховенството на закона">
                                            <h3>Междинен доклад за изпълнението на ІV НПД по инициативата „Партньорство за открито управление“ (в рамките на Независимия механизъм за оценка)</h3>
                                        </a>
                                    </div>
                                </div>
                                <span class="main-color">Категория:</span>
                                <a href="#" title="Оценка на въздействието" class="text-decoration-none mb-3">
                                    Координация на инициатива "Партньорство за открито управление"
                                </a>
                                <div class="meta-consul">
                                <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                                                title="Публикувано"></i> 18.07.2023 г.</span>
                                    <a href="#">
                                        <i class="fas fa-arrow-right read-more"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <nav aria-label="Page navigation example">
                <ul class="pagination m-0">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">57</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div>
        <div>
        </div>
    </div>
@endsection
