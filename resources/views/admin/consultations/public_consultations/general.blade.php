@php($storeRoute = route($storeRouteName, ['item' => $item]))
<form action="{{ $storeRoute }}" method="post" name="form" id="form">
    @csrf
    @if($item->id)
        @method('PUT')
    @endif
    <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
    <input type="hidden" name="nomenclature_level" id="nomenclature_level" value="@if(!$isAdmin || $item->id){{ $item->id ? $item->nomenclatureLevelLabel : $userInstitutionLevel }}@else{{ '0' }}@endif">
    <div class="row">
        @if($item->id)
            <div class="col-md-2">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('custom.number', 1) }}: </label> {{ $item->reg_num }}
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('custom.importers', 1) }}: </label> @if($item->importerInstitution) <a class="text-primary" href="{{ route('admin.strategic_documents.institutions.edit', $item->importerInstitution) }}" target="_blank"><i class="fas fa-link mr-1 fs-6"></i>{{ $item->importerInstitution->name }} @if(!empty($item->importer)){{ '('.$item->importer.')' }}@endif</a> @else{{ '---' }}@endif
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-auto control-label">{{ __('custom.importer_address') }}: </label> {{ $item->importerInstitution ? (($item->importerInstitution->settlement ? $item->importerInstitution->settlement->ime.', ' : '').$item->importerInstitution->address) : '---'}}
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-auto control-label">{{ trans_choice('site.public_consultation.responsible_institution', 1) }}: </label> @if($item->responsibleInstitution) <a class="text-primary" href="{{ route('admin.strategic_documents.institutions.edit', $item->responsibleInstitution) }}" target="_blank"><i class="fas fa-link mr-1 fs-6"></i>{{ $item->responsibleInstitution->name }}</a> @else{{ '---' }}@endif
                </div>
            </div>
            @if($item->pris)
                <div class="col-12">
                    <div class="form-group">
                        <label class="col-auto control-label">{{ trans_choice('custom.pris_documents', 1) }}: </label> <a class="text-primary" href="{{ route('admin.pris.edit', ['item' => $item->pris->id]) }}" target="_blank"><i class="fas fa-link mr-2"></i>{{ $item->pris->regNum.' ('.$item->pris->actType->name.')' }}</a>
                    </div>
                </div>
            @endif
        @else
            @if(!$isAdmin)
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="col-auto control-label">{{ trans_choice('custom.importers', 1) }}: </label> {{ auth()->user() && auth()->user()->institution ? auth()->user()->institution->name : '---'}}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="col-auto control-label">{{ __('custom.importer_address') }}: </label> {{ auth()->user() && auth()->user()->institution ? ((auth()->user()->institution->settlement ? auth()->user()->institution->settlement->ime.', ' : '').auth()->user()->institution->address) : '---'}}
                    </div>
                </div>
            @else
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-12 control-label" for="institution_id">
                            {{ trans_choice('custom.importers', 1) }}
                        </label>
                        <div class=" col-12 d-flex flex-row">
                            <div class="input-group">
                                <select class="form-control form-control-sm select2 @error('institution_id') is-invalid @enderror" name="institution_id" id="institution_id">
                                    <option value="" @if('' == old('institution_id', '')) selected @endif>---</option>
                                    @if(isset($institutions) && $institutions->count())
                                        @foreach($institutions as $option)
                                            <option value="{{ $option->value }}" @if($option->value == old('institution_id', ($item->id ? $item->institution_id : ''))) selected @endif
                                            data-level="{{ $option->level }}" data-foa="{{ $option->foa }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary ml-1 pick-institution"
                                    data-title="{{ trans_choice('custom.institutions',2) }}"
                                    data-url="{{ route('modal.institutions').'?select=1&multiple=0&admin=1&dom=institution_id' }}">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        @error('institution_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12"></div>
                @include('admin.partial.edit_field_translate', ['field' => 'importer', 'required' => false])
            @endif
        @endif
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-auto control-label">{{ trans_choice('custom.consultation_level', 1) }}: </label> <span id="levelLabel">@if(!$isAdmin || $item->id){{ $item->id ? $item->nomenclatureLevelLabel : (isset($userInstitutionLevel) ? __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($userInstitutionLevel)) : '---') }}@else{{ '---' }}@endif</span>
            </div>
        </div>
        @if($item->id)
            @if($item->importerInstitution && $item->importerInstitution->links->count())
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label class="col-auto control-label">{{ trans_choice('custom.useful_links', 2) }}: </label>
                        <div class="col-12">
                            @foreach($item->importerInstitution->links as $l)
                                @if(!$loop->first)
                                <br>
                                @endif
                                <a href="{{ $l->link }}" target="_blank" class="main-color text-decoration-none"><i class="fas fa-regular fa-link  main-color me-1 fs-6"></i> {{ $l->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="col-12"></div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="field_of_actions_id">{{ __('validation.attributes.field_of_actions_id') }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="field_of_actions_id" name="field_of_actions_id" class="cl-child form-control form-control-sm select2 select2-no-clear @error('field_of_actions_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($fieldsOfActions) && $fieldsOfActions->count())
                            @foreach($fieldsOfActions as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('field_of_actions_id', ($item->id ? $item->field_of_actions_id : 0)) == $row->id) selected @endif
                                >{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('field_of_actions_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="act_type_id">{{ trans_choice('validation.attributes.act_type_id', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select id="act_type_id" name="act_type_id" class="cl-child form-control form-control-sm select2 @error('act_type_id'){{ 'is-invalid' }}@enderror">
                        <option value="">---</option>
                        @if(isset($actTypes) && $actTypes->count())
                            <option value=""></option>
                            @foreach($actTypes as $row)
                                <option value="{{ $row->id }}" data-level="{{ $row->consultation_level_id }}"
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
            <div class="col-md-4" id="pris_section">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="pris_id">Постановление</label>
                    <div class="col-12">
                        <select id="pris_id" name="pris_id" data-types2ajax="pris_doc" data-legalacttype="{{ \App\Models\LegalActType::TYPE_DECREES }}" data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}" class="form-control form-control-sm select2-autocomplete-ajax @error('pris_id'){{ 'is-invalid' }}@enderror">
                            <option value="" @if(old('pris_id', ($item->id && $pris ? $item->pris_id : 0)) == 0) selected @endif>---</option>
                            @if(!old('pris_id') && $pris)
                                <option value="{{ $pris->id }}" selected>{{ $pris->displayName }}</option>
                            @endif
                        </select>
                        @error('pris_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="law_section">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="law_id">Закон</label>
                    <div class="col-12">
                        <select name="law_id" class="form-control form-control-sm select2 @error('law_id'){{ 'is-invalid' }}@enderror">
                            <option value="0">---</option>
                            @if(isset($laws) && $laws->count())
                                @foreach($laws as $row)
                                    <option value="{{ $row->id }}"
                                            @if(old('law_id', ($item->id ? $item->law_id : 0)) == $row->id) selected @endif
                                            data-id="{{ $row->id }}"
                                    >{{ $row->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('law_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-md-4 my-3" id="legislative_programs">
            <div class="form-group" id="legislative_program_id" >
                <label class="col-sm-12 control-label" for="legislative_program_id">{{ trans_choice('custom.legislative_programs', 1) }}<span class="required">*</span></label>
                <div class="col-12">
                    <select name="legislative_program_id" class="form-control form-control-sm select2 @error('legislative_program_id'){{ 'is-invalid' }}@enderror">
                        <option value="0" @if(old('legislative_program_id', ($item->id ? $item->legislative_program_id : 0)) == 0) selected @endif>---</option>
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
                @php($institutionid = $item->id ? ($item->importer_institution_id) : (auth()->user() && auth()->user()->institution ? auth()->user()->institution->id :0))
                <select id="legislative_program_row_id" name="legislative_program_row_id" data-types2ajax="lp_record_pc" data-institution="{{ $institutionid }}"
                        data-urls2="{{ route('admin.select2.ajax', 'lp_record_pc') }}"
                        data-placeholders2="{{ __('custom.search_lp_record_js_placeholder') }}"
                        class="form-control form-control-sm select2-autocomplete-ajax @error('legislative_program_row_id'){{ 'is-invalid' }}@enderror">
                    @if(!old('legislative_program_row_id') && $item->legislative_program_row_id && $item->lpRow)
                        <option value="{{ $item->lpRow->id }}" >{{ $item->lpRow->value }} {{ $item->lpRow->parent->recordPeriod }}</option>
                    @endif
                </select>
                @error('legislative_program_row_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_legislative_program">
                    <input type="checkbox" id="no_legislative_program" name="no_legislative_program"
                           @if(!old('no_legislative_program') && (($item->legislative_program_id && !$item->lpRow) && !$item->legislative_program_id )) checked @endif
                           data-list="legislative_program_row_id" value="1" class="checkbox ">
                    {{ __('custom.no_legislative_program') }}
                </label>
            </div>
        </div>

        <div class="col-md-4 my-3" id="operational_programs">
            <div class="form-group" id="operational_program_id" >
                <label class="col-sm-12 control-label" for="operational_program_id">{{ trans_choice('custom.operational_programs', 1) }}</label>
                <div class="col-12">
                    <select name="operational_program_id" class="form-control form-control-sm select2 @error('operational_program_id'){{ 'is-invalid' }}@enderror">
                        <option value="0" @if(old('operational_program_id', ($item->id ? $item->operational_program_id : 0)) == 0) selected @endif>---</option>
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
                @php($institutionid = $item->id ? ($item->importer_institution_id) : (auth()->user() && auth()->user()->institution ? auth()->user()->institution->id :0))
                <select id="operational_program_row_id" name="operational_program_row_id"
                        data-types2ajax="op_record_pc" data-urls2="{{ route('admin.select2.ajax', 'op_record_pc') }}" data-institution="{{ $institutionid }}"
                        data-placeholders2="{{ __('custom.search_op_record_js_placeholder') }}"
                        class="form-control form-control-sm select2-autocomplete-ajax @error('operational_program_row_id'){{ 'is-invalid' }}@enderror">
                    @if(!old('operational_program_row_id') && $item->operational_program_row_id && $item->opRow)
                        <option value="{{ $item->opRow->id }}" selected="selected">{{ $item->opRow->value }} {{ $item->opRow->parent->recordPeriod }}</option>
                    @endif
                </select>
                @error('operational_program_row_id')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label" for="no_operational_program">
                    <input type="checkbox" id="no_operational_program" name="no_operational_program"
                           @if(!old('no_operational_program', null) && (($item->operational_program_id && !$item->opRow) && !$item->operational_program_id)) checked @endif
                           data-list="operational_program_row_id" value="1" class="checkbox ">
                    {{ __('custom.no_operational_program') }}
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        @include('admin.partial.edit_field_translate', ['field' => 'responsible_unit'])
    </div>

    <hr class="mb-5">
    <div class="row mb-2">
        <div class="form-group">
            <label class="col-md-12 control-label" for="connected_pc">{{ __('custom.consultation_connections') }}</label>
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
{{--        @php($pcByOpLp = $item->connectedConsultationByProgram())--}}
        @php($pcByDecree = $item->decree ? $item->decree->decreesConsultation : null)
        @if($pcByDecree)
            <div class="form-group">
                <label class="col-sm-12 control-label" for="connected_pc">{{ __('custom.connections_by_op_lp') }}</label>
                <div class="col-12">
    {{--                @if($pcByOpLp->count())--}}
    {{--                    @foreach($pcByOpLp as $row)--}}
    {{--                        <p>{{ $row->title.' ('.displayDate($row->open_from).' - '.displayDate($row->open_to).')' }}</p>--}}
    {{--                    @endforeach--}}
    {{--                @endif--}}
                    @php($found = false)
                    @if($pcByDecree && $pcByDecree->count())
                        @foreach($pcByDecree as $row)
                            @if($row->id != $item->id)
                                @php($found = true)
                                <a class="d-block" href="{{ route('admin.consultations.public_consultations.edit', $row) }}" target="_blank">
                                    <i class="fas fa-link mr-1 fs-6"></i>{{ $row->title.' ('.displayDate($row->open_from).' - '.displayDate($row->open_to).')' }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                    @if(!$found)
                        ---
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="row">
{{--        <div class="col-md-3">--}}
{{--            <div class="form-group">--}}
{{--                <label class="col-sm-12 control-label" for="monitorstat">{{ __('validation.attributes.monitorstat') }}</label>--}}
{{--                <input type="text" id="monitorstat" name="monitorstat"--}}
{{--                       class="form-control form-control-sm @error('monitorstat'){{ 'is-invalid' }}@enderror"--}}
{{--                       value="{{ old('monitorstat', ($item->id ? $item->monitorstat : '')) }}">--}}
{{--                @error('monitorstat')--}}
{{--                <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                @enderror--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="active">{{ __('custom.status') }}</label>
                <select id="active" name="active" class="form-control form-control-sm select2 @error('active'){{ 'is-invalid' }}@enderror">
                    <option value="0" @if(!old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.draft') }}</option>
                    <option value="1" @if(old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.public_f') }}</option>
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
@if($isAdmin)
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                let levelsLabel = <?php echo json_encode(\App\Enums\InstitutionCategoryLevelEnum::keyToLabel());?>

                $('#institution_id').on('change', function (){
                    let selectedOpt = $(this).find('option:selected');
                    let foa  = selectedOpt.data('foa');
                    let level  = parseInt(selectedOpt.data('level'));

                    $('#operational_program_row_id').data('institution', $('#institution_id').val());
                    $('#legislative_program_row_id').data('institution', $('#institution_id').val());


                    $('#nomenclature_level').val(level);
                    $('#levelLabel').html(levelsLabel[level]);

                    $('#act_type_id').val('').trigger('change');
                    $('#act_type_id option').each(function (){
                        if(parseInt($(this).data('level')) == level) {
                            $(this).attr('disabled', false);
                        } else{
                            $(this).attr('disabled', true);
                        }
                    });

                    $('#field_of_actions_id').val('').trigger('change');
                    $('#field_of_actions_id option').each(function (){
                        if(foa.indexOf(parseInt($(this).attr('value'))) != -1) {
                            $(this).attr('disabled', false);
                        } else{
                            $(this).attr('disabled', true);
                        }
                    });
                });
            });
        </script>
    @endpush
@endif
