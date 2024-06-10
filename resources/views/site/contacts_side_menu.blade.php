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
                            <li class="mb-2">
                                <a href="{{ route('contacts') }}" class="@if(request()->route()->getName() == 'contacts' && empty($section))) active-item-left text-white p-1 @else link-dark @endif text-decoration-none"
                                >{{ trans_choice('site.administrators', 2) }}</a>
                            </li>
                            @foreach(['public-consultations', 'advisory-boards', 'strategy-documents', 'ogp', 'pris'] as $s)
                                <li class="mb-2">
                                    <a href="{{ route('contacts', $s) }}" class="@if(request()->route()->getName() == 'contacts' && str_contains(url()->current(), $s)) active-item-left text-white p-1 @else link-dark @endif text-decoration-none"
                                    >{{ __('site.contacts.module.'.$s) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <hr class="custom-hr">
    </div>
</div>
