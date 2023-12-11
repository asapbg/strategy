@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Партньорство за открито управление')

@section('content')

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Партньорство за открито управление
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Национални планове
                                        за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценка за
                                        изпълнението на плановете за действие - мониторинг</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Разработване на нов
                                        план за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">OGP FORUM</a>
                                </li>
                                <li class="mb-2 active-item-left p-1""><a href=" #"
                                    class="link-dark text-decoration-none">Новини и събития</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
                <img src="/img/ogp-img.png" class="img-fluid rounded mt-2" alt="OGP">
            </div>
        </div>

    </div>
















    <div class="row p-1">
        <div class="accordion" id="accordionExample">

            <div class="card custom-card">
                <div class="card-header" id="heading60">
                    <h2 class="mb-0">
                        <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start" type="button"
                            data-toggle="collapse" data-target="#collapse60" aria-expanded="true"
                            aria-controls="collapse60">
                            <i class="me-1 bi bi-file-earmark-text fs-18"></i> ИМЕ НА ФАЙЛ
                        </button>
                    </h2>
                </div>

                <div id="collapse60" class="collapse show" aria-labelledby="heading60" data-parent="#accordionExample"
                    style="">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="text-start">
                                    <span class="text-start me-3">
                                        <strong>Дата на създаване:</strong> 07.12.2023 г..
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="https://strategy.asapbg.com/download/60" class="btn btn-primary">Изтегли</a>
                            </div>
                        </div>

                        <div class="row file-content">

                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="text-start">
                                    <span class="text-start me-3">
                                        <strong>Дата на създаване:</strong> 07.12.2023 г..
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="https://strategy.asapbg.com/download/60" class="btn btn-primary">Изтегли</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






















    <div class="col-lg-10 py-5 right-side-content">
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Последни новини и събития
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div> <span
                        class="post-date text-secondary">11.09.2023</span>
                    <h3 class="post-title">България се включи в годишната среща на Глобалната инициатива „Партньорство
                        за открито управление“ в Талин</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Новини</span>
                        </div>
                        <div class="col-md-2">
                            <div class="consult-item-header-edit">
                                <a href="#">
                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                        role="button" title="Изтриване"></i>
                                </a>
                                <a href="#">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                        title="Редакция">
                                    </i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">
                        Две хиляди участници от 75 страни се включиха в годишната среща на Глобалната инициатива
                        „Партньорство за открито...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1"
                        title="България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин">Прочетете
                        още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div> <span
                        class="post-date text-secondary">08.09.2023</span>
                    <h3 class="post-title">Вицепремиерът Мария Габриел поема националната координация по глобалната
                        инициатива „Партньорство за открито управление“</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Новини</span>
                        </div>
                        <div class="col-md-2">
                            <div class="consult-item-header-edit">
                                <a href="#">
                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                        role="button" title="Изтриване"></i>
                                </a>
                                <a href="#">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                        title="Редакция">
                                    </i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">
                        С решение на Министерския съвет вицепремиерът Мария Габриел бе определена за координатор за
                        участието на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1"
                        title="Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“">Прочетете
                        още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>
                    <span class="post-date text-secondary">11.05.2023</span>
                    <h3 class="post-title">България се включва в Седмицата на откритото управление по глобалната
                        инициатива „Партньорство за открито управление“</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Предстоящи събития</span>
                        </div>
                        <div class="col-md-2">
                            <div class="consult-item-header-edit">
                                <a href="#">
                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                        role="button" title="Изтриване"></i>
                                </a>
                                <a href="#">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                        title="Редакция">
                                    </i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">
                        С публична дискусия на тема „Открито управление – цел и фокус на доброто управление“ страната ни
                        се...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1"
                        title="България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“">Прочетете
                        още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

        </div>
        <div class="row mb-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination m-0">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">57</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="row mb-2 py-5">
            <h2 class="mb-4">
                Лица, които отговарят за развитието на инициативата в България
            </h2>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Иван Иванов
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Началник отдел
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 124 124
                                <a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    ivan_ivanov@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Мария Иванова
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Старши експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 125 125
                                <a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    mariya_ivanova@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Николай Георгиев
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 127 127
                                <a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    nikolay_georgiev@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 ">
                <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                    <div class="member-info">
                        <p class="team-member-name fs-3 main-color mb-0">
                            Петър Георгиев
                        </p>
                        <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                            Експерт
                        </p>
                        <p class="team-member-info dark-text mb-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute
                            irure dolor in reprehenderit in.
                        </p>
                        <div class="team-member-contact d-flex flex-row">
                            <a href="#" class="text-decoration-none me-4">
                                <i class="fa-solid fa-phone me-1"></i>
                                +359 888 128 128
                                <a href="#" class="text-decoration-none">
                                    <i class="fa-solid fa-envelope me-1"></i>
                                    petar_georgiev@gov.bg
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2 ">
            <h2 class="mb-3">
                Календар с предстоящи събития
            </h2>



            <div class="calendar-wrap">
                <div class="row mb-sm-3 align-items-center calendar-info">
                    <div class="col-md-4">
                        <div class="calendar-month mb-2">
                            <span class="fs-3 d-flex align-items-center">
                                <span>
                                    <a href="#" class="link-dark">
                                        <i class="fa-solid fa-chevron-left me-2 fs-18"></i>
                                        <span class="d-none">Previous month</span></a>
                                    <!-- Трябва да се слага, защото дава грешки за празни линкове -->
                                </span>
                                Декември
                                <span>
                                    <a href="#" class="link-dark">
                                        <i class="fa-solid fa-chevron-right ms-2 fs-18"></i>
                                        <span class="d-none">Next month</span></a>
                                    <!-- Трябва да се слага, защото дава грешки за празни линкове -->
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="calendar-legend d-flex justify-content-md-end justify-content-sm-start">
                            <div class="event-legend me-4">
                                <span class="past-event-legend rounded me-2">
                                </span>
                                <span>
                                    Минало събитие
                                </span>
                            </div>

                            <div class="event-legend me-4">
                                <span class="current-day-legend rounded me-2">
                                </span>
                                <span>
                                    Днес
                                </span>
                            </div>

                            <div class="event-legend mb-0">
                                <span class="future-event-legend rounded me-2">
                                </span>
                                <span>
                                    Предстоящо събитие
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="calendar-head w-100 p-2 rounded desktop-day">
                    <div class="dayname">Понеделник</div>
                    <div class="dayname">Вторник</div>
                    <div class="dayname">Сряда</div>
                    <div class="dayname">Четвърък</div>
                    <div class="dayname">Петък</div>
                    <div class="dayname">Събота</div>
                    <div class="dayname">Неделя</div>
                </div>
                <div class="calendar-head w-100 p-2 rounded mobile-day d-none">
                    <div class="dayname">пн</div>
                    <div class="dayname">вт</div>
                    <div class="dayname">ср</div>
                    <div class="dayname">чт</div>
                    <div class="dayname">пт</div>
                    <div class="dayname">сб</div>
                    <div class="dayname">нд</div>
                </div>
                <div class="calendar col-md-12 p-3">
                    <div class="row">
                        <div class="day">
                            <div class="day-number">1</div>
                        </div>
                        <div class="day past-event" data-toggle="modal" data-target="#pastEventExample">
                            <div class="day-number">2</div>
                        </div>
                        <div class="day">
                            <div class="day-number">3</div>
                        </div>
                        <div class="day past-event" data-toggle="modal" data-target="#pastEventExample">
                            <div class="day-number">4</div>
                        </div>
                        <div class="day">
                            <div class="day-number">5</div>
                        </div>
                        <div class="day current-day-calendar">
                            <div class="day-number">6</div>
                        </div>
                        <div class="day">
                            <div class="day-number">7</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="day">
                            <div class="day-number">8</div>
                        </div>
                        <div class="day">
                            <div class="day-number">9</div>
                        </div>
                        <div class="day">
                            <div class="day-number">10</div>
                        </div>
                        <div class="day">
                            <div class="day-number">11</div>
                        </div>
                        <div class="day">
                            <div class="day-number">12</div>
                        </div>
                        <div class="day">
                            <div class="day-number">13</div>
                        </div>
                        <div class="day">
                            <div class="day-number">14</div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="day">
                            <div class="day-number">15</div>
                        </div>
                        <div class="day">
                            <div class="day-number">16</div>
                        </div>
                        <div class="day">
                            <div class="day-number">17</div>
                        </div>
                        <div class="day">
                            <div class="day-number">18</div>
                        </div>
                        <div class="day">
                            <div class="day-number">19</div>
                        </div>
                        <div class="day">
                            <div class="day-number">20</div>
                        </div>
                        <div class="day">
                            <div class="day-number">21</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="day">
                            <div class="day-number">22</div>
                        </div>
                        <div class="day">
                            <div class="day-number">23</div>
                        </div>
                        <div class="day">
                            <div class="day-number">24</div>
                        </div>
                        <div class="day">
                            <div class="day-number">25</div>
                        </div>
                        <div class="day">
                            <div class="day-number">26</div>
                        </div>
                        <div class="day">
                            <div class="day-number">27</div>
                        </div>
                        <div class="day">
                            <div class="day-number">28</div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="day event cursor-pointer" data-toggle="modal" data-target="#futureEventExample">
                            <div class="day-number">29</div>
                        </div>
                        <div class="day">
                            <div class="day-number">30</div>
                        </div>
                        <div class="day event cursor-pointer" data-toggle="modal" data-target="#futureEventExample">
                            <div class="day-number">31</div>
                        </div>
                        <div class="day next">
                            <div class="day-number">1</div>
                        </div>
                        <div class="day next">
                            <div class="day-number">2</div>
                        </div>
                        <div class="day next">
                            <div class="day-number">3</div>
                        </div>
                        <div class="day next">
                            <div class="day-number">4</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</div>
