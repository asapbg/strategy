<div id="topbar">
    <div class="container-fluid">
        <div class="row top align-items-center">
            <div class="col-md-6 align-items-center d-flex">
                <div class="contact-info d-flex w-100 align-items-center ">
                    <a class="navbar-brand logo-link pe-2" href="{{ route('site.home') }}">
                        <img src="{{ asset('/img/strategy-logo.svg') }}" alt="Strategy Лого" id="siteLogo">
                    </a>
                    <div class="contact-info-text d-flex flex-column ps-2">
                        <span class="main-color fw-600" id="ms">{{ __('site.header_main_title') }}</span>
                        <span class="fw-600" id="ok">{{ __('site.seo_title') }}</span>
                    </div>
                </div>
            </div>
            {{--          @include('partials.breadcrumbs_front')--}}
            {{--        <div class="col-md-4">--}}
            {{--          <div class="search">--}}
            {{--            <i class="fas fa-search main-color"></i>--}}
            {{--            <label for="search-field" class="visually-hidden">Търсене в сайта</label>--}}
            {{--            <input type="text" class="form-control" id="search-field" placeholder="Търсене в сайта">--}}
            {{--            <button class="btn btn-primary">Търсене</button>--}}
            {{--          </div>--}}
            {{--        </div>--}}

            <div class="col-md-6 text-end top-bar-right-column">
                <div class="auth d-flex justify-content-end top-bar-left-side-desktop">
                    @if(app('auth')->check())
                        <div id="front-timer me-2">
                            @include('partials.count-down-timer')
                        </div>
                        @if(auth()->user()->user_type == \App\Models\User::USER_TYPE_INTERNAL)
                        <a href="{{ route('admin.home') }}" class="btn btn-success text-success me-2"
                            id="back-to-admin"><i class="fas fa-arrow-left me-1"></i>{{ __('site.to_administration') }}</a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" id="profile-toggle" type="button"
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
                        <a href="" class="cstm-btn btn btn-primary login-search d-flex align-items-center ms-2"
                            id="search-btn" style="height: 40px;" data-toggle="modal" data-target="#searchModal">
                            <i class="login-icon fas fa-search main-color"><span class="d-none">Search</span></i>
                        </a>
                    @else
                        <div class="registration text-right justify-content-end align-items-center d-flex">
                            <!--                    <form class="form-inline me-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Търсене" aria-label="Username" aria-describedby="basic-addon1" style="border-top-right-radius:0px !important;border-bottom-right-radius:0px !important;">
                                    <div class="input-group-prepend rounded-0">
                                    <span class="input-group-text search-btn d-block" id="basic-addon1"><i class="fa-solid fa-magnifying-glass main-color"></i>
                                    </span>
                                    </div>
                                </div>
                                </form> -->
                            <a class="main-color me-3" id="register-link"
                                href="{{ route('register') }}">{{ __('custom.register') }}</a>
                            <a class="btn btn-primary me-3" id="login-btn" href="{{ route('login') }}"><i
                                    class="login-icon fa-solid fa-right-to-bracket main-color"></i>
                                {{ __('custom.login') }}</a>
                            <a href="" class="cstm-btn btn btn-primary login-search d-flex align-items-center "
                                id="search-btn" style="height: 40px;" data-toggle="modal" data-target="#searchModal">
                                <i class="login-icon fas fa-search main-color"><span class="d-none">Search</span></i>
                            </a>

                            <!-- Modal -->
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
