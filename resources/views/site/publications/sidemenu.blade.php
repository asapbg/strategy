<div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ __('custom.library') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2 p-1
                                @if(request()->routeIs('library.news') || $type == App\Enums\PublicationTypesEnum::TYPE_NEWS->value) active-item-left @endif"
                            >
                                <a href="{{ route('library.news') }}" class="link-dark text-decoration-none">{{ trans_choice('custom.news', 2) }}</a>
                            </li>
                            <li class="mb-2 p-1
                                @if(request()->routeIs('library.publications') || $type == App\Enums\PublicationTypesEnum::TYPE_LIBRARY->value) active-item-left @endif"
                            >
                                <a href="{{ route('library.publications') }}" class="link-dark text-decoration-none">{{ trans_choice('custom.publications', 2) }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="custom-hr">
            </ul>
        </div>
    </div>
</div>
