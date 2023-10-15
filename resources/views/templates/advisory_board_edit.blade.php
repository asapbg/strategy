
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
                        <h1>Консултативен съвет (редакция)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/admin">Начало</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Редакция на Консултативен съвет
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">Основна информация</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-files-tab" data-toggle="pill" href="#ct-files" role="tab" aria-controls="ct-files" aria-selected="false">Архив</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-files2-tab" data-toggle="pill" href="#ct-files2" role="tab" aria-controls="ct-files2" aria-selected="false">Допълнителна секция </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabsContent">
                            <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                                <div class="row mb-4">
                                    <h5>Добавяне нова секция</h5>
                                    <hr>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" placeholder="Наименование">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-sm" placeholder="Поредност">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button id="save" type="submit" class="btn btn-success">Добави</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Наименование <span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Област на политика <span class="required"> *</span></label>
                                            <select class="form-control form-control-sm">
                                                <option>От номенклатура</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="highlighted">
                                                <input type="checkbox" id="highlighted" name="highlighted" value="1" class="checkbox ">
                                                Наличие на представител на НПО в състава на съвета
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Органът, към който е създаден съветът  <span class="required"> *</span></label>
                                            <select class="form-control form-control-sm">
                                                <option>От номенклатура</option>
                                            </select>
                                        </div>
                                    </div>
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Председатели</h5>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Вид председател <span class="required"> *</span></label>
                                            <select class="form-control form-control-sm">
                                                <option>От номенклатура</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Председател (име) <span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Заместник-председател</label>
                                            <input type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Членове на съвета</h5>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control form-control-sm" placeholder="Име">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control form-control-sm" placeholder="Длъжност">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control form-control-sm" placeholder="Организация">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <button id="save" type="submit" class="btn btn-success">Добави</button>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th>Име</th>
                                            <th>Длъжност</th>
                                            <th>Организация</th>
                                            <th></th>
                                        </tr>
                                        <tbody>
                                            <tr>
                                                <td>Магдалена Миткова</td>
                                                <td>Програмист</td>
                                                <td>АСАП ЕООД</td>
                                                <td>
                                                    <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Секретариат</h5>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Секретар на съвета<span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Секретариат на съвета<span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th colspan="2">Файлове</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Име файл</td>
                                            <td>
                                                <i class="fas fa-download text-info" title="Свали"></i>
                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Работна програма и отчети</h5>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Работна програма<span class="required"> *</span></label>
                                            <textarea class="form-control form-control-sm summernote"></textarea>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th colspan="2">Файлове</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Име файл</td>
                                            <td>
                                                <i class="fas fa-download text-info" title="Свали"></i>
                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
{{--                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Нормативна рамка</h5>--}}
{{--                                    <div class="col-md-8">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label class="control-label" for="active">Съдържание<span class="required"> *</span></label>--}}
{{--                                            <textarea class="form-control form-control-sm summernote"></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <table class="table table-sm table-hover table-bordered">--}}
{{--                                        <tr>--}}
{{--                                            <th colspan="2">Файлове</th>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>--}}
{{--                                        </tr>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Име файл</td>--}}
{{--                                            <td>--}}
{{--                                                <i class="fas fa-download text-info" title="Свали"></i>--}}
{{--                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Заседания и решения</h5>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Съдържание<span class="required"> *</span></label>
                                            <textarea class="form-control form-control-sm summernote"></textarea>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th colspan="2">Файлове</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Име файл</td>
                                            <td>
                                                <i class="fas fa-download text-info" title="Свали"></i>
                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Информация за модератора „Консултативен съвет“</h5>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Съдържание<span class="required"> *</span></label>
                                            <textarea class="form-control form-control-sm summernote"></textarea>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th colspan="2">Файлове</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Име файл</td>
                                            <td>
                                                <i class="fas fa-download text-info" title="Свали"></i>
                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button id="save" type="submit" class="btn btn-success">Запиши</button>
                            </div>

                            <div class="tab-pane fade" id="ct-files" role="tabpanel" aria-labelledby="ct-files-tab">
                                <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Заседания и решения</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Списък<span class="required"> *</span></label>
                                            <select class="form-control form-control-sm" id="period">
                                                <option value="1">за месеца</option>
                                                <option value="2">за годината</option>
                                                <option value="3">за избран период</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 period d-none">
                                        <div class="form-group">
                                            <label class="control-label" for="active">От дата<span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm datepicker">
                                        </div>
                                    </div>
                                    <div class="col-md-3 period d-none">
                                        <div class="form-group">
                                            <label class="control-label" for="active">До дата<span class="required"> *</span></label>
                                            <input type="text" class="form-control form-control-sm datepicker">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm table-hover table-bordered">
                                    <tr>
                                        <th>Дата</th>
                                        <th>Решение</th>
                                        <th>Файлове</th>
                                    </tr>
                                    <tbody>
                                    <tr>
                                        <td>21.12.2022 г.</td>
                                        <td>Текст от решение</td>
                                        <td>
                                            <span class="d-block">Файл 1 <i class="fas fa-download text-info" title="Свали"></i></span>
                                            <span class="d-block">Файл 2 <i class="fas fa-download text-info" title="Свали"></i></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>05.02.2023 г.</td>
                                        <td>Текст от решение</td>
                                        <td>
                                            <span class="d-block">Файл 1 <i class="fas fa-download text-info" title="Свали"></i></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">Работни програми и отчети на програми</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="active">Списък<span class="required"> *</span></label>
                                            <select class="form-control form-control-sm">
                                                <option value="1">2023</option>
                                                <option value="2">2022</option>
                                                <option value="3">2021</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm table-hover table-bordered">
                                    <tr>
                                        <th>Дата</th>
                                        <th>Програма</th>
                                        <th>Файлове</th>
                                    </tr>
                                    <tbody>
                                    <tr>
                                        <td>21.12.2022 г.</td>
                                        <td>Текст програма 1</td>
                                        <td>
                                            <span class="d-block">Файл 1 <i class="fas fa-download text-info" title="Свали"></i></span>
                                            <span class="d-block">Файл 2 <i class="fas fa-download text-info" title="Свали"></i></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>05.02.2022 г.</td>
                                        <td>Текст програма 2</td>
                                        <td>
                                            <span class="d-block">Файл 1 <i class="fas fa-download text-info" title="Свали"></i></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>





                            <div class="tab-pane fade" id="ct-files2" role="tabpanel" aria-labelledby="ct-files2-tab">
                                <div class="row">
                                    <div class="col-12 mb-5">
                                        <div class="form-group">
                                            <label class="control-label bg-primary px-4 w-100 rounded-1" for="active">Съдържание допълнителна секция</label>
                                            <textarea class="form-control summernote"></textarea>
                                        </div>
                                        <button id="save" type="submit" class="btn btn-success">Запази</button>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered">
                                        <tr>
                                            <th colspan="2">Файлове</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><input type="file"><button id="save" type="button" class="btn btn-success">Качи</button></td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Име файл</td>
                                            <td>
                                                <i class="fas fa-download text-info" title="Свали"></i>
                                                <i class="fas fa-trash text-danger" title="Изтрий"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
        let colorDays = ['21-12-2022', '05-02-2023'];
        function highlightDays(date) {
            var fDate = $.datepicker.formatDate('dd-mm-yy', date);
            var result = [false, ""];
            $.each(colorDays, function(k, d) {
                if (fDate === d) {
                    result = [true, "highlight-green"];
                }
            });
            return result;
        }

        $(".period .datepicker").datepicker({
            dateFormat: "dd.mm.yy",
            beforeShowDay: highlightDays,
        });
        $('#period').on('change', function (){
            if(parseInt($(this).val()) == 2) {
                $('.period').removeClass('d-none');
            } else{
                $('.period').addClass('d-none');
            }
        });
    });
</script>

</body>
</html>
