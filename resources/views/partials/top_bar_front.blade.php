<div id="topbar">
    <div class="container-fluid">
        <div class="row top">
            <div class="col-md-6">
                <div class="contact-info d-flex align-items-center">
                    <a class="navbar-brand" href="#"><img src="/img/logo_title.png" alt="Logo" id="imageLogo"></a>
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

            <div class="col-md-6 text-end">
                <div class="auth text-right">
                    @if(app('auth')->check())
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="profile-menu" data-bs-toggle="dropdown" aria-expanded="false">
                                @php($user = app('auth')->user())
                                {{ $user->is_org ? $user->org_name : $user->first_name . ' ' . $user->last_name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="profile-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">{{ trans_choice('custom.profiles', 1) }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
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


                            <a class="main-color me-3" href="{{ route('register') }}">{{ __('custom.register') }}</a>
                            <a class="btn btn-primary me-3" href="{{ route('login') }}"><i class="login-icon fa-solid fa-right-to-bracket main-color"></i> {{ __('custom.login') }}</a>
                            <a href="" class="cstm-btn btn btn-primary login-search d-flex align-items-center" style="height: 40px;"><i class="login-icon fas fa-search main-color"></i></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>