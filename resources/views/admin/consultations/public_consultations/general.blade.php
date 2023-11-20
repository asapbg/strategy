@php($storeRoute = route($storeRouteName, ['item' => $item]))
<form action="{{ $storeRoute }}" method="post" name="form" id="form">
    @csrf
    @if($item->id)
        @method('PUT')
    @endif
    <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
    <input type="hidden" name="nomenclature_level" id="nomenclature_level" value="{{ $item->id ? $item->nomenclatureLevelLabel : $userInstitutionLevel }}">
    <div class="row">
        @if($item->id)
            <div class="col-md-2">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('custom.number', 1) }}: </label> {{ $item->reg_num }}
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('custom.importers', 1) }}: </label> {{ $item->importerInstitution ?  $item->importerInstitution->name : '---'}}
                </div>
            </div>
            @if($item->pris)
                <div class="col-12">
                    <div class="form-group">
                        <label class="col-auto control-label">{{ trans_choice('custom.pris_documents', 1) }}: </label> <a class="text-primary" href="{{ route('admin.pris.edit', ['item' => $item->pris->id]) }}" target="_blank"><i class="fas fa-link mr-2"></i>{{ $item->pris->regNum.' ('.$item->pris->actType->name.')' }}</a>
                    </div>
                </div>
            @endif
        @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('custom.consultation_level', 1) }}: </label> {{ $item->id ? $item->nomenclatureLevelLabel : (isset($userInstitutionLevel) ? __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($userInstitutionLevel)) : '---') }}
                </div>
            </div>
        <div class="col-12"></div>
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
{{--                                        data-id="{{ $row->id }}"--}}
{{--                                        data-cl="{{ $row->consultationLevel->id }}"--}}
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
        <div class="col-md-4 my-3" id="legislative_programs">
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
                <label class="col-sm-12 control-label" for="legislative_program_row_id">{{ trans_choice('custom.legislative_programs_rows', 1) }}</label>
                <select id="legislative_program_row_id" name="legislative_program_row_id" data-types2ajax="lp_record"
                        data-urls2="{{ route('admin.select2.ajax', 'lp_record') }}"
                        data-placeholders2="{{ __('custom.search_lp_record_js_placeholder') }}"
                        class="form-control form-control-sm select2-autocomplete-ajax @error('legislative_program_row_id'){{ 'is-invalid' }}@enderror">
                    @if(!old('operational_program_row_id') && $item->legislative_program_row_id && $item->lpRow)
                        <option value="{{ $item->lpRow->id }}" selected="selected">{{ $item->lpRow->value }}</option>
                    @endif
                </select>
                @error('legislative_program_row_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_legislative_program">
                    <input type="checkbox" id="no_legislative_program" name="no_legislative_program"
                           @if(!old('no_legislative_program') && $item->legislative_program_id && !$item->lpRow) checked @endif
                           data-list="legislative_program_row_id" value="1" class="checkbox ">
                    {{ __('custom.no_legislative_program') }}
                </label>
            </div>
        </div>

        <div class="col-md-4 my-3" id="operational_programs">
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
                <label class="col-sm-12 control-label" for="operational_program_row_id">{{ trans_choice('custom.operational_programs_rows', 1) }}</label>
                <select id="operational_program_row_id" name="operational_program_row_id"
                        data-types2ajax="op_record" data-urls2="{{ route('admin.select2.ajax', 'op_record') }}"
                        data-placeholders2="{{ __('custom.search_op_record_js_placeholder') }}"
                        class="form-control form-control-sm select2-autocomplete-ajax @error('operational_program_row_id'){{ 'is-invalid' }}@enderror">
                    @if(!old('operational_program_row_id') && $item->operational_program_row_id && $item->opRow)
                        <option value="{{ $item->opRow->id }}" selected="selected">{{ $item->opRow->value }}</option>
                    @endif
                </select>
                @error('operational_program_row_id')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_operational_program">
                    <input type="checkbox" id="no_operational_program" name="no_operational_program"
                           @if(!old('no_legislative_program') && $item->operational_program_id && !$item->opRow) checked @endif
                           data-list="operational_program_row_id" value="1" class="checkbox ">
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
                       class="form-control form-control-sm datepicker-tomorrow @error('open_from'){{ 'is-invalid' }}@enderror"
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
                       class="form-control form-control-sm datepicker-tomorrow @error('open_to'){{ 'is-invalid' }}@enderror"
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
            <label class="col-sm-12 control-label" for="connected_pc">{{ __('custom.consultation_connections') }}</label>
            <div class="col-12">
{{--                data-connections="{{ json_encode($item->consultations->pluck('id')->toArray()) }}"--}}
                <select id="connected_pc" name="connected_pc[]" multiple="multiple" data-current="{{ $item->id ?? 0 }}"  data-types2ajax="pc" data-urls2="{{ route('admin.select2.ajax', 'pc') }}" data-placeholders2="{{ __('custom.search_pc_record_js_placeholder') }}" class="form-control form-control-sm select2-autocomplete-ajax @error('connected_pc'){{ 'is-invalid' }}@enderror">
                    @if($item->consultations->count())
                        @foreach($item->consultations as $row)
                            <option value="{{ $row->id }}" selected>{{ $row->title.' ('.displayDate($row->open_from).' - '.displayDate($row->open_to).')' }}</option>
                        @endforeach
                    @endif
                </select>
                @error('connected_pc')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @php($pcByOpLp = $item->connectedConsultationByProgram())
        @if($pcByOpLp->count())
            <div class="form-group">
                <label class="col-sm-12 control-label" for="connected_pc">{{ __('custom.consultation_connections_by_op_lp') }}</label>
                <div class="col-12">
                    @foreach($pcByOpLp as $row)
                        <p>{{ $row->title.' ('.displayDate($row->open_from).' - '.displayDate($row->open_to).')' }}</p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        @include('admin.partial.edit_field_translate', ['field' => 'responsible_unit'])
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="monitorstat">{{ __('validation.attributes.monitorstat') }}</label>
                <input type="text" id="monitorstat" name="monitorstat"
                       class="form-control form-control-sm @error('monitorstat'){{ 'is-invalid' }}@enderror"
                       value="{{ old('monitorstat', ($item->id ? $item->monitorstat : '')) }}">
                @error('monitorstat')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="active">{{ __('custom.status') }}</label>
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
