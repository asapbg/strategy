@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на Потребител')

@section('content')
<div class="row mt-5">

    {слайдер} <br>
    {пътечки}
</div>
<div class="row">
    <div class="col-lg-2 side-menu pt-5 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Профил на {Потребител}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Обща информация</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Абонаменти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Законодателни
                                        инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценки на
                                        въздействието</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Публикувани
                                        коментари</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 right-side-content py-5 ">
        <div class="col-md-12">
            <h2 class="mb-4">История с публикувани коментари</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="custom-card p-3 mb-4">
                    <div class="row subscribe-row pb-2 align-items-center">
                        <div class="col-md-12">
                            <h3 class="fs-4 mb-2 fw-normal">
                                Обществени консултации
                            </h3>
                        </div>

                        <div class="user-comment-wrapper">
                            <ul class="list-group">
                                <li class="list-group-item user-profile-comment">
                                    <a href="#" class="text-decoration-none">Проект на Правилник за изменение и
                                        допълнение на
                                        Устройствения правилник на Центъра за развитие на човешките ресурси и регионални
                                        инициативи
                                    </a>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-3 mb-4">
                    <div class="row subscribe-row pb-2 align-items-center">
                        <div class="col-md-12">
                            <h3 class="fs-4 mb-2 fw-normal">
                                Стратегически документи
                            </h3>
                        </div>

                        <div class="user-comment-wrapper">
                            <ul class="list-group">
                                <li class="list-group-item user-profile-comment">
                                    <a href="#" class="text-decoration-none">
                                        Програми за разширяване и подобряване на
                                        сградния фонд и материалната база в системата на образованието за периода 2024 -
                                        2026 г.
                                    </a>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item user-profile-comment">
                                    <a href="#" class="text-decoration-none">Проект на Правилник за изменение и
                                        допълнение на
                                        Устройствения правилник на Центъра за развитие на човешките ресурси и регионални
                                        инициативи
                                    </a>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-3 mb-4">
                    <div class="row subscribe-row pb-2 align-items-center">
                        <div class="col-md-12">
                            <h3 class="fs-4 mb-2 fw-normal">
                                Партньорство за открито управление
                            </h3>
                        </div>

                        <div class="user-comment-wrapper">
                            <ul class="list-group">
                                <li class="list-group-item user-profile-comment">
                                    <a href="#" class="text-decoration-none">Национални планове за действие
                                    </a>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-3 mb-4">
                    <div class="row subscribe-row pb-2 align-items-center">
                        <div class="col-md-12">
                            <h3 class="fs-4 mb-2 fw-normal">
                                Законодателни инициативи
                            </h3>
                        </div>

                        <div class="user-comment-wrapper">
                            <ul class="list-group">
                                <li class="list-group-item user-profile-comment">
                                    <a href="#" class="text-decoration-none">Промяна в наименование на нормативния акт
                                    </a>
                                    <div class="obj-comment comment-background p-2 rounded my-3">
                                        <div class="info">
                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                Asap Admin
                                            </span>

                                            <span class="obj-icon-info me-2 text-muted">
                                                07.12.2023 12:39
                                            </span>
                                            <form class="d-none" method="POST"
                                                action="http://strategy.test/legislative-initiatives/comments/1/delete"
                                                name="DELETE_COMMENT_0">
                                                <input type="hidden" name="_token"
                                                    value="30N3uGOHDuzikykgH26TZ7EHcxIrPVlv9tNJsh1Q"> </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                    role="button" title="Изтрий"></i>
                                            </a>
                                        </div>

                                        <div class="comment rounded py-2">
                                            <p class="mb-2">
                                                test
                                            </p>

                                            <div class="mb-0">
                                                <!-- LIKES -->

                                                0

                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/like"
                                                    class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>

                                                <!-- DISLIKES -->

                                                0
                                                <a href="http://strategy.test/legislative-initiatives/comments/1/stats/store/dislike"
                                                    class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>


@endsection
