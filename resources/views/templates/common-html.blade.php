@extends('layouts.admin')

@section('content')
<style>
    /*За добаявне в admin.css*/
    a.btn.btn-sm i {
        text-align: center;
        min-width: 16px;
        max-width: 16px;
    }

    a.btn.btn-sm i,
    a.btn i {
        line-height: inherit;
    }

</style>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 my-3">
            <h2>Секция с бутони</h2>
        </div>

        <div class="col-md-12 my-3">
            <h3>Бутони</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-2">
                <p>Запази</p>
                <a href="#" class="btn btn-success" title="Запази">
                    <i class="fas fa-save me-2"></i>Запази
                </a>
            </div>
            <div class="col-md-2">
                <p>Запази и остани</p>
                <a href="#" class="btn btn-success" title="Запази и остани">
                    <i class="fas fa-save me-2"></i>Запази и остани
                </a>
            </div>
            <div class="col-md-2">
                <p>Откажи</p>
                <a href="#" class="btn btn-danger mr-1" title="Откажи">
                    <i class="fas fa-ban me-2"></i>Откажи
                </a>
            </div>


        </div>

        <div class="row">
            <div class="col-md-2">
                <p>Добавяне</p>
                <a href="#" class="btn btn-success" title="Добавяне">
                    <i class="fas fa-plus me-2"></i>Добавяне
                </a>
            </div>

            <div class="col-md-2">
                <p>Редактиране</p>
                <a href="#" class="btn btn-info" title="Редактиране">
                    <i class="fas fa-edit me-2"></i>Редактиране
                </a>
            </div>

            <div class="col-md-2">
                <p>Изтриване</p>
                <a href="#" class="btn btn-danger" title="Изтриване">
                    <i class="fas fa-trash me-2"></i>Изтриване
                </a>
            </div>


            <div class="col-md-2">
                <p>Преглед</p>
                <a href="#" class="btn btn-primary" title="Преглед">
                    <i class="fas fa-eye me-2"></i>Преглед
                </a>
            </div>


            <div class="col-md-2">
                <p>Изчистване</p>
                <a href="#" class="btn btn-default" title="Изчистване">
                    <i class="fas fa-eraser me-2"></i>Изчистване
                </a>
            </div>

            <div class="col-md-2">
                <p>Търсене</p>
                <a href="#" class="btn btn-success" title="Търсене">
                    <i class="fas fa-search me-2"></i>Търсене
                </a>
            </div>
        </div>


        <hr class="mt-5">


        <div class="col-md-12 my-3">
            <h3>Бутони само с икони</h3>
        </div>

        <div class="row">
            <div class="col-md-2">
                <p>Добавяне</p>
                <a href="#" class="btn btn-sm btn-success" title="Добавяне">
                    <i class="fas fa-plus"></i>
                </a>
            </div>

            <div class="col-md-2">
                <p>Редактиране</p>
                <a href="#" class="btn btn-sm btn-info mr-1" title="Редакция">
                    <i class="fas fa-edit"></i>
                </a>
            </div>

            <div class="col-md-2">
                <p>Изтриване</p>
                <a href="#" class="btn btn-sm btn-danger mr-1" title="Изтриване">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
            <div class="col-md-2">
                <p>Преглед</p>
                <a href="#" class="btn btn-sm btn-primary mr-1" title="Преглед">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
            <div class="col-md-2">
                <p>Изчистване</p>
                <a href="#" class="btn btn-sm btn-default mr-1" title="Изчистване">
                    <i class="fas fa-eraser"></i>
                </a>
            </div>
            <div class="col-md-2">
                <p>Премахване от ПЧ</p>
                <a href="#" class="btn btn-sm btn-secondary mr-1" title="Премахване от публична част">
                    <i class="fas fa-eye-slash"></i>
                </a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-2">
                <p>Одобряване</p>
                <a href="#" class="btn btn-sm btn-success mr-1" title="Одобряване">
                    <i class="fas fa-check"></i>
                </a>
            </div>
            <div class="col-md-2">
                <p>Отказ</p>
                <a href="#" class="btn btn-sm btn-danger mr-1" title="Отказ">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>

        <div class="col-md-12 my-3">
            <h4>Пример 1</h4>

            <div class="card">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <a href="https://strategy.asapbg.com/admin/advisory-boards/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Добави Консултативен съвет
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Име на съвет</th>
                                <th>Активен</th>
                                <th>Дата на създаване</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd">
                                <td>3</td>
                                <td>TEST</td>
                                <td>
                                    <div id="active_form_3">
                                        <input type="hidden" name="id" class="id" value="3">
                                        <input type="hidden" name="model" class="model" value="AdvisoryBoard">
                                        <div class="status-box">
                                            <span class="badge badge-success status" style="cursor: pointer"
                                                data-status="0"
                                                onclick="ConfirmToggleBoolean('active','3','Сигурни ли сте, че искате да деактивирате TEST')">
                                                Да
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>2023-11-29 17:51:13</td>
                                <td class="text-left">
                                    <a href="#" class="btn btn-sm btn-info mr-1" title="Редакция">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger mr-1" title="Изтриване">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-primary mr-1" title="Преглед">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-secondary mr-1"
                                        title="Премахване от публична част">
                                        <i class="fas fa-eye-slash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 my-3">
            <h4>Пример 2</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <a href="https://strategy.asapbg.com/admin/advisory-boards/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Добави Консултативен съвет
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Заглавие</th>
                                <th>Име на съвет</th>
                                <th>Активен</th>
                                <th>Дата на създаване</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd">
                                <td>3</td>
                                <td>Проект на Отчет за 2022 г. за изпълнение на Актуализираната национална стратегия за
                                    демографско развитие на населението в Република България (2012 – 2030г.);</td>
                                <td>TEST</td>
                                <td>
                                    <div id="active_form_3">
                                        <input type="hidden" name="id" class="id" value="3">
                                        <input type="hidden" name="model" class="model" value="AdvisoryBoard">
                                        <div class="status-box">
                                            <span class="badge badge-success status" style="cursor: pointer"
                                                data-status="0"
                                                onclick="ConfirmToggleBoolean('active','3','Сигурни ли сте, че искате да деактивирате TEST')">
                                                Да
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>2023-11-29 17:51:13</td>
                                <td class="text-left ">
                                    <a href="#" class="btn btn-sm btn-info mr-1" title="Редакция">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger mr-1" title="Изтриване">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-primary mr-1" title="Преглед">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-secondary mr-1"
                                        title="Премахване от публична част">
                                        <i class="fas fa-eye-slash"></i>
                                    </a>
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
            <h5 class="text-danger">Когато бутоните от колона Действия падат на два реда, добавяме в заглавието
                "Действия"<i class="text-dark">('th' min-width:/определно число спрямо случая/vh)<span class="text-dark"
                        style="font-size:16px;"> style="min-width:25vh;"</span></i></h5>
            <div class="card">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <a href="https://strategy.asapbg.com/admin/advisory-boards/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Добави Консултативен съвет
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Заглавие</th>
                                <th>Име на съвет</th>
                                <th>Активен</th>
                                <th>Дата на създаване</th>
                                <th style="min-width:25vh;">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd">
                                <td>3</td>
                                <td>Проект на Отчет за 2022 г. за изпълнение на Актуализираната национална стратегия за
                                    демографско развитие на населението в Република България (2012 – 2030г.);</td>
                                <td>TEST</td>
                                <td>
                                    <div id="active_form_3">
                                        <input type="hidden" name="id" class="id" value="3">
                                        <input type="hidden" name="model" class="model" value="AdvisoryBoard">
                                        <div class="status-box">
                                            <span class="badge badge-success status" style="cursor: pointer"
                                                data-status="0"
                                                onclick="ConfirmToggleBoolean('active','3','Сигурни ли сте, че искате да деактивирате TEST')">
                                                Да
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>2023-11-29 17:51:13</td>
                                <td class="text-left ">
                                    <a href="#" class="btn btn-sm btn-info mr-1" title="Редакция">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger mr-1" title="Изтриване">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-primary mr-1" title="Преглед">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-secondary mr-1"
                                        title="Премахване от публична част">
                                        <i class="fas fa-eye-slash"></i>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 my-3">
                <h2>Заглавие и пътечки в страница</h2>
            </div>
        </div>
    </div>
