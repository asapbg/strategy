@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Консултативни съвети - Контакти')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Консултативни съвети
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2 active-item-left p-1 "><a href="#" class="link-dark text-decoration-none">Контакти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Актуална информация и събития</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>

    <div class="col-lg-10 py-2 right-side-content">
        <div class="row mb-2">
            <h2 class="mb-4">
                Контакти на служители от дирекция „Координация на политики и концесии"
            </h2>
            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Иван Иванов
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Началник отдел
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 124 124
                                </a><a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    ivan_ivanov@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Мария Иванова
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Старши експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 125 125
                                </a><a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    mariya_ivanova@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Николай Георгиев
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 127 127
                                </a><a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    nikolay_georgiev@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Петър Георгиев
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 128 128
                                </a><a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    petar_georgiev@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
