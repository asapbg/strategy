@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на Потребител')

@section('content')
<div class="row mt-5">

    {слайдер} <br>
    {пътечки}
</div>
<div class="row">
    <div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>{Профил на Потребител}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Обща информация</a>
                                </li>
                                <li class="mb-2"><a href="#"
                                        class="link-dark text-decoration-none">Абонаменти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Законодателни
                                        инициативи</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Оценки на
                                        въздействието</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Публикувани
                                        коментари</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 right-side-content py-2">
    </div>

</div>
</body>


@endsection
