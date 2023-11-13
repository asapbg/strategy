<nav class="navbar navbar-expand-lg justify-content-center d-flex ">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarTogglerDemo02">
            <ul class="navbar-nav ">
                <li class="nav-item" style="padding-left:0px !important;">
                    <a class="nav-link @if(request()->route()->getName() == 'home') active @endif" aria-current="page" href="/"><i class="bi bi-house-door-fill text-light"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'public-consultation')) active @endif" href="{{ route('public_consultation.index') }}">{{ __('site.menu.public_consultation') }}</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" type="button" data-toggle="dropdown">Оценки на въздействието
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu p-1 ">
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Инструменти</a>
                            <ul class="sub-menu-three list-unstyled ps-2">
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Калкулатор</a></li>
                            </ul>
                        </li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Образци и форми</a>
                            <ul class="sub-menu-three list-unstyled ps-2">
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Частична предварителна</a></li>
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Цялостна предварителна-резюме</a></li>
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Цялостна предварителна-доклад</a></li>
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Последваща</a></li>
                            </ul>
                        </li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Библиотека</a></li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Списък на изготвящи оценки по ЗНА</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" type="button" data-toggle="dropdown">{{ __('site.menu.pris') }}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu p-1 ">
                        <li class="nav-item ">
                            <a tabindex="-1" href="#" class="text-decoration-none main-color">Планиране</a>
                            <ul class="sub-menu-three list-unstyled ps-2">
                                <li><a tabindex="-1" href="{{ route('lp.index') }}" class="text-decoration-none main-color">Законодателна програма</a></li>
                                <li><a tabindex="-1" href="{{ route('op.index') }}" class="text-decoration-none main-color">Оперативна програма</a></li>
                            </ul>
                        </li>
                        <li class="nav-item "><a tabindex="-1" href="{{ route('pris.index') }}" class="text-decoration-none main-color">Актове</a>
                            <ul class="sub-menu-three list-unstyled ps-2">
                                <li><a tabindex="-1" href="{{ route('pris.index').'?category=1' }}" class="text-decoration-none main-color">Постановления</a></li>
                                <li><a tabindex="-1" href="{{ route('pris.index').'?category=2' }}" class="text-decoration-none main-color">Решения</a></li>
                                <li><a tabindex="-1" href="{{ route('pris.index') }}" class="text-decoration-none main-color">Становища</a></li>
                                <li><a tabindex="-1" href="{{ route('pris.index').'?category=5' }}" class="text-decoration-none main-color">Протоколи</a></li>
                            </ul>
                        </li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Архив</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" type="button" data-toggle="dropdown">Гражданско участие
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu p-1 ">
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Законодателни инициативи</a></li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Отворено управление</a>
                            <ul class="sub-menu-three list-unstyled ps-2">
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Планове</a></li>
                                <li><a tabindex="-1" href="#" class="text-decoration-none main-color">Отчети</a></li>
                            </ul>
                        </li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Анкети</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('strategy_documents') }}">Стратегически документи</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" type="button" data-toggle="dropdown">Консултативни съвети
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu p-1 ">
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Висш експертен екологичен съвет</a></li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Висш консултативен съвет по водите</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" type="button" data-toggle="dropdown">Библиотека
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu p-1 ">
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Публикации</a></li>
                        <li class="nav-item "><a tabindex="-1" href="#" class="text-decoration-none main-color">Новини</a></li>
                    </ul>
                </li>
            </ul>
            <li class="nav-item d-flex list-unstyled text-end align-items-center" style="padding-right: 0px !important;">
                <a class="nav-link me-3" href="#"><i class="fa-brands fa-facebook text-light"></i></a>
                <a class="nav-link" href="#">EN</a>
            </li>

        </div>
    </div>
</nav>
