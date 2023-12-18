<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-12 mb-3">
                <h3 class="text-light fs-4 fw-400">Контакти</h3>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Петя Цанкова</a></li>
                    <li class="nav-item mb-2"><a href="mailto:p.tsankova@goverment.bg" class="p-0 text-light">»  p.tsankova@goverment.bg</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-sm-12 mb-3">
                <img class="d-block w-100 px-md-3 footer-img" src="../img/eu_white.png" alt="Европейски Съюз Европейски Социален Фонд" title="Европейски Съюз Европейски Социален Фонд">
               <!-- <h3 class="text-light fs-4 fw-400">Контакти</h3>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Министерски съвет</a></li>
                    <li class="nav-item mb-2"><a
                            href="@if(isset($contactMail) && !empty($contactMail)){{ 'mailto:'.$contactMail }}@else{{ '#' }}@endif"
                            class="p-0 text-light">» @if(isset($contactMail) &&
                            !empty($contactMail)){{ 'mailto:'.$contactMail }}@else{{ '---' }}@endif</a></li>
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» +359 888 123 123</a></li>
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Контакт с администрация</a></li>
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Към стария сайт</a></li>
                </ul>
            -->
            </div>

            <div class="col-md-4 col-sm-12 mb-3">
                <img class="d-block w-100 px-md-3 footer-img" src="../img/op-white.png" alt="Оперативна програма Добро управление" title="Оперативна програма Добро управление">
                <!-- Секция за добавяне на страници от админ панела -->
                 <!-- <h3 class="text-light fs-4 fw-400">Страници</h3> -->
                 <!--<ul class="nav flex-column footer-nav">
                    <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Пример допълнителна страница</a></li> 
                </ul>
                -->
            </div>
        </div>


        <div class="d-flex flex-column flex-sm-row justify-content-center pt-4 border-top">
            <p class="m-0 text-light text-center">© 2023 Портал за обществени консултации. Всички права запазени МС.</p>
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
                <div class="search-wrapper-modal">
                  <input class="search-site-input w-100" type="text" placeholder="Търсене в портала...">
                  <a type="button" class="text-decoration-none text-light cursor-pointer" title="Търсене">
                    <i class="fa-solid fa-search text-light ms-3"></i>
                    <span class="d-none">Search</span>
                  </a>
                 </a>
                </div>
              </div>


            </div>
        </div>
    </div>
</div>
