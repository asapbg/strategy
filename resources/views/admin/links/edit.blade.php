@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.links', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.links', 2) }}
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
                            <label class="col-sm-12 control-label">{{ trans_choice('validation.attributes.link_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select class="form-control form-control-sm select2">
                                    <option>Изберете</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="url">{{ __('validation.attributes.internet_address') }} <span class="required">*</span></label>
                            <input type="text" id="url" name="url" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.text') }} <span class="required">*</span></label>
                            <textarea name="text" style="width: 100%;"></textarea>
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
