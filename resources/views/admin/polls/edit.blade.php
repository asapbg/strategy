@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.polls', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.polls', 2) }}
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
                    <div class="row">
                        <div class="col-sm-6">
                            <form action="" method="post" name="form" id="form">
                                @csrf
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                                    <textarea id="title" name="description" style="width: 100%" rows="5"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="open_from">{{ __('validation.attributes.open_from') }} <span class="required">*</span></label>
                                    <input type="text" id="open_from" name="open_from" data-provide="datepicker" class="form-control form-control-sm">
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="open_to">{{ __('validation.attributes.open_to') }} <span class="required">*</span></label>
                                    <input type="text" id="open_to" name="open_to" data-provide="datepicker" class="form-control form-control-sm">
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">{{ __('validation.attributes.content') }} <span class="required">*</span></label>
                                    <textarea class="ckeditor"></textarea>
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
                        <div class="col-sm-6">
                            <h3>{{ trans_choice('custom.answers', 2) }}</h3>
                            <a href="#" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.answers', 1) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
