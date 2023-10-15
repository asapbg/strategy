
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Strategy :: Публикации</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="cRfo412J0ivy5hyr60L1bZhWzOX2p7jYuPiq4nBb"/>

    <link href="https://strategy.asapbg.com/css/admin.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var GlobalLang = "bg";
    </script>
    <script src="https://strategy.asapbg.com/vendor/ckeditor/ckeditor.min.js"></script>
    <script src="https://strategy.asapbg.com/vendor/ckeditor/translations/bg.min.js"></script>
    <link rel='stylesheet' type='text/css' property='stylesheet' href='//strategy.test/_debugbar/assets/stylesheets?v=1644393152&theme=auto'><script src='//strategy.test/_debugbar/assets/javascript?v=1644393152'></script><script>jQuery.noConflict(true);</script>
</head>
<body class="hold-transition sidebar-mini ">
<!-- Site wrapper -->
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link sidebar-toggle" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="hidden-xs">BG</span>
                </a>
                <ul class="dropdown-menu language dropdown-menu-left p-2">
                    <li>
                        <a href="https://strategy.asapbg.com/locale?locale=en">
                            EN
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="navbar-nav mx-auto">
            <h4>
                Супер Администратор
            </h4>
        </div>

        <ul class="navbar-nav ml-auto">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="hidden-xs">Asap  Admin</span>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                    <a class="dropdown-item dropdown-footer" href="javascript:;"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Изход <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="https://strategy.asapbg.com/logout" method="POST" class="d-none">
                        <input type="hidden" name="_token" value="cRfo412J0ivy5hyr60L1bZhWzOX2p7jYuPiq4nBb">                </form>
                </div>

            </li>
            <!-- Control Sidebar Toggle Button -->
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Logo -->
        <a href="https://strategy.asapbg.com/admin" class="brand-link">
        <span class="ml-2 brand-text">
            <img src="https://strategy.asapbg.com/img/logo.png" style="height: 40px; width: auto;">
            Strategy
        </span>
            <span class="ml-2 font-weight-light"></span>
        </a>

        <div class="sidebar">

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin"
                           class="nav-link ">
                            <i class="fas fa-home"></i>
                            <p>Начало</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link  active ">
                            <i class="nav-icon fas fa-ellipsis-v"></i>
                            <p>Публични секции<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/publications"
                                   class="nav-link  active ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Публикации</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/nomenclature/publication_category"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Категории публикации</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Обществени консултации<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/consultations/legislative_programs"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Законодателни програми</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/consultations/operational_programs"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Оперативни програми</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/consultations/public_consultations"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Консултации</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/consultations/comments"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Коментари</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/nomenclature/consultation_document_type"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Набори документи, според вида акт – обект на консултация</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Admin -->
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>Съдържание<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/pages"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Статично съдържание</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/impact_pages"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Оценки на въздействието</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/static_pages"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Статични страници</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/pages"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Мултикритериен анализ</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/polls"
                           class="nav-link ">
                            <i class="fal fa-check-square"></i>
                            <p>Анкети</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/activity-logs"
                           class="nav-link ">
                            <i class="fas fa-history"></i>
                            <p>Обща активност</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-info"></i>
                            <p>Стратегически документи<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/strategic_documents"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Стратегически документи</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="fas fa-hand-point-up"></i>
                            <p>Партньорство за ОУ<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/ogp/plan_elements"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Планове и оценки</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/ogp/articles"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Новини и събития</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-link"></i>
                            <p>Връзки<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/nomenclature/link_category"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Категории връзки</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/links"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Връзки</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="fas fa-weight"></i>
                            <p>Лица и възнаграждения<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/pc_subjects"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Списък с ФЛ/ЮЛ</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <i class="fas fa-weight"></i>
                            <p>Законодателни инициативи<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="https://strategy.asapbg.com/admin/legislative_initiatives"
                                   class="nav-link ">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>Списък със законодателни инициативи</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">Номенклатури</li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/nomenclature"
                           class="nav-link ">
                            <i class="fas fa-file"></i>
                            <p>Номенклатури</p>
                        </a>
                    </li>
                    <li class="nav-header">Потребители</li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/roles"
                           class="nav-link ">
                            <i class="fas fa-users"></i>
                            <p>Роли</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/users"
                           class="nav-link ">
                            <i class="fas fa-user"></i>
                            <p>Потребители</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/permissions"
                           class="nav-link ">
                            <i class="fas fa-gavel"></i>
                            <p>Права на потребители</p>
                        </a>
                    </li>

                    <li class="nav-header">Лични данни</li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/users/profile/1/edit"
                           class="nav-link ">
                            <i class="fas fa-user-cog"></i>
                            <p>Профил</p>
                        </a>
                    </li>

                    <hr class="text-white">
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/settings"
                           class="nav-link ">
                            <i class="fas fa-cogs"></i>
                            <p>Настройки</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://strategy.asapbg.com/admin/dynamic-structures"
                           class="nav-link ">
                            <i class="fas fa-cogs"></i>
                            <p>Динамични структури</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header p-0">
            <div class="container-fluid">
            </div>
        </section>

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>OGP FORUM (редакция)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/admin">Начало</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Редакция на OGP FORUM
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabsContent">
                            <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label bg-primary px-4 w-100 rounded-1" for="active">Правомощия, органи и структура на Форума</label>
                                            <textarea class="form-control summernote"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label bg-primary px-4 w-100 rounded-1" for="active">Работни групи</label>
                                            <textarea class="form-control summernote"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label bg-primary px-4 w-100 rounded-1" for="active">Членове на Форума</label>
                                            <textarea class="form-control summernote"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label bg-primary px-4 w-100 rounded-1" for="active">Съдържание</label>
                                            <textarea class="form-control summernote"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button id="save" type="submit" class="btn btn-success">Запиши</button>
                                        <button id="save" type="button" class="btn btn-primary">Отказ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Версия:</b> 1.0.0
        </div>
        <strong>
            Strategy
            Разработка и поддръжка
            <a href="https://www.asap.bg/" target="_blank">АСАП ЕООД</a>
        </strong>
    </footer>
    <div class="modal fade in" id="modal-alert">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">
                        Внимание!
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade in" id="modal-confirm">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="" method="get" name="confirm_form">
                    <!-- Modal Header -->
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">
                            Внимание!
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <p></p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Да</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Не</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<script src="https://strategy.asapbg.com/js/admin.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

    });
</script>

</body>
</html>
