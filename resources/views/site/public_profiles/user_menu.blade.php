<div class="col-lg-2 side-menu pt-2 mt-1" style="background:#f5f9fd;">
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
                            <li class="mb-2 @if(str_contains(url()->current(),'user-profile/public-consultations')) active-item-left p-1 @endif">
                                <a href="{{ route('user.profile.pc', $item) }}" class="link-dark text-decoration-none" title="{{ trans_choice('custom.public_consultations', 2) }}">{{ trans_choice('custom.public_consultations', 2) }}</a>
                            </li>
                            <li class="mb-2 @if(str_contains(url()->current(),'user-profile/legislative-initiatives')) active-item-left p-1 @endif">
                                <a href="{{ route('user.profile.li', $item) }}" class="link-dark text-decoration-none" title="{{ trans_choice('custom.legislative_initiatives', 2) }}">{{ trans_choice('custom.legislative_initiatives', 2) }}</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <hr class="custom-hr">
    </div>
</div>
