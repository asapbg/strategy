@extends('layouts.site')

@section('pageTitle', 'Обществени консултации')

@section('content')
    <h2 class="obj-title mb-4">Проект на Закон за изменение и допълнение на Закона за Европейската заповед за разследване</h2>
    <div>
        <span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue" title="Дата на откриване"></i>28.8.2023 г.</span>
        <span class="obj-icon-info me-2"><i class="fas fa-users me-1 dark-blue" title="Целева група"></i>Всички заинтересовани</span>
        <span class="obj-icon-info me-2"><i class="fas fa-sitemap me-1 dark-blue" title="Сфера на действие"></i>Правосъдие и вътрешни работи</span>
        <span class="obj-icon-info me-2"><i class="far fa-calendar-check me-1 dark-blue" title="Дата на приключване"></i>27.9.2023 г.</span>
    </div>
    <hr>
    <ul class="nav nav-tabs nav-pills border-0 mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Основна информация</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">История</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pools-tab" data-bs-toggle="tab" data-bs-target="#pools" type="button" role="tab" aria-controls="pools" aria-selected="false">Анкети</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="info" role="tabpanel" aria-labelledby="info-tab">
            <div class="obj_content mb-3">
                Промените в Закона за Европейската заповед за разследване са продиктувани от необходимостта да се съобрази националното законодателство с решение на Съда на Европейския съюз (СЕС) от 11 ноември 2021 г. по дело С-852/19, Иван Гаванозов (Гаванозов II) и решение на Съда на ЕС от 16 декември 2021 г. по дело С-724/19. Според тълкуването на съда в българското законодателство липсват механизми, чрез които засегнатите лица могат да оспорват пред съд в издаващата държава материалноправните основания за издаването на Европейска заповед за арест, а така също законосъобразността и необходимостта от извършваните действия за събиране на доказатаелства, както и да искат подходящо обезщетение, в случай че тези действия са били разпоредени или изпълнени незаконно. Предложените промени имат за цел да бъдат създадени ефективни правни средства за защита при издаване и признаване на Европейска заповед за разследване, съобразени с решението по дело С-852/19 (процедура по обжалване на издаването на ЕЗР и процедура по обжалване признаването на ЕЗР), както и да бъдат предвидени национални правни средства за защита, чрез които засегнатите от извършените действия по разследване лица да имат възможността да оспорват законосъобразността и необходимостта от тези действия, както и да искат подходящо обезщетение в случай че те са били разпоредени или изпълнени незаконно. Решението по дело С-724/19 изисква прецизиране на разпределението на компетентността между органите, които могат да вземат решение относно издаването на ЕЗР в досъдебното производство, което да съответства на компетентността им според националното законодателство да разрешават или да разпореждат действия по събиране на доказателства в тази фаза на наказателния процес. Наложителните изменения и допълнения на Закона за Европейската заповед за разследване дават възможност да бъдат прецизирани и други разпоредби, чието съдържание е неясно или би могло да постави въпроса за неточното и непълно въвеждане в националното законодателство на Директива 2014/41/ЕС. Ще се гарантира и изпълнението на задълженията на България, произтичащи от Директива 2014/41/ЕС на Европейския парламент и на Съвета от 3 април 2014 г. относно Европейска заповед за разследване по наказателноправни въпроси и от чл. 47 от Хартата на основните права на Европейския съюз.
            </div>
            <div class="obj-section obg-files-section mb-5">
                <div class="obj-section-title">
                    Документи
                </div>
                <a class="obj-file">
                    <i class="fas fa-file-download me-2"></i> Проект на Закон за изменение и допълнение на Закона за Европейската заповед за разследване
                </a>
                <a class="obj-file">
                    <i class="fas fa-file-download me-2"></i> Мотиви към проект на Закон за изменение и допълнение на Закона за Европейската заповед за разследване
                </a>
                <a class="obj-file">
                    <i class="fas fa-file-download me-2"></i> Частична предварителна оценка на въздействието по проект на Закон за изменение и допълнение на Закона за Европейската заповед за разследване
                </a>
                <a class="obj-file">
                    <i class="fas fa-file-download me-2"></i> Становище на дирекция Модернизация на администрацията по частична предварителна оценка на въздействието
                </a>
            </div>
            <div class="obj-section obg-comments-section">
                <div class="obj-section-title border-0">
                    <i class="far fa-comments dark-blue me-3"></i>Коментари
                </div>
                <form class="mb-5" action="" method="post">
                    <input type="text" class="form-control mb-2" placeholder="Заглавие">
                    <textarea class="form-control" placeholder="Въведете коментар"></textarea>
                    <button type="submit" class="btn btn-primary mb-2 mt-2">Остави коментар</button>
                </form>
                <div class="obj-comment">
                    <div class="info">
                        <span class="obj-icon-info me-2"><i class="fas fa-clock me-1 dark-blue" title="Дата на публикуване"></i>30.05.2023 18:10</span>
                        <span class="obj-icon-info me-2"><i class="fas fa-user me-1 dark-blue" title="Автор"></i>Mitrev</span>
                    </div>
                    <div class="comment rounded">
                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    </div>
                </div>
                <div class="obj-comment">
                    <div class="info">
                        <span class="obj-icon-info me-2"><i class="fas fa-clock me-1 dark-blue" title="Дата на публикуване"></i>23.05.2023 13:23</span>
                        <span class="obj-icon-info me-2"><i class="fas fa-user me-1 dark-blue" title="Автор"></i>stpetrov</span>
                    </div>
                    <div class="comment rounded">
                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
            {{--    Start Timeline--}}
            <div class="timeline">
                <div class="hori-timeline" dir="ltr">
                    <ul class="timeline events">
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold">Начало на обществената консултация</h5>
                            <p class="text-muted mb-2 fw-bold">20.05.2023</p>
                        </li>

                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold">Приключване на консултацията</h5>
                            <p class="text-muted mb-2 fw-bold">01.06.2023</p>
                        </li>
                    </ul>
                </div>
            </div>
            {{--    End Timeline--}}
        </div>
    </div>
@endsection
