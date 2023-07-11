@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.operational_programs', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.operational_programs', 2) }}
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
                    <p><b>Съдържанието е на Български</b></p>
                    <form action="" method="post" name="form" id="form">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="description">{{ __('validation.attributes.description') }} <span class="required">*</span></label>
                            <textarea id="description" name="description" style="width: 100%" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="effective_from">{{ __('validation.attributes.effective_from') }} <span class="required">*</span></label>
                            <input type="text" id="effective_from" name="effective_from" data-provide="datepicker"
                                class="form-control form-control-sm @error('effective_from'){{ 'is-invalid' }}@enderror"
                                >
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="effective_to">{{ __('validation.attributes.effective_to') }} <span class="required">*</span></label>
                            <input type="text" id="effective_to" name="effective_to" data-provide="datepicker"
                                class="form-control form-control-sm"
                                >
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="open_from">{{ __('validation.attributes.description') }} <span class="required">*</span></label>
                            <textarea class="ckeditor"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active"
                                    class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.active') }} <span class="required">*</span>
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
                    
                    <div>
                        <h5>{{ trans_choice('custom.attached_documents', 2) }}</h5>
                        <button class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            {{ __('custom.add') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
