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
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'content', 'required' => true])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'npo_partner', 'required' => false])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'responsible_administration', 'required' => false])
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
                                                <a href="{{ route('admin.ogp.plan.edit', $ogpPlanArea->ogp_plan_id) }}#area-tab-{{ $ogpPlanArea->ogp_area_id }}" class="btn btn-danger mr-1" title="{{ __('custom.cancel') }}">
                                                    <i class="fas fa-times me-2"></i> {{ __('custom.cancel') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


