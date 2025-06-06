<footer>
    <div class="container">
        @if(isset($footerTermsPages) && sizeof($footerTermsPages))
            <div class="row mb-3">
                <div class="col-md-4 mb-1">
                    <h3 class="text-light fs-4 fw-400 w-100">{{ __('site.footer.section.terms') }}</h3>
                </div>
                <div class="col-md-8 mb-1">
                    <div class="row">
                        @foreach($footerTermsPages as $page)
                            <div class="col-md-6 mb-3">
                                <a class="p-0 text-light text-decoration-none" href="{{ $page['url'] }}" title="{{ $page['name'] }}">{{ $page['name'] }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-center pb-4 border-top"></div>
        @endif

        <div class="row mb-3">
            <div class="col-md-4 mb-1">
                <h3 class="text-light fs-4 fw-400 w-100">{{ __('site.footer.extra_links') }}</h3>
            </div>
            <div class="col-md-8 mb-1">
                <div class="row">
                    @if(isset($footerPages) && sizeof($footerPages))
                        @foreach($footerPages as $page)
                            <div class="col-md-6 mb-3">
                                <a class="p-0 text-light text-decoration-none" href="{{ $page['url'] }}" title="{{ $page['name'] }}">{{ $page['name'] }}</a>
                            </div>
                        @endforeach
                        @if(sizeof($footerPages) % 2 !== 0)
                            <div class="col-md-12 mb-3">
                                <span class="d-none">span</span>
                            </div>
                        @endif
                    @endif
                        <div class="col-md-6 mb-3">
                            <a class="p-0 text-light text-decoration-none" href="{{ route('other_links') }}" title="{{ __('site.footer.other_links') }}">{{ __('site.footer.other_links') }}</a>
                        </div>
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <a class="p-0 text-light text-decoration-none" href="{{ route('sitemap') }}" title="{{ __('site.sitemap') }}">{{ __('site.sitemap') }}</a>--}}
{{--                        </div>--}}
                        <div class="col-md-6 mb-3">
                            <a class="p-0 text-light text-decoration-none" href="https://www.strategy.bg/" title="{{ __('site.old_portal_link') }}л">{{ __('site.old_portal_link') }}</a>
                        </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-center pb-4 border-top"></div>

        <div class="row">
            <div class="col-md-4 mb-1">
                <h3 class="text-light fs-4 fw-400 w-100">{{ trans_choice('custom.contacts', 2) }}</h3>
            </div>
            <div class="col-md-8 mb-1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a class="p-0 text-light text-decoration-none" href="{{ route('contacts') }}" title="{{ __('site.footer.section.contact.link') }}">{{ __('site.footer.section.contact.link') }}</a>
                        <a class="p-0 text-light text-decoration-none d-block mt-3" href="{{ route('help.guide') }}" title="{{ __('site.user_guide') }}">{{ __('site.user_guide') }}</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a class="p-0 text-light text-decoration-none" href="@if(isset($contactMail) && !empty($contactMail)){{ 'mailto:'.$contactMail }}@else{{ '#' }}@endif" >» @if(isset($contactMail) &&
                            !empty($contactMail)){{ $contactMail }}@else{{ '---' }}@endif</a>
                        <a class="p-0 text-light text-decoration-none d-block mt-3" href="{{ route('help.video') }}" title="{{ __('site.user_video_guide') }}">{{ __('site.user_video_guide') }}</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex flex-column flex-sm-row justify-content-center pt-4 border-top">
            <p class="m-0 text-light text-center">{{ __('site.footer.copyright', ['year' => date('Y')]) }}</p>
        </div>
    </div>
</footer>

<script>
    /*Shrink navbar*/
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {

        let pageWidth = screen.width;
        let navLink = document.querySelectorAll('header nav.navbar a.nav-link');
        let navItem = document.querySelectorAll('header li.nav-item');
        let navBar = document.querySelectorAll('header nav.navbar');

        if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {

            if (document.getElementById("back-to-admin")) {
                document.getElementById("back-to-admin").style.fontSize = "14px";
            }

            if (document.getElementById("profile-toggle")) {
                document.getElementById("profile-toggle").style.fontSize = "14px";
            }

            if (document.getElementById("register-link")) {
                document.getElementById("register-link").style.fontSize = "14px";
            }

            if (document.getElementById("login-btn")) {
                document.getElementById("login-btn").style.fontSize = "14px";
            }

            if (document.getElementById("search-btn")) {
                document.getElementById("search-btn").style.fontSize = "14px";
                document.getElementById("search-btn").style.height = "37px";

            }

            document.getElementById("siteLogo").style.width = "45px";
            document.getElementById("ms").style.fontSize = "12px";
            document.getElementById("ok").style.fontSize = "12px";

            navLink.forEach(function (link) {
                link.style.fontSize = '14px';
            });

            navBar.forEach(function (nav) {
                nav.style.boxShadow = 'rgb(27 81 126 / 81%) 0px 1px 4px';
            });

            if (pageWidth > 991) {
                navItem.forEach(function (item) {
                item.style.padding = '2px 10px';
            });
            }



        }
        else {

            if (document.getElementById("profile-toggle")) {
                document.getElementById("profile-toggle").style.fontSize = "16px";
            }

            if (document.getElementById("back-to-admin")) {
                document.getElementById("back-to-admin").style.fontSize = "16px";
            }

            document.getElementById("siteLogo").style.width = "55px";


            if (pageWidth < 480) {
                navItem.forEach(function (item) {

            document.getElementById("ms").style.fontSize = "14px";
            document.getElementById("ok").style.fontSize = "14px";

            });
            }
            else {
                document.getElementById("ms").style.fontSize = "16px";
            document.getElementById("ok").style.fontSize = "16px";
            }



            if (document.getElementById("register-link")) {
                document.getElementById("register-link").style.fontSize = "16px";
            }

            if (document.getElementById("login-btn")) {
                document.getElementById("login-btn").style.fontSize = "16px";
            }

            if (document.getElementById("search-btn")) {
                document.getElementById("search-btn").style.fontSize = "16px";
                document.getElementById("search-btn").style.height = "40px";
            }

            navLink.forEach(function (link) {
                link.style.fontSize = '15px';
            });

            navItem.forEach(function (item) {
                item.style.padding = '10px';
            });

            navBar.forEach(function (nav) {
                nav.style.boxShadow = 'rgb(27 81 126) 0px 1px 4px';
            });
        }
    }
    /*End shrink*/

</script>


<!-- Website search modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog search-screen" role="document">
        <div class="modal-content">
            <div class="modal-body row">
              <div class="col-md-12 text-end mb-5">
                <a type="button" class="close search-close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">
                      <i class="fa-solid fa-xmark text-light fs-3"></i></span>
               </a>
              </div>
              <div class="col-md-12 mb-5">
                <div class="search-wrapper-modal flex-column">
                    <form action="{{ route('search') }}" method="get" class="w-100 d-flex">
                        <input class="search-site-input w-100" type="text" name="search" id="global_search" placeholder="{{ __('site.search_in_platform') }}" autocomplete="off">
                        <button type="button" class="bg-transparent border-0" id="global_search_btn">
                            <i class="fa-solid fa-search text-light ms-3"></i>
                        </button>
                    </form>
                    <div class="w-100 d-none text-danger bg-white rounded-2 fw-bold px-2 py-2 mb-1 opacity-75" id="global_search_error"></div>

                </div>
              </div>


            </div>
        </div>
    </div>
</div>