</div>
@endsection

<!-- Past Event -->
<div class="modal fade" id="pastEventExample" tabindex="-1" role="dialog" aria-labelledby="pastEventExampleLabel"
    aria-hidden="true">
    <div class="modal-dialog event-screen-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Минали събития 02.12.2023г.</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 mt-4">

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 past-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">02</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество">
                                        <h3>Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Заместник министър-председателят Атанас Пеканов е новият председател на Съвета за
                                развитие на гражданското общество /СРГО/, реши правителството.Като консултативен орган
                                на Министерския съвет за разработване и провеждане на политики за подкрепа на развитието
                                на гражданското общество.
                            </p>
                            <p class="mb-0 text-secondary"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 past-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">02</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество">
                                        <h3>Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Заместник министър-председателят Атанас Пеканов е новият председател на Съвета за
                                развитие на гражданското общество /СРГО/, реши правителството.Като консултативен орган
                                на Министерския съвет за разработване и провеждане на политики за подкрепа на развитието
                                на гражданското общество.
                            </p>
                            <p class="mb-0 text-secondary"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 past-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">02</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество">
                                        <h3>Вицепремиерът Атанас Пеканов ще ръководи Съвета за развитие на
                                            гражданското общество</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Заместник министър-председателят Атанас Пеканов е новият председател на Съвета за
                                развитие на гражданското общество /СРГО/, реши правителството.Като консултативен орган
                                на Министерския съвет за разработване и провеждане на политики за подкрепа на развитието
                                на гражданското общество.
                            </p>
                            <p class="mb-0 text-secondary"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Затвори</button>
            </div>
        </div>
    </div>
