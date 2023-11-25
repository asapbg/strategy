<div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2 @if(str_contains(url()->current(),'legislative-programs') || str_contains(url()->current(),'operational-programs')) active-item-left @endif p-1"><a href="#" class="link-dark text-decoration-none">Планиране</a></li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="{{ route('lp.index') }}" class="@if(str_contains(url()->current(),'legislative-programs')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ trans_choice('custom.legislative_program',1) }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="{{ route('op.index') }}" class="@if(str_contains(url()->current(),'operational-programs')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ trans_choice('custom.operational_program',1) }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>

                            <li class="mb-2 @if(str_contains(url()->current(),'pris')) active-item-left @endif p-1"><a href="{{ route('pris.index') }}" class="link-dark text-decoration-none">Актове на МС</a></li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
{{--                                    active-item-left p-1 text-white--}}
                                    <li class="my-2"><a href="#" class="link-dark text-decoration-none">Постановления</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Решения</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Становища</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Протоколи</a></li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                        </ul>
                    </div>
                </li>
                <li class="mb-2"><a href="#" class="link-dark  text-decoration-none">Архив</a></li>
            </ul>
        </div>
        <hr class="custom-hr">
    </div>
</div>
