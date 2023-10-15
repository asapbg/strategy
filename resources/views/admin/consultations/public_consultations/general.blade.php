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
                <label class="col-sm-12 control-label" for="act_type_id">{{ trans_choice('validation.attributes.act_type_id', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="act_type_id" name="act_type_id" class="cl-child form-control form-control-sm select2 select2-no-clear @error('act_type_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($actTypes) && $actTypes->count())
                            @foreach($actTypes as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('act_type_id', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                        data-cl="{{ $row->consultationLevel->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('act_type_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" id="normative_act_pris_section">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="pris_act_id">{{ trans_choice('custom.acts_pris', 1) }}</label>
                <div class="col-12">
                    <select id="pris_act_id" name="pris_act_id" class="form-control form-control-sm select2 @error('pris_act_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($prisActs) && $prisActs->count())
                            @foreach($prisActs as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('pris_act_id', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('pris_act_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-4" id="normative_act_section">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="regulatory_act_id">{{ trans_choice('custom.regulatory_acts', 1) }}</label>
                <div class="col-12">
                    <select id="regulatory_act_id" name="regulatory_act_id" class="form-control form-control-sm select2 @error('regulatory_act_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($regulatoryActs) && $regulatoryActs->count())
                            @foreach($regulatoryActs as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('regulatory_act_id', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                        data-id="{{ $row->id }}"
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('regulatory_act_id')
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
                @error('open_from')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="open_to">{{ __('validation.attributes.open_to') }} <span class="required">*</span></label>
                <input type="text" id="open_to" name="open_to"
                       class="form-control form-control-sm datepicker-today @error('open_to'){{ 'is-invalid' }}@enderror"
                       value="{{ old('open_to', ($item->id ? $item->open_to : '')) }}">
                @error('open_to')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
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
        @include('admin.partial.edit_field_translate', ['field' => 'short_term_reason'])
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
            <label class="col-sm-12 control-label" for="act_links">{{ __('custom.act_links') }}</label>
            <div class="col-12">
                <textarea id="act_links" name="act_links"
                          class="form-control form-control-sm summernote @error('act_links'){{ 'is-invalid' }}@enderror">{{ old('act_links', ($item->id ? $item->act_links : ($default_val ?? '' ) )) }}</textarea>
                @error('act_links')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        @include('admin.partial.edit_field_translate', ['field' => 'responsible_unit'])
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="active"></label>
                <select id="active" name="active" class="form-control form-control-sm select2 @error('active'){{ 'is-invalid' }}@enderror">
                    <option value="0" @if(!old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.inactive') }}</option>
                    <option value="1" @if(old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.active') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 col-md-offset-3">
            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
            <button id="save" type="submit" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>
            <a href="{{ route($listRouteName) }}"
               class="btn btn-primary">{{ __('custom.cancel') }}</a>
        </div>
    </div>
</form>
