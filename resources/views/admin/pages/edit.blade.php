@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.pages', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.pages', 2) }}
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
                    <form action="" method="post" name="form" id="form">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="subtitle">{{ __('validation.attributes.subtitle') }} <span class="required">*</span></label>
                            <textarea id="subtitle" name="subtitle" style="width: 100%" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="specialised_page">
                                <input type="checkbox" id="specialised_page" name="specialised_page" class="checkbox">
                                {{ __('validation.attributes.specialised_page') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="url">{{ __('validation.attributes.url') }} <span class="required">*</span></label>
                            <input type="text" id="url" name="url" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.content') }} <span class="required">*</span></label>
                            <textarea name="content" class="ckeditor"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="css_class">{{ __('validation.attributes.css_class') }}</label>
                            <input type="text" id="css_class" name="css_class" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="show_main_menu">
                                <input type="checkbox" id="show_main_menu" name="show_main_menu">
                                {{ __('validation.attributes.show_main_menu') }}
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" class="checkbox">
                                {{ __('validation.attributes.active') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="deleted">
                                <input type="checkbox" id="deleted" name="deleted" class="checkbox">
                                {{ __('validation.attributes.deleted') }}
                            </label>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
