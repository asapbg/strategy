@extends('layouts.admin')

@section('content')
    @php($data = json_decode($notification->data))
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">
                                {{ __('custom.general_info') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label" for="type">
                                    {{ __('custom.from') }}:
                                </label>
                                <div class="d-inline">
                                    {{ $data->from_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label" for="type">
                                    {{ __('custom.send_to') }}:
                                </label>
                                <div class="d-inline">
                                    {{ $data->to_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label" for="type">
                                    {{ __('validation.attributes.title') }}:
                                </label>
                                <div class="d-inline">
                                    {{ $data->subject }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label" for="type">
                                    {{ __('validation.attributes.content') }}: <span class="requred">*</span>
                                </label>
                                <div class="d-inline">
                                    {!! $data->message !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="{{ route('admin.advisory-boards.messages') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
