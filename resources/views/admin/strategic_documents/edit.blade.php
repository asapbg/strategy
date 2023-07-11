@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.strategic_documents', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.strategic_documents', 2) }}
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
                            <label class="col-sm-12 control-label">{{ trans_choice('validation.attributes.category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select class="form-control form-control-sm select2">
                                    <option>Изберете</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">
                                {{ trans_choice('custom.act', 1) }} <span class="required">*</span>
                            </label>
                            <div class="col-12">
                                <select class="form-control form-control-sm select2">
                                    <option>{{ __('custom.select') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="document_number">{{ __('custom.document_number') }} <span class="required">*</span></label>
                            <input type="text" id="document_number" name="document_number" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="document_date">{{ __('custom.document_date') }} <span class="required">*</span></label>
                            <input type="text" id="document_date" name="document_date" data-provide="datepicker" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                            <textarea id="title" name="description" style="width: 100%" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.description') }} <span class="required">*</span></label>
                            <textarea class="ckeditor"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="date_valid">{{ __('custom.date_valid') }} <span class="required">*</span></label>
                            <input type="text" id="date_valid" name="date_valid" data-provide="datepicker" class="form-control form-control-sm">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active"
                                    class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.active') }} <span class="required">*</span>
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
                    
                    @include('admin.partial.attached_documents')
                </div>
            </div>
        </div>
    </section>
@endsection
