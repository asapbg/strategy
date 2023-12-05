<div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ __('custom.home') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                            <li class="mb-2  active-item-left p-1"><a href="#"
                                                                      class="link-dark text-decoration-none">Законодателни
                                    инициативи</a>
                            </li>
                            <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                    управление</a>
                            </li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Планове
                                        </a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#"
                                                        class="link-dark  text-decoration-none">Отчети</a>
                                    </li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                            <li class="mb-2 @if(str_contains(url()->current(),'polls')) active-item-left p-1 @endif"><a href="{{ route('poll.index') }}" class="link-dark text-decoration-none" title="{{ trans_choice('custom.polls', 2) }}">{{ trans_choice('custom.polls', 2) }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="custom-hr">
            </ul>
        </div>
    </div>

</div>
