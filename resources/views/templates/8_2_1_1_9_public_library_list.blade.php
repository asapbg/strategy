@extends('layouts.site')

@section('pageTitle', 'Списък на физическите и юридическите лица')

@section('content')
    <div class="col-lg-12  home-results home-results-two " style="padding: 0px !important;">
        <hr>
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Категория</label>
                        <select class="form-select" aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Категория 1</option>
                            <option value="1">Категория 2</option>
                            <option value="1">Категория 3</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Публикувана след:</label>
                        <input type="text" class="form-control datapicker">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Публикувана преди:</label>
                        <input type="text" class="form-control datapicker">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-8">
                <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>

            <div class="col-md-4">
                <div class="info-consul">
                    <h4>
                        Общо 98 резултата
                    </h4>
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
                            <a href="#" class="consul-item">
                                <h3>Европейски механизъм за върховенството на закона</h3>
                                <p><i class="fas fa-sitemap me-1 dark-blue" title="Сфера на действие"></i>Оценка на въздействието</p>
                                <div class="anotation text-secondary mb-2">
                                    Европейският механизъм за върховенството на закона осигурява процес на  диалог относно принципите на върховенството на закона между Европейската Комисията, Съвета на ЕС и Европейския парламент съвместно с държавите от Европейския съюз, включително с техните правителства, гражданското общество и други заинтересовани страни.
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Публикувано"></i> 30.06.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
                            <a href="#" class="consul-item">
                                <h3>Доклади за състоянието на администрацията</h3>
                                <p><i class="fas fa-sitemap me-1 dark-blue" title="Сфера на действие"></i>Стратегическо планиране</p>
                                <div class="anotation text-secondary mb-2">
                                    *Публикацията е обновена през месец юни 2023 г.
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Публикувано"></i> 19.6.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
                            <img class="img-thumbnail" src="{{ asset('\img\templates\library_img_1.jpg') }}">
                        </div>
                        <div class="consult-body">
                            <a href="#" class="consul-item">
                                <h3>Доклади на Комитета за регулаторен контрол към Европейската комисия</h3>
                                <p><i class="fas fa-sitemap me-1 dark-blue" title="Сфера на действие"></i>Оценка на въздействието</p>
                                <div class="anotation text-secondary mb-2">
                                    Комитетът за регулаторен контрол е създаден от Европейската комисия през 2015 г., като заменя Комитета по оценка на въздействието и има по-широки отговорности от него. Той е независим орган на Комисията, съставен от нейни служители и експерти извън нея, за да съветва комисарите.
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Публикувано"></i> 07.04.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
@endsection
