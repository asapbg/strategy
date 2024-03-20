@php($disabled = $disabled ?? false)
<div class="row">
    @if(!$disabled)
        <div class="col-md-4">
            <form method="POST" action="{{ route('admin.ogp.plan.add_area', $item->id) }}">
                <input type="hidden" name="active" value="1">
                @csrf
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="ogp_area_id">{{ trans_choice('custom.area', 1) }} <span class="required">*</span></label>
                    <div class="col-12">
                        <select name="ogp_area" id="ogp_area" class="form-select @error('ogp_area'){{ 'is-invalid' }}@enderror">
                            <option value=""></option>
                            @foreach($ogpArea as $v)
                                <option value="{{ $v->id }}" @if(old('ogp_area', 0) == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                            @endforeach
                        </select>
                        @error('ogp_area')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <button id="save" type="submit" class="btn btn-success">Добави</button>
                </div>
            </form>
        </div>
    @endif
    <div class="col-md-8">
        <form action="{{ route('admin.ogp.plan.'.($item->id ? "edit" : "create").'_store') }}" method="post" name="form" id="form">
            @csrf
            @if($item->id)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
            @endif

            <div class="row mb-4">
                @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
            </div>
            <div class="row mb-4">
                @include('admin.partial.edit_field_translate', ['field' => 'content', 'required' => true])
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="from_date">{{ __('custom.from_date') }} <span class="required">*</span></label>
                        <div class="col-12">
                            <div class="input-group">
                                <input @if($disabled) disabled readonly @endif type="text" id="from_date" name="from_date" class="form-control form-control-sm datepicker @error('from_date'){{ 'is-invalid' }}@enderror" value="{{ old('from_date', displayDate($item->from_date) ?? '') }}" autocomplete="off">
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
                        <label class="col-sm-12 control-label" for="to_date">{{ __('custom.to_date') }} <span class="required">*</span></label>
                        <div class="col-12">
                            <div class="input-group">
                                <input @if($disabled) disabled readonly @endif type="text" id="to_date" name="to_date" class="form-control form-control-sm datepicker @error('to_date'){{ 'is-invalid' }}@enderror" value="{{ old('to_date', displayDate($item->to_date) ?? '') }}" autocomplete="off">
                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                            </div>
                            @error('to_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @if($disabled && $evaluationEdit)
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="self_evaluation_published_at">{{ __('ogp.evaluation') }} ({{ mb_strtolower(__('custom.published_at_f')) }})</label>
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="text" id="self_evaluation_published_at" name="self_evaluation_published_at" class="form-control form-control-sm datepicker @error('report_published_at'){{ 'is-invalid' }}@enderror" value="{{ old('self_evaluation_published_at', displayDate($item->self_evaluation_published_at) ?? '') }}" autocomplete="off">
                                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                </div>
                                @error('self_evaluation_published_at')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="develop_plan_id">{{ __('custom.connection') }} {{ __('custom.with') }} {{ __('custom.develop_plan') }}</label>
                        <div class="col-12">
                            <div class="input-group">
                                <select @if(!$devPlanEdit) disabled readonly @endif id="develop_plan_id" name="develop_plan_id" class="form-control form-control-sm @error('develop_plan_id'){{ 'is-invalid' }}@enderror">
                                    <option></option>
                                    @if($devPlans->count())
                                        @foreach($devPlans as $v)
                                            <option value="{{ $v->id }}" @if(old('develop_plan_id', $item->develop_plan_id ?? '') == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('develop_plan_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-6">--}}
{{--                    @include('admin.partial.active_field')--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="status">{{ __('custom.status') }} <span class="required">*</span></label>
                        <div class="col-12">
                            <div class="input-group">
                                <select @if($disabled && $evaluationEdit) disabled readonly @endif id="status" name="status" class="form-control form-control-sm @error('status'){{ 'is-invalid' }}@enderror">
                                    <option></option>
                                    @foreach(\App\Models\OgpStatus::get() as $v)
                                        @if(in_array($v->type, [\App\Enums\OgpStatusEnum::ACTIVE->value, \App\Enums\OgpStatusEnum::DRAFT->value]))
                                            <option value="{{ $v->id }}" @if(old('status', $item->ogp_status_id ?? '') == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 col-md-offset-3">
                    @if($disabled && !$evaluationEdit)
                        <button id="save_status" type="submit" name="save_status" class="btn btn-success" value="1">{{ __('custom.save') }}</button>
                    @elseif($disabled && $evaluationEdit)
                        <button id="save_dev_plan" type="submit" name="save_dev_plan" class="btn btn-success" value="1">{{ __('custom.save') }}</button>
                    @endif
                    @if(!$disabled)
                        <button id="save" type="submit" name="save" class="btn btn-success" value="1">{{ __('custom.save') }}</button>
                    @endif
                    <a href="{{ route('admin.ogp.plan.index') }}"
                       class="btn btn-primary">{{ __('custom.cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

