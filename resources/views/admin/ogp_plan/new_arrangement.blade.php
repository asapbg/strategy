@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header fw-bold">
                    {{ trans_choice('ogp.arrangements', 1) }}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ogp.plan.arrangement.edit_store', $ogpPlanArea->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item && $item->id ? $item->id : 0 }}" />
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="add-suggestion">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="custom-left-border">Описание на мярката:</h4>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'responsible_administration', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'problem', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'content', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'solving_problem', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'values_initiative', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'extra_info', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'npo_partner', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'interested_org', 'required' => false])
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="custom-left-border">Контактна информация:</h4>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'contact_names', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'contact_positions', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'contact_phone_email', 'required' => false])
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="custom-left-border">Начална и крайна дата за изпълнение на мярката:</h4>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label" for="from_date">{{ __('custom.from_date') }}</label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text" id="from_date" name="from_date" class="form-control form-control-sm datepicker @error('from_date'){{ 'is-invalid' }}@enderror" value="{{ old('from_date', $item && $item->id ? displayDate($item->from_date) : '') }}" autocomplete="off">
                                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                        </div>
                                                        @error('from_date')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label" for="to_date">{{ __('custom.to_date') }}</label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text" id="to_date" name="to_date" class="form-control form-control-sm datepicker @error('to_date'){{ 'is-invalid' }}@enderror" value="{{ old('to_date', $item && $item->id ? displayDate($item->to_date) : '') }}" autocomplete="off">
                                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                        </div>
                                                        @error('to_date')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <div class="col-md-12">
                                                <button class="btn btn-success" title="{{ __('custom.save') }}">
                                                    <i class="fas fa-save me-2"></i> {{ __('custom.save') }}
                                                </button>
                                                <a href="{{ url()->previous() }}#area-tab-{{ $ogpPlanArea->ogp_area_id }}" class="btn btn-danger mr-1" title="{{ __('custom.cancel') }}">
                                                    <i class="fas fa-times me-2"></i> {{ __('custom.cancel') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if($item->id)
                        <div class="row mt-5">
                            <div class="col-12">
                                <h4 class="custom-left-border">Дейности за и измерими резултати от изпълнението на мярката:</h4>
                            </div>
                        </div>
                        <form action="{{ route('admin.ogp.plan.action.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="0">
                            <input type="hidden" name="ogp_plan_arrangement_id" value="{{ $item->id }}">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="new_name_bg">{{ __('validation.attributes.new_name_bg') }} <span class="required">*</span> </label>
                                        <input type="text" name="new_name_bg" class="form-control form-control-sm @error('new_name_bg'){{ 'is-invalid' }}@enderror" value="{{ old('new_name_bg', '') }}" autocomplete="off">
                                        @error('new_name_bg')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="new_name_en">{{ __('validation.attributes.new_name_en') }} <span class="required">*</span> </label>
                                        <input type="text" name="new_name_en" class="form-control form-control-sm @error('new_name_en'){{ 'is-invalid' }}@enderror" value="{{ old('new_name_en', '') }}" autocomplete="off">
                                        @error('new_name_en')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="new_from_date">{{ __('validation.attributes.new_from_date') }} <span class="required">*</span> </label>
                                        <input type="text" id="new_from_date" name="new_from_date" class="form-control form-control-sm datepicker @error('new_from_date'){{ 'is-invalid' }}@enderror" value="{{ old('new_from_date', '') }}" autocomplete="off">
                                        @error('new_from_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="new_to_date">{{ __('validation.attributes.new_to_date') }} <span class="required">*</span> </label>
                                        <input type="text" id="new_to_date" name="new_to_date" class="form-control form-control-sm datepicker @error('new_to_date'){{ 'is-invalid' }}@enderror" value="{{ old('new_to_date', '') }}" autocomplete="off">
                                        @error('new_to_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" type="submit">{{ __('custom.add') }}</button>
                                </div>
                            </div>
                        </form>

                        @if($item->actions->count())
                            <table class="table">
                                <tbody>
                                    @foreach($item->actions as $k => $action)
                                        <tr id="action-{{ $action->id }}">
                                            <td>
                                                <input type="text" name="name" class="form-control form-control-sm " value="{{ old('name', $action->name) }}" autocomplete="off">
                                                <div class="ajax-error error_name text-danger mt-1 "></div>
                                            </td>
                                            <td >
                                                <input type="text" name="from_date" class="form-control form-control-sm datepicker " value="{{ old('from_date', $action->from_date) }}" autocomplete="off">
                                                <div class="ajax-error error_from_date text-danger mt-1 "></div>
                                            </td>
                                            <td>
                                                <input type="text" name="to_date" class="form-control form-control-sm datepicker" value="{{ old('to_date', $action->to_date) }}" autocomplete="off">
                                                <div class="ajax-error error_to_date text-danger mt-1 "></div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success mr-1">
                                                    <i class="fas fa-save save-arrangement-action" role="button" title="{{ __('custom.save') }}" data-action="{{ $action->id }}"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger mr-1">
                                                    <i class="fas fa-trash delete-arrangement-action" role="button" title="{{ __('custom.delete') }}" data-action="{{ $action->id }}"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

@endpush


