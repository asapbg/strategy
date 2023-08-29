@extends('layouts.admin')
@section('content')<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Създай Публикация</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="/admin">Начало</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Създай Публикация
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
                            <a class="nav-link" id="ct-files-tab" data-toggle="pill" href="#ct-files" role="tab" aria-controls="ct-files" aria-selected="false">Файлове</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade  active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                            <form class="row" action="" method="post" name="form" id="form">
                                @csrf

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="publication_category_id">{{ trans_choice('custom.publication_category', 1) }}<span class="required">*</span></label>
                                            <div class="col-12">
                                                <select id="publication_category_id" name="publication_category_id" class="form-control form-control-sm select2">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-12">Тип публикация:</label>
                                            <label class="w-auto control-label mr-4" for="type">
                                                <input type="radio" id="type" name="type" value="1">
                                                Библиотека
                                            </label>
                                            <label class="w-auto control-label" for="type">
                                                <input type="radio" id="type" name="type" value="2">
                                                Новина
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Заглавие (BG)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <input type="text" class="form-control form-control-sm " value="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Заглавие (EN)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <input type="text" class="form-control form-control-sm " value="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Анотация (BG)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Анотация (EN)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Описание (BG)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <textarea class="form-control summernote"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" >Описание (EN)<span class="required">*</span></label>
                                            <div class="col-12">
                                                <textarea class="form-control summernote"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="deleted">Дата на публикуване: <span class="required">*</span></label>
                                            <select class="form-control form-control-sm">
                                                <option>Активна</option>
                                                <option>Неактивна</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="deleted">Дата на публикуване:<span class="required">*</span></label>
                                            <input type="text" data-provide="datepicker" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="deleted">Снимка:</label>
                                            <input type="file" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="deleted">Дата на публикуване:<span class="required">*</span></label>
                                            <input type="text" data-provide="datepicker" class="form-control form-control-sm value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                        <a href="{{ route('admin.publications.index') }}"
                                           class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="tab-pane fade" id="ct-files" role="tabpanel" aria-labelledby="ct-files-tab">
                            <form action="http://pitay.test/admin/upload-file/28/21" method="post" name="form" id="form" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="s2TY7BYR84ysIwZzkU9IKguqyOdW64BUFPr1JHsV">                                    <div class="mb-3">
                                    <label for="description" class="form-label">Публично име <span class="required">*</span> </label>
                                    <input value="" class="form-control form-control-sm " id="description" type="text" name="description" autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="form-label">Изберете файл <span class="required">*</span> </label>
                                    <input class="form-control form-control-sm " id="file" type="file" name="file">
                                </div>
                                <button id="save" type="submit" class="btn btn-success">Запази</button>
                            </form>
                            <table class="table table-sm table-hover table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Име</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Приложение №1</td>
                                        <td>
                                            <i class="fas fa-file-download text-info" role="button" title="Свали"></i>
                                            <i class="fas fa-trash text-danger" role="button" title="Изтрий"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Приложение №2</td>
                                        <td>
                                            <i class="fas fa-file-download text-info" role="button" title="Свали"></i>
                                            <i class="fas fa-trash text-danger" role="button" title="Изтрий"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