</div>
<!-- End Past Event -->

<!-- Future Event -->
<div class="modal fade" id="futureEventExample" tabindex="-1" role="dialog" aria-labelledby="futureEventExampleLabel"
    aria-hidden="true">
    <div class="modal-dialog event-screen-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Предстоящи събития 29.12.2023г.</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 mt-4">

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 future-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">29</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                    административната реформа">
                                        <h3>Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                            административната реформа</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Министерският съвет прие Решение за определяне на заместник министър-председателя и
                                министър на външните работи Мария Габриел за председател на Съвета за административната
                                реформа (САР).
                            </p>
                            <p class="mb-0 main-color"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span></p>
                        </div>
                    </div>
                </div>

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 future-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">29</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                    административната реформа">
                                        <h3>Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                            административната реформа</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Министерският съвет прие Решение за определяне на заместник министър-председателя и
                                министър на външните работи Мария Габриел за председател на Съвета за административната
                                реформа (САР).
                            </p>
                            <p class="mb-0 main-color"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span></p>
                        </div>
                    </div>
                </div>

                <div class="event-list-wrapper mb-4">
                    <div class="row event-body custom-shadow rounded">
                        <div class="col-md-2 future-date-event p-2 rounded">
                            <span class="fs-50 lh-normal">29</span>
                            <span class="fs-18">декември</span>
                            <span class="fs-18">2023г.</span>
                        </div>
                        <div class="col-md-10 p-3">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="#" class="text-decoration-none" title="Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                    административната реформа">
                                        <h3>Вицепремиерът Мария Габриел е определена за председател на Съвета за
                                            административната реформа</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <p class="event-description mb-2">
                                Министерският съвет прие Решение за определяне на заместник министър-председателя и
                                министър на външните работи Мария Габриел за председател на Съвета за административната
                                реформа (САР).
                            </p>
                            <p class="mb-0 main-color"><i class="bi bi-clock me-2"></i><span>13:00 - 17:00</span></p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Затвори</button>
            </div>
        </div>
    </div>
</div>
<!-- End Future Event -->
