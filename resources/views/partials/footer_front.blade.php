<footer>
    <div class="container-fluid">
          <div class="row">
            <div class="col-md-2 col-sm-12 mb-3">
              <h3 class="text-light fs-4 fw-400">Условия за ползване</h3>
              <ul class="nav flex-column footer-nav">
                <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Обща информация</a></li>
                <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Помощ за системата</a></li>
                <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Права на потребителите</a></li>
                <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Политика за поверителност</a></li>
                <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Политика за бисквитки</a></li>
              </ul>
            </div>

            <div class="col-md-2 col-sm-12 mb-3">
                <h3 class="text-light fs-4 fw-400">Контакти</h3>
                <ul class="nav flex-column footer-nav">
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Министерски съвет</a></li>
                  <li class="nav-item mb-2"><a href="@if(isset($contactMail) && !empty($contactMail)){{ 'mailto:'.$contactMail }}@else{{ '#' }}@endif" class="p-0 text-light">» @if(isset($contactMail) && !empty($contactMail)){{ 'mailto:'.$contactMail }}@else{{ '---' }}@endif</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» +359 888 123 123</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Контакт с администрация</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Към стария сайт</a></li>
                </ul>
              </div>

              <div class="col-md-2 col-sm-12 mb-3">
                <!-- Секция за добавяне на страници от админ панела -->
                <h3 class="text-light fs-4 fw-400">Страници</h3>
                <ul class="nav flex-column footer-nav">
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Обществени консултации</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Стратегически документи</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Законодателни инициативи</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Оценки на въздействието</a></li>
                  <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Гражданско участие</a></li>
                </ul>
              </div>

            <div class="col-md-5 offset-md-1 mb-3">
              <form>
                <h3 class="text-light fs-4 fw-400">Търсене в портала</h3>
                <div class="row">
                    <div class="col-md-8">
                        <label for="newsletter1" class="visually-hidden text-light">Търсене</label>
                        <input id="newsletter1" type="text" class="form-control br-30 " placeholder="Въведете дума или израз">
                    </div>
                    <div class="col-md-12 mt-1">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search main-color me-1"><span class="d-none">Search</span></i>Търсене</button>
                    </div>
                </div>
              </form>
            </div>
          </div>


            <div class="d-flex flex-column flex-sm-row justify-content-between pt-4 mt-4 border-top">
                <p class="m-0 text-light">© 2023 Портал за обществени консултации. Всички права запазени.</p>
                <a class="m-0 text-light text-danger text-decoration-none" href="https://www.asap.bg/" target="_blank">Софтуерна разработка и поддръжка от ASAP</a>
            </div>
      </div>
</footer>

<script>
  /*Shrink navbar*/
window.onscroll = function() {scrollFunction()};
function scrollFunction() {

  let navLink = document.querySelectorAll('nav.navbar a.nav-link');
  let navItem = document.querySelectorAll('li.nav-item');
  let navBar = document.querySelectorAll('nav.navbar');

  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {

    if(document.getElementById("back-to-admin")){
      document.getElementById("back-to-admin").style.fontSize = "14px";
    }

    if(document.getElementById("profile-toggle")){
      document.getElementById("profile-toggle").style.fontSize = "14px";
    }

    if(document.getElementById("register-link")){
      document.getElementById("register-link").style.fontSize = "14px";
    }

    if(document.getElementById("login-btn")){
      document.getElementById("login-btn").style.fontSize = "14px";
    }

    if(document.getElementById("search-btn")){
    document.getElementById("search-btn").style.fontSize = "14px";
    document.getElementById("search-btn").style.height = "37px";

    }

    document.getElementById("siteLogo").style.width = "45px";
    document.getElementById("ms").style.fontSize = "12px";
    document.getElementById("ok").style.fontSize = "12px";

    navLink.forEach(function(link) {
      link.style.fontSize = '14px';
    });

    navItem.forEach(function(item) {
      item.style.padding = '2px 10px';
    });

    navBar.forEach(function(nav) {
      nav.style.boxShadow = 'rgb(27 81 126 / 81%) 0px 1px 4px';
    });
  }

  else {

    if(document.getElementById("profile-toggle")){
      document.getElementById("profile-toggle").style.fontSize = "16px";
    }

    if(document.getElementById("back-to-admin")){
      document.getElementById("back-to-admin").style.fontSize = "16px";
    }

   document.getElementById("siteLogo").style.width = "55px";
    document.getElementById("ms").style.fontSize = "16px";
    document.getElementById("ok").style.fontSize = "16px";

    if(document.getElementById("register-link")){
      document.getElementById("register-link").style.fontSize = "16px";
    }

    if(document.getElementById("login-btn")){
      document.getElementById("login-btn").style.fontSize = "16px";
    }

    if(document.getElementById("search-btn")){
    document.getElementById("search-btn").style.fontSize = "16px";
    document.getElementById("search-btn").style.height = "40px";
    }

    navLink.forEach(function(link) {
      link.style.fontSize = '15px';
    });

    navItem.forEach(function(item) {
      item.style.padding = '10px';
    });

    navBar.forEach(function(nav) {
      nav.style.boxShadow = 'rgb(27 81 126) 0px 1px 4px';
    });
  }
}
/*End shrink*/
</script>