</section>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Редактиране на /Текст/</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="/admin">Начало</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/admin/consultations">Консултации</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Редактиране на /Текст/
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>



<hr>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 my-3">
                <h2>Главен контейнер</h2>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header fw-bold">
                        <h2 class="mb-0 fs-4">Заглавие</h2>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<hr>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 my-3">
                <h2>Главен контейнер със секции</h2>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header fw-bold">
                       <h2 class="mb-0 fs-4">Заглавие</h2>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="card card-secondary p-0 mt-4">
                                <div class="card-body">
                                    <h3>Основна информация</h3>
                                    <div class="row mb-2">    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example">Пример текст<span class="required">*</span></label>
                                                <input id="example" type="text" class="form-control form-control-sm "> 
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example">Пример текст<span class="required">*</span></label>
                                                <input id="example" type="text" class="form-control form-control-sm "> 
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row mb-2">    
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="select-example">Пример текст<span class="required">*</span></label>
                                                <select class="form-select select2"aria-label="Default select example">
                                                    <option value="1">Всички</option>
                                                    <option value="1">Финанси и данъчна политика</option>
                                                    <option value="1">Партньорство за открито управление</option>
                                                    <option value="1">Енергетика</option>
                                                    <option value="1">Защита на потребителите</option>
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row mb-2">    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="textarea">Пример текст<span class="required">*</span></label>
                                                <textarea name="textarea" id="textarea" cols="30" rows="10" class="summernote"></textarea>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="textarea">Пример текст<span class="required">*</span></label>
                                                <textarea name="textarea" id="textarea" cols="30" rows="10" class="summernote"></textarea>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
