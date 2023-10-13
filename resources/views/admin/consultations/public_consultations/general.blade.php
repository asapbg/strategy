@php($storeRoute = route($storeRouteName, ['item' => $item]))
<form action="{{ $storeRoute }}" method="post" name="form" id="form">
    @csrf
    @if($item->id)
        @method('PUT')
    @endif
    <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="consultation-type-select">{{ trans_choice('custom.consultation_type', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="consultation-type-select" name="consultation_type_id" class="form-control form-control-sm select2 select2-no-clear @error('consultation_type_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($consultationTypes) && $consultationTypes->count())
                            @foreach($consultationTypes as $row)
                                <option value="{{ $row->id }}" @if(old('consultation_type_id', ($item->id ? $item->consultation_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('consultation_type_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="consultation_level_id">{{ trans_choice('custom.consultation_level', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="consultation_level_id" name="consultation_level_id" data-cl="{{ $row->id }}" class="form-control form-control-sm select2-no-clear @error('consultation_level_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($consultationLevels) && $consultationLevels->count())
                            @foreach($consultationLevels as $row)
                                <option value="{{ $row->id }}" @if(old('consultation_level_id', ($item->id ? $item->consultation_level_id : 0)) == $row->id) selected @endif>{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('consultation_level_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="act_type">{{ trans_choice('custom.act_type', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="act_type" name="act_type" class="cl-child form-control form-control-sm select2 select2-no-clear @error('act_type'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($actTypes) && $actTypes->count())
                            @foreach($actTypes as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('act_type', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                        data-cl="{{ $row->consultationLevel->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('act_type')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" id="normative_act_pris_section">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="normative_act_pris">{{ trans_choice('custom.acts_pris', 1) }}</label>
                <div class="col-12">
                    <select id="normative_act_pris" name="normative_act_pris" class="form-control form-control-sm select2 @error('normative_act_pris'){{ 'is-invalid' }}@enderror">
                        @if(isset($prisActs) && $prisActs->count())
                            @foreach($prisActs as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('normative_act_pris', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('normative_act_pris')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-4" id="normative_act_section">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="normative_act">{{ trans_choice('custom.regulatory_acts', 1) }}</label>
                <div class="col-12">
                    <select id="normative_act" name="normative_act" class="form-control form-control-sm select2 @error('normative_act'){{ 'is-invalid' }}@enderror">
                        @if(isset($regulatoryActs) && $regulatoryActs->count())
                            @foreach($regulatoryActs as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('normative_act', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('normative_act')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" id="legislative_programs">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="legislative_program_id">{{ trans_choice('custom.legislative_programs', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="legislative_program_id" name="legislative_program_id" class="form-control form-control-sm select2 @error('legislative_program_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($legislativePrograms) && $legislativePrograms->count())
                            @foreach($legislativePrograms as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('legislative_program_id', ($item->id ? $item->legislative_program_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('legislative_program_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_legislative_program">
                    <input type="checkbox" id="no_legislative_program" name="no_legislative_program" value="1" class="checkbox ">
                    {{ __('custom.no_legislative_program') }}
                </label>
            </div>
        </div>

        <div class="col-md-4" id="operational_programs">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="operational_program_id">{{ trans_choice('custom.operational_programs', 1) }}</label>
                <div class="col-12">
                    <select id="operational_program_id" name="operational_program_id" class="form-control form-control-sm select2 @error('operational_program_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($operationalPrograms) && $operationalPrograms->count())
                            @foreach($operationalPrograms as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('operational_program_id', ($item->id ? $item->operational_program_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('operational_program_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_operational_program">
                    <input type="checkbox" id="no_operational_program" name="no_operational_program" value="1" class="checkbox ">
                    {{ __('custom.no_operational_program') }}
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="open_from">{{ __('validation.attributes.open_from') }} <span class="required">*</span></label>
                <input type="text" id="open_from" name="open_from"
                       class="form-control form-control-sm datepicker-today @error('open_from'){{ 'is-invalid' }}@enderror"
                       value="{{ old('open_from', ($item->id ? $item->open_from : '')) }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="open_to">{{ __('validation.attributes.open_to') }} <span class="required">*</span></label>
                <input type="text" id="open_to" name="open_to"
                       class="form-control form-control-sm datepicker-today @error('open_to'){{ 'is-invalid' }}@enderror"
                       value="{{ old('open_to', ($item->id ? $item->open_to : '')) }}">
            </div>
        </div>
        <div class="col-md-4">
            <label class="col-sm-12 control-label">&nbsp;</label>
            <p class="text-primary">
                {{ __('custom.period_in_days') }}
                <span id="period-total" class="fw-bold"></span>
                {{ mb_strtolower(trans_choice('custom.days', 2)) }}
            </p>
        </div>
        <div class="col-md-4 text-danger" id="duration-err"></div>
    </div>

    <div class="row" id="shortTermReason_section">
        {{-- TODO fix me hide if duration is more then 30 days --}}
        @include('admin.partial.edit_field_translate', ['field' => 'shortTermReason', 'required' => true])
    </div>

    <hr class="mb-5">
    <div class="row">
        @include('admin.partial.edit_field_translate', ['field' => 'title', 'required' => true, 'languages' => config('app.def')])
    </div>
    <div class="row">
        @include('admin.partial.edit_field_translate', ['field' => 'description', 'required' => true])
    </div>
    <div class="row">
        @include('admin.partial.edit_field_translate', ['field' => 'proposal_ways', 'required' => true, 'default_val' => __('custom.proposal_ways_default_html')])
    </div>

    <div class="row">
        <div class="form-group">
            <label class="col-sm-12 control-label" for="consultation_links">Звена</label>
            <div class="col-12">
                <textarea name="consultation_links" class="form-control form-control-sm summernote @error('consultation_links'){{ 'is-invalid' }}@enderror">{{ old('consultation_links', ($item->id ? $item->consultation_links : '')) }}</textarea>
                @error('consultation_links')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label" for="active">
            <input type="checkbox" id="active" name="active" value="1"
                   @if ($item->active) checked @endif
                   class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
            {{ __('validation.attributes.active') }} <span class="required">*</span>
        </label>
    </div>

    <div class="form-group row">
        <div class="col-md-6 col-md-offset-3">
            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
            <a href="{{ route($listRouteName) }}"
               class="btn btn-primary">{{ __('custom.cancel') }}</a>
        </div>
    </div>
</form>
