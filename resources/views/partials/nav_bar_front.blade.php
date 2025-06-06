<nav class="navbar navbar-expand-lg justify-content-center d-flex ">
    <div class="container-fluid">
        <button class=" ms-auto navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarTogglerDemo02">
            <ul class="navbar-nav">
                <li class="nav-item home-icon-menu">
                    <a class="nav-link @if(request()->route()->getName() == 'home') active @endif" aria-current="page"
                        href="/"><i class="bi bi-house-door-fill text-light"><span class="d-none">Home</span></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'public-consultation')) active @endif"
                        href="{{ route('public_consultation.index') }}"
                    >
                        {{ __('site.menu.public_consultation') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'impact_assessment')) active @endif"
                       href="{{ route('impact_assessment.index') }}" type="button"
                    >
                        {{ trans_choice('custom.impact_assessment', 1) }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'pris') || str_contains(request()->url(), 'legislative-programs') || str_contains(request()->url(), 'operational-programs')) active @endif"
                       href="{{ route('pris.index') }}"
                    >
                        {{ __('site.menu.pris') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'legislative-initiatives') || str_contains(request()->url(), 'ogp')) active @endif"
                       href="{{ route('legislative_initiatives.index') }}" type="button"
                    >
                        {{ __('site.ogp_menu') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'strategy-documents')) active @endif"
                       href="{{ route('strategy-documents.index') }}"
                    >
                        {{ trans_choice('custom.strategic_documents', 2) }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'advisory-boards')) active @endif"
                       href="{{ route('advisory-boards.index') }}" type="button"
                    >
                        {{ trans_choice('custom.advisory_boards', 2) }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(str_contains(request()->url(), 'library/')) active @endif"
                       href="{{ route('library.publications') }}" type="button"
                    >
                        {{ __('custom.library') }}
                    </a>
                </li>

                <li class="nav-item top-bar-left-side-mobile">
                    <div class="auth d-flex justify-content-start flex-column">
                        @if(app('auth')->check())
                        <a href="" class="text-light me-3 text-decoration-none nav-link" style="padding-bottom: 10px !important;"
                        id="search-btn" data-toggle="modal" data-target="#searchModal">
                        Търсене
                        </a>
                            @if(auth()->user()->user_type == \App\Models\User::USER_TYPE_INTERNAL)
                            <a href="{{ route('admin.home') }}" class="text-light me-3 text-decoration-none nav-link" style="padding-bottom:10px !important;padding-top:10px !important;"
                                id="back-to-admin"><i class="text-light fas fa-arrow-left me-1"></i>{{ __('site.to_administration') }}</a>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle nav-link" id="profile-toggle" type="button" style="margin-top:10px !important;padding:5px 10px !important;"
                                    id="profile-menu" data-toggle="dropdown" aria-expanded="false">
                                    @php($user = app('auth')->user())
                                    {{ $user->is_org ? $user->org_name : $user->first_name . ' ' . $user->last_name }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="profile-menu">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('profile') }}">{{ trans_choice('custom.profiles', 1) }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:;"
                                            onclick="event.preventDefault();document.getElementsByClassName('logout-form')[0].submit();">
                                            {{ __('auth.logout') }}
                                        </a>
                                        <form class="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="registration justify-content-start align-items-start d-flex flex-column">
                            <a class="nav-link text-light me-3 text-decoration-none" id="register-link" style="padding-bottom:10px !important;"
                                    href="{{ route('register') }}">{{ __('custom.register') }}</a>
                                <a class="nav-link text-light me-3 text-decoration-none" id="login-btn" style="padding:10px 0px !important;"
                                 href="{{ route('login') }}">
                                    {{ __('custom.login') }}</a>
                                <a href="#" class="nav-link text-light me-3 text-decoration-none" style="padding-top:10px !important;"
                                    id="search-btn" data-toggle="modal" data-target="#searchModal">
                                    Търсене
                                </a>
                            </div>
                        @endif
                    </div>
                </li>

            </ul>
            <li class="nav-item d-flex list-unstyled text-end align-items-center social-lang">
{{--                <a class="nav-link me-3" href="#"><i class="fa-brands fa-facebook text-light"><span--}}
{{--                            class="d-none">Facebook</span></i></a>--}}
                <div class="desktop-accessibility">
                    <button id="vo-option-btn" class="btn dropdown-toggle me-1" type="button" aria-label="VO Option button"
                            data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa-solid fa-wheelchair"></i>
                    </button>

                    <ul class="dropdown-menu link-action">
                        <li class="visual-option vo-increase-text dropdown-item" role="button"><a><i class="text-primary me-2">A+</i>{{ __('custom.increase_text') }}</a></li>
                        <li class="visual-option vo-decrease-text dropdown-item" role="button"><a><i class="text-primary me-2">A-</i>{{ __('custom.decrease_text') }}</a></li>
                        <li class="visual-option vo-contrast dropdown-item" role="button" id="vo-contrast"><a><i class="fa-solid fa-palette text-primary me-2"></i><span class="height @if($vo_high_contrast) d-none @endif">{{ __('custom.high_contrast') }}</span><span class="low @if(!$vo_high_contrast) d-none @endif">{{ __('custom.low_contrast') }}</span></a></li>
                        <li class="visual-option vo-reset dropdown-item" role="button"><a><i class="fas fa-sync-alt text-primary me-2"></i>{{ __('custom.clear') }}</a></li>
                    </ul>
                </div>

                @foreach(config('available_languages') as $locale)
                    @if($locale['code'] != app()->getLocale())
                    <a href="{{ route('change-locale', ['locale' => $locale['code']]) }}" class="nav-link d-inline-block">{{ mb_strtoupper($locale['code']) }}</a>
                    @endif
                @endforeach
{{--                <a class="nav-link" href="#">EN</a>--}}
            </li>

        </div>
    </div>
</nav>
