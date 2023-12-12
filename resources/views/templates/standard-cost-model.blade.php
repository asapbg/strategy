@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                           data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Методи за анализ на въздействията
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                <li class="mb-2 p-1">
                                    <a href="" class="link-dark text-decoration-none">Калкулатор за
                                        изчисляване на административната тежест</a>
                                </li>
                                <li class="mb-2 active-item-left p-1">
                                    <a href="" class="link-dark text-decoration-none">Модел на стандартните
                                        разходи</a>
                                </li>
                                <li class="mb-2 p-1">
                                    <a href="" class="link-dark text-decoration-none">Анализ на разходите и
                                        ползите</a>
                                </li>
                                <li class="mb-2 p-1">
                                    <a href="" class="link-dark text-decoration-none">Анализ на
                                        ефективността на разходите</a>
                                </li>
                                <li class="mb-2 p-1">
                                    <a href="" class="link-dark text-decoration-none">Мултикритериен
                                        анализ</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-10 right-side-content py-5">
        <div class="custom-card py-4 px-3 mb-5">
            <div class="col-md-12">
                <h4 class="mb-2">Информация</h4>
                <p>
                    МСР служи предимно за определяне на административната тежестза бизнеса, наложена от регулациите. Моделът измерва основно два типа разходи за регулиране – финансови и административни. 
            </div>
        </div>

        <div class="col-md-12 col-lg-12 custom-card p-3 col-sm-12">
            @if(!request()->has('step') || request()->offsetGet('step') === '1')
                <form action="">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="description-change" class="form-label">Име на административна
                                    тежест</label>
                                <div class="summernote-wrapper">
                                    <textarea class="summernote" id="description-change"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="user-email" class="form-label">Брой дейности</label>
                                <input id="user-email" type="number" class="form-control" value="    "/>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="location.href='?step=2'">Продължи
                    </button>
                </form>
            @endif

            @if(request()->has('step') || request()->offsetGet('step') === '2')
                <form action="">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="description-change" class="form-label">Име на дейност</label>
                                <div class="summernote-wrapper">
                                    <textarea class="summernote" id="description-change"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="" class="form-label">Брой часове, необходими за извършването на дейността</label>
                                <input type="number" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="" class="form-label">Средна месечна работна заплата на човека, който трябва да извърши дейността</label>
                                <input type="number" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="" class="form-label">Брой на фирмите, които трябва да извършат дейността</label>
                                <input type="number" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="" class="form-label">Брой пъти на година, които бизнесът трябва да извърши дейността</label>
                                <input type="number" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 d-none" id="result">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="result" class="form-label">Общ административен товар за една година за МВР</label>
                                <input disabled id="result" type="number" class="form-control" value="0.1"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-auto">
                            <a href="/img/13-Rakovostvo.pdf" target="_blank" class="btn btn-primary">Печат PDF</a>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onclick="">Печат</button>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onclick="document.querySelector('#result').classList.remove('d-none')">Изчисли</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
</body>


@endsection
