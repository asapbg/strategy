@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<section class="public-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">

                <div class="left-nav-panel" style="background: #fff !important;">
                    <div class="flex-shrink-0 p-2">
                        <ul class="list-unstyled">
                            <li class="mb-1">
                                <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                    <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                                </a>
                                <hr class="custom-hr">
                                <div class="collapse show mt-3" id="home-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                        <li class="mb-2  active-item-left p-1"><a href="#"
                                                class="link-dark text-decoration-none">Законодателни инициативи</a>
                                        </li>
                                        <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                                управление</a>
                                        </li>
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                            <ul class="list-unstyled ps-3">
                                                <hr class="custom-hr">
                                                <li class="my-2"><a href="#"
                                                        class="link-dark  text-decoration-none">Планове
                                                    </a></li>
                                                <hr class="custom-hr">
                                                <li class="my-2"><a href="#"
                                                        class="link-dark  text-decoration-none">Отчети</a>
                                                </li>
                                                <hr class="custom-hr">
                                            </ul>
                                        </ul>

                                        <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
                                        </li>


                                    </ul>
                                </div>
                            </li>
                            <hr class="custom-hr">
                        </ul>
                    </div>
                </div>

            </div>


     



            </div>
        </div>


    </div>
    </div>
</section>
</body>


@endsection
