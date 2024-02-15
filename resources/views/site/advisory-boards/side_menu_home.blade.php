<div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ trans_choice('custom.advisory_boards', 2) }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.index') }}" class="@if(request()->route()->getName() == 'advisory-boards.index') active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('site.adv_board_all') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.info') }}" class="@if(str_contains(url()->current(),'information')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('site.base_info') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.documents') }}" class="@if(str_contains(url()->current(),'documents')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.documents', 2) }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.news') }}" class="@if(str_contains(url()->current(),'news')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.news', 2) }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.contacts') }}" class="@if(str_contains(url()->current(),'contacts')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.contacts', 2) }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="custom-hr">
            </ul>
        </div>
    </div>
</div>
