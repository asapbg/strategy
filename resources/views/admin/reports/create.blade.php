@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form
                        action="{{ route('admin.reports.store') }}" method="post" name="form" id="form">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_bg">{{ __('validation.attributes.name_bg') }}</label>
                                <input id="name_bg" name="name_bg" type="text" class="form-control"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_en">{{ __('validation.attributes.name_en') }}</label>
                                <input id="name_en" name="name_en" type="text" class="form-control"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label
                                    for="type">{{ trans_choice('validation.attributes.type', 1) }}
                                    <span class="required">*</span>
                                </label>

                                <select id="type" name="type"
                                        class="form-control form-control-sm select2 @error('type'){{ 'is-invalid' }}@enderror">
                                    <option value="">---</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}"
                                                data-id="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>

                                @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label
                                    for="field_of_action_id">{{ trans_choice('validation.attributes.field_of_action', 1) }}
                                    <span class="required">*</span>
                                </label>

                                <select id="field_of_action_id" name="strategic_document_level_id"
                                        class="form-control form-control-sm select2 @error('field_of_action_id'){{ 'is-invalid' }}@enderror">
                                    <option value="">---</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action->id }}"
                                                data-id="{{ $action->id }}">{{ $action->name }}</option>
                                    @endforeach
                                </select>

                                @error('strategic_document_level_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="date_valid">{{__('custom.date_valid')}}<span class="required">*</span></label>
                                <input id="date_valid" type="text" name="date_valid" class="form-control datepicker" value="{{ request()->get('date_valid') ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="document_number">{{__('validation.attributes.document_accepted_with')}}<span class="required">*</span></label>
                                <input id="document_number" type="number" name="document_number" class="form-control" value="{{ request()->get('document_number') ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.nomenclature.field_of_actions.index') }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>

                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
