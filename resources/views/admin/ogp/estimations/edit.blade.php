@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.ogp.estimations', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">{{ __('custom.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.ogp.estimations', 2) }}
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
                            <label class="col-sm-12 control-label">{{ trans_choice('validation.attributes.estimation_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select class="form-control form-control-sm select2">
                                    <option>Изберете</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                            <textarea id="title" name="title" style="width: 100%" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ __('validation.attributes.description') }} <span class="required">*</span></label>
                            <textarea class="ckeditor"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" class="checkbox">
                                {{ __('validation.attributes.active') }}
                            </label>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.ogp.estimations.index', ['element' => 1]) }}"
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
