@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.legislative_initiatives_list', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.legislative_initiatives_list', 2) }}
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
                            <label class="col-sm-12 control-label">{{ trans_choice('validation.attributes.groups', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select class="form-control form-control-sm select2">
                                    <option>Изберете</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ trans_choice('validation.attributes.category', 1) }}<span class="required">*</span></label>
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
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.text') }} <span class="required">*</span></label>
                            <textarea class="ckeditor" name="text" style="width: 100%;"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" class="checkbox">
                                {{ __('custom.active') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="denied">
                                <input type="checkbox" id="denied" name="denied" class="checkbox">
                                {{ __('custom.denied') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.reason_denied') }} <span class="required">*</span></label>
                            <textarea class="ckeditor" name="reason_denied" style="width: 100%;"></textarea>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>

                        @include('admin.partial.attached_documents')
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
