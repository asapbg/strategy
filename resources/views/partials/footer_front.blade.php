<footer>
    <div class="container">
        <div class="row">
            {{--        <div class="col-6 col-md-2 mb-3">--}}
            {{--          <h5 class="text-light">Полезни връзки</h5>--}}
            {{--          <ul class="nav flex-column footer-nav">--}}
            {{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Начало</a></li>--}}
            {{--            <li class="nav-item mb-2"><a href="#" class= "p-0 text-light">» Новини</a></li>--}}
            {{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Публикации</a></li>--}}
            {{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Мнения</a></li>--}}
            {{--          </ul>--}}
            {{--        </div>--}}

            <div class="col-md-4 mb-3">
                <h5 class="text-light">Информация</h5>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Условия за ползване</a></li>
                    <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Към стария портал</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="text-light">Контакти</h5>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item pb-0 text-light"><i class="fas fa-envelope text-white me-2"></i>main_mail@test.bg</li>
                    <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Контакт с администрация</a></li>
                </ul>
            </div>

            {{--        <div class="col-md-5 offset-md-1 mb-3">--}}
            {{--          <form>--}}
            {{--            <h5 class="text-light">Абонирайте се за нашия бюлетин</h5>--}}
            {{--            <p class="text-light">Получавайте актуална информация относно обществени консултации, новини и др.</p>--}}
            {{--            <div class="d-flex flex-column flex-sm-row w-100 gap-2">--}}
            {{--              <label for="newsletter1" class="visually-hidden" style="color: #fff !important;background: #000;">Имейл адрес</label>--}}
            {{--              <input id="newsletter1" type="text" class="form-control" placeholder="Имейл адрес">--}}
            {{--              <button class="btn rss-sub subscribe" type="button">Абониране</button>--}}
            {{--            </div>--}}
            {{--          </form>--}}
            {{--        </div>--}}
            {{--      </div>--}}

            <div class="d-flex flex-column flex-sm-row justify-content-between pt-4  border-top">
                <p class="m-0 text-light">© {{ date('Y') }} {{ __('custom.copyright_text') }}</p>
                <a class="m-0 text-light text-danger text-decoration-none" href="https://www.asap.bg/" target="_blank">{{ __('custom.asap_support') }}</a>
            </div>
        </div>

</footer>
