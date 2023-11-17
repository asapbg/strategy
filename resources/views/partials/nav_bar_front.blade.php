<nav class="navbar navbar-expand-lg justify-content-center d-flex ">
    <div class="container-fluid">
        <button class=" ms-auto navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarTogglerDemo02">
            <ul class="navbar-nav ">
                <li class="nav-item home-icon-menu">
                    <a class="nav-link @if(request()->route()->getName() == 'home') active @endif" aria-current="page"
                        href="/"><i class="bi bi-house-door-fill text-light"><span class="d-none">Home</span></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'public-consultation')) active @endif"
                        href="{{ route('public_consultation.index') }}">{{ __('site.menu.public_consultation') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('impact_assessment.index') }}" type="button">Оценки на въздействието</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pris.index') }}">{{ __('site.menu.pris') }}</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link" type="button">Гражданско участие
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('strategy-documents.index') }}">Стратегически документи</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" type="button">
                        Консултативни съвети
                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link" type="button">Библиотека
                    </a>
                </li>
            </ul>
            <li class="nav-item d-flex list-unstyled text-end align-items-center"
                style="padding-right: 0px !important;">
                <a class="nav-link me-3" href="#"><i class="fa-brands fa-facebook text-light"><span
                            class="d-none">Facebook</span></i></a>
                <a class="nav-link" href="#">EN</a>
            </li>

        </div>
    </div>
</nav>
