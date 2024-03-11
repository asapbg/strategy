@php($storeRoute = route($storeRouteName))
{{--<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">--}}
    <form action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

        <div class="row">
            <div class="card card-secondary p-0 mt-4">
                <div class="card-body">
                    <h3>{{ trans_choice('custom.main_information', 1) }}</h3>
                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['field' => 'title','required' => true])
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['field' => 'description', 'required' => true])
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="strategic_document_level_id">{{ trans_choice('custom.strategic_document_level', 1) }}
                                    <span class="required">*</span></label>
                                <div class="col-12">
                                    <select id="strategic_document_level_id" name="strategic_document_level_id"
                                            class="form-control form-control-sm select2 @error('strategic_document_level_id'){{ 'is-invalid' }}@enderror">
                                            @if(isset($strategicDocumentLevels) && sizeof($strategicDocumentLevels))
                                                @foreach($strategicDocumentLevels as $row)
                                                    <option value="{{ $row['value'] }}"
                                                            @if(old('strategic_document_level_id', ($item->id ? $item->strategic_document_level_id : '')) == $row['value']) selected @endif
                                                            data-id="{{ $row['value'] }}">{{ $row['name'] }}</option>
                                                @endforeach
                                            @endif

                                    </select>
                                    @error('strategic_document_level_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" id="policy_area_div">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="policy_area_id">{{ trans_choice('custom.policy_area', 1) }}<span
                                        class="required">*</span></label>
                                <div class="col-12">
                                    <select id="policy_area_id" name="policy_area_id"
                                            class="form-control form-control-sm select2 @error('policy_area_id'){{ 'is-invalid' }}@enderror">
                                            <option value="" @if(old('policy_area_id', ($item->id ? $item->policy_area_id : '')) == '') selected @endif>---
                                            </option>
                                        @if(isset($policyAreas) && $policyAreas->count())
                                            @foreach($policyAreas as $row)
                                                <option value="{{ $row->id }}"
                                                        @if(old('policy_area_id', ($item->id ? $item->policy_area_id : 0)) == $row->id) selected
                                                        @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('policy_area_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="strategic_document_type_id">{{ trans_choice('custom.strategic_document_type', 1) }}
                                    <span class="required">*</span></label>
                                <div class="col-12">
                                    <select id="strategic_document_type_id" name="strategic_document_type_id"
                                            class="form-control form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">
                                        @if(!$item->id)
                                            <option value=""
                                                    @if(old('strategic_document_type_id', '') == '') selected @endif>
                                                ---
                                            </option>
                                        @endif
                                        @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                            @foreach($strategicDocumentTypes as $row)
                                                @if($row->active || ($item && $item->strategic_document_type_id == $row->id))
                                                <option value="{{ $row->id }}"
                                                        @if(old('strategic_document_type_id', ($item->id ? $item->strategic_document_type_id : 0)) == $row->id) selected
                                                        @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('strategic_document_type_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="ekatte_area_div_id">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="ekatte_area_id">{{ trans_choice('custom.areas', 1) }}<span
                                        class="required"></span></label>
                                <div class="col-12">
                                    <select id="ekatte_area_id" name="ekatte_area_id"
                                            class="form-control form-control-sm select2 @error('ekatte_area_id'){{ 'is-invalid' }}@enderror">
                                            <option value="" @if(old('ekatte_area_id', ($item->id ? $item->policy_area_id : '')) == '') selected @endif>
                                                ---
                                            </option>

                                        @foreach ($ekateAreas as $ekateArea)
                                            <option value="{{ $ekateArea->id }}"
                                                    @if(old('ekatte_area_id', ($item->id ? $item->policy_area_id : 0)) == $ekateArea->id) selected @endif data-id="{{ $ekateArea->id }}">{{ $ekateArea->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="ekatte_municipality_div_id">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="ekatte_municipality_id">{{ trans_choice('custom.municipalities', 1) }}<span
                                        class="required"></span></label>
                                <div class="col-12">
                                    @if(isset($ekateMunicipalities))
                                        <select id="ekatte_municipality_id" name="ekatte_municipality_id"
                                                class="form-control form-control-sm select2 @error('ekatte_municipality_id'){{ 'is-invalid' }}@enderror">
                                                <option value="" @if(old('ekatte_municipality_id', ($item->id ? $item->policy_area_id : '')) == '') selected @endif>---
                                                </option>
                                            @foreach ($ekateMunicipalities as $ekateMunicipality)
                                                <option value="{{ $ekateMunicipality->id }}"
                                                        @if(old('ekatte_municipality_id', ($item->id ? $item->policy_area_id : 0)) == $ekateMunicipality->id) selected
                                                        @endif data-id="{{ $ekateMunicipality->id }}">{{ $ekateMunicipality->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="accept_act_institution_type_id">{{ trans_choice('custom.authority_accepting_strategic', 1) }}
                                    <span class="required">*</span></label>
                                <div class="col-12">
                                    <select id="accept_act_institution_type_id" name="accept_act_institution_type_id"
                                            class="form-control form-control-sm select2 @error('accept_act_institution_type_id'){{ 'is-invalid' }}@enderror">
                                            <option value=""
                                                    @if(old('accept_act_institution_type_id', $item->id ? $item->accept_act_institution_type_id : '') == '') selected @endif>---
                                            </option>
                                        @if(isset($authoritiesAcceptingStrategic) && $authoritiesAcceptingStrategic->count())
                                            @foreach($authoritiesAcceptingStrategic as $row)
                                                <option value="{{ $row->id }}"
                                                        @if(old('accept_act_institution_type_id', ($item->id ? $item->accept_act_institution_type_id : 0)) == $row->id) selected @endif
                                                        data-id="{{ $row->id }}"
                                                        data-level="{{ $row->nomenclature_level_id }}"
                                                >{{ $row->name }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('accept_act_institution_type_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="active">{{ trans_choice('custom.public_consultations', 1) }}</label>
                                <div class="col-12">
                                    <select id="public_consultation_id" name="public_consultation_id" data-types2ajax="pc" data-urls2="{{ route('admin.select2.ajax', 'pc') }}" data-placeholders2="{{ __('custom.search_pc_record_js_placeholder') }}"
                                            class="form-control form-control-sm select2-autocomplete-ajax @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                                        @if($item->publicConsultation)
                                            <option value="{{ $item->publicConsultation->id }}"
                                                    {{ old('public_consultation_id', ($item->publicConsultation? $item->publicConsultation->id : null)) == $item->id ? 'selected' : '' }}
                                                    data-id="{{ $item->publicConsultation->id }}"> {{ $item->publicConsultation->reg_num }} </option>
                                        @endif
                                    </select>
                                    @error('public_consultation_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-none" id="pris-act">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">
                                        {{ trans_choice('custom.pris_categories', 1) }}
                                    </label>

                                    <span class="text-danger" id="connect-doc-error"></span>
                                    <select id="legal_act_type_filter" name="legal_act_type_filter" class="form-control form-control-sm select2 @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror">
                                        <option value="all">--</option>
                                        @if(isset($legalActTypes) && $legalActTypes->count())
                                            @foreach($legalActTypes as $row)
                                                <option value="{{ $row->id }}" @if($row->id == $item->pris?->legal_act_type_id) selected @endif>{{ $row->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('legal_act_type_filter')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="pris_act_id">Акт за приемане от раздел „Актове на МС“</label>
                                    <select id="pris_act_id" name="pris_act_id" data-types2ajax="pris_doc" data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}"
                                            class="form-control form-control-sm select2-autocomplete-ajax @error('pris_act_id'){{ 'is-invalid' }}@enderror">
                                        @if($item->pris)
                                        <option value="{{ $item->pris?->id }}"
                                                {{ old('pris_act_id', ($item->pris ? $item->pris?->id : null)) == $item->id ? 'selected' : '' }}
                                                data-id="{{ $item->pris?->id }}"> {{ $item->pris?->displayName }} </option>
                                        @endif

                                    </select>
                                    @error('pris_act_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 act-custom-fields d-none">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="strategic_act_link">{{ __('validation.attributes.strategic_act_link') }}</label>
                                <div class="col-12">
                                    <input type="text" id="strategic_act_link" name="strategic_act_link"
                                           class="form-control form-control-sm @error('strategic_act_link'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('strategic_act_link', $item->id ? $item->strategic_act_link : '') }}">
                                    @error('strategic_act_link')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <!-- Document date -->
                        <div class="col-md-6 act-custom-fields d-none">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="document_date">{{ __('custom.document_act') }}</label>
                                <div class="col-12">
                                    <input type="text" id="document_date" name="document_date"
                                           class="form-control form-control-sm datepicker @error('document_date'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('document_date', ($item->id ? $item->document_date : '')) }}">
                                    @error('document_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="active">{{ trans_choice('custom.parent_strategic_document', 1) }}</label>
                                    <div class="col-12">
                                        <select id="parent_document_id" name="parent_document_id" data-types2ajax="sd_parent_documents" data-urls2="{{ route('admin.select2.ajax', 'sd_parent_documents') }}"
                                                @if($item->id) data-documentid="{{ $item->id }}" @endif class="form-control form-control-sm select2-autocomplete-ajax @error('parent_document_id'){{ 'is-invalid' }}@enderror">
                                            <option value=""
                                                    @if(old('parent_document_id', '') == '') selected @endif>
                                                ---
                                            </option>
                                            @if($item->id && $item->parentDocument)
                                                <option value="{{ $item->parentDocument->id }}"
                                                    {{ old('pris_act_id', $item->parentDocument->id) == $item->parentDocument->id ? 'selected' : '' }}>
                                                    {{ $item->parentDocument->title }}
                                                </option>

                                            @endif

                                        </select>
                                        @error('parent_document_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="active">{{ __('custom.status') }}</label>
                                    <div class="col-12">
                                        <select id="active" name="active"
                                                class="form-control form-control-sm select2 @error('active'){{ 'is-invalid' }}@enderror">
                                            <option value="0"
                                                    @if(!old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.inactive_m') }}</option>
                                            <option value="1"
                                                    @if(old('active', $item->id ? $item->active : 0 )) selected @endif>{{ __('custom.active_m') }}</option>
                                        </select>
                                        @error('active')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="strategic_act_link">{{ __('validation.attributes.link_to_monitorstat') }}</label>
                                    <div class="col-12">
                                        <input type="text" name="link_to_monitorstat"
                                               class="form-control form-control-sm @error('link_to_monitorstat'){{ 'is-invalid' }}@enderror"
                                               value="{{ old('link_to_monitorstat', $item->id ? $item->link_to_monitorstat : '') }}">
                                        @error('link_to_monitorstat')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="document_date_accepted">
                                        <i class="fas fa-info-circle text-info mr-2" data-toggle="tooltip" title="Когато документът е свързан с Акт за приемане от раздел „Актове на МС“, дата на приемане се взима автоматично от акта. Когато Дата на приемане остане празно, автоматично се взима днешна дата."></i>{{ __('custom.date_accepted') }}
                                        <span class="required">*</span></label>
                                    <div class="col-12">
                                        <input type="text" id="document_date_accepted" name="document_date_accepted"
                                               class="form-control form-control-sm datepicker @error('document_date_accepted'){{ 'is-invalid' }}@enderror"
                                               value="{{ old('document_date_accepted', ($item->id ? ($item->document_date_accepted ? \Carbon\Carbon::parse($item->document_date_accepted)->format('d.m.Y') : '') : displayDate(\Carbon\Carbon::now()))) }}">
                                        @error('document_date_accepted')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="document_date_pris">{{ __('custom.date_expiring') }}
                                        <span class="required">*</span></label>
                                    <div class="col-12">
                                        <input type="text" id="document_date_expiring" name="document_date_expiring"
                                               class="form-control form-control-sm datepicker @error('document_date_expiring'){{ 'is-invalid' }}@enderror"
                                               value="{{ old('document_date_expiring', ($item->id ? ($item->document_date_expiring ? \Carbon\Carbon::parse($item->document_date_expiring)->format('d.m.Y') : '') : '')) }}">
                                        @error('document_date_expiring')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="document_date_pris">{{ __('custom.date_indefinite') }}
                                        <span class="required">*</span></label>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input type="hidden" name="date_expiring_indefinite" value="0">
                                            <input type="checkbox" id="date_expiring_indefinite"
                                                   name="date_expiring_indefinite"
                                                   class="form-check-input"
                                                   value="1" {{ $item->document_date_expiring === null ? 'checked' : '' }}>
                                            <label class="form-check-label" for="date_valid_indefinite_main">
                                                {{ __('custom.date_expring_indefinite') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group row">
                <div class="col-md-6 col-md-offset-3">
                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                    <button id="stayButton" type="submit" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>

                    @if($item->active)
                        <a href="{{ route('admin.strategic_documents.unpublish', ['id' => $item->id, 'stay' => false]) }}"
                           class="btn btn-danger">{{ __('custom.unpublish_make') }}</a>
                    @else
                        <a href="{{ route('admin.strategic_documents.publish', ['id' => $item->id, 'stay' => false]) }}"
                           class="btn btn-success">{{ __('custom.publish') }}</a>
                    @endif

                    <a href="{{ route('admin.strategic_documents.index') }}"
                       class="btn btn-primary">{{ __('custom.cancel') }}</a>
                </div>
            </div>
        </div>
    </form>

@push('styles')
    <style>
        .tab-pane {
            padding: 0;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            const docId = parseInt('<?php echo $item->id ?? 0; ?>');
            const centralLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>';
            const areaLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::AREA->value; ?>';
            const municipalityLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value; ?>';

            const documentId = {!! json_encode(isset($item) ? (int)$item->id : null) !!};
            $("#stayButton").click(function () {
                $("#stay").val("true");
            });
            let manualChangeConsultationId = true;
            let manualPrisActId = true;
            const prisAct = $('#pris_act_id');
            const dateValidAtMain = $('#valid_at_main');
            const dateIndefiniteCheckboxMain = $('#date_valid_indefinite_main');
            const dateExpiring = $('#document_date_expiring');
            const dateExpiringCheckbox = $('#date_expiring_indefinite');

            const dateValidAtFiles = $('#valid_at_files');
            const dateInfiniteFilesCheckbox = $('#date_valid_indefinite_files');
            const dateValidAtFileEdit = $('[id^="valid_at_files_edit"]');
            const dateInfiniteEditCheckbox = $('[id^="date_valid_indefinite_files_edit"]');
            handleDateCheckbox(dateValidAtMain, dateIndefiniteCheckboxMain);
            handleDateCheckbox(dateExpiring, dateExpiringCheckbox);
            handleDateCheckbox(dateValidAtFiles, dateInfiniteFilesCheckbox);

            function clearStartDate(init = false){
                if(parseInt(prisAct.val()) > 0){
                    $('#document_date_accepted').prop('disabled', true);
                    if(!init){
                        //TODO get act date by data attribute from select2
                        $('#document_date_accepted').val('');
                    }
                } else{
                    $('#document_date_accepted').prop('disabled', false);
                }
            }

            prisAct.on('change', function (){
                clearStartDate();
            });

            dateValidAtFileEdit.on('change', function () {
                const checkboxId = '#date_valid_indefinite_files_edit_' + $(this).data('id');
                const checkbox = $(checkboxId);

                if (checkbox.is(':checked')) {
                    checkbox.prop('checked', $(this).val() === '').trigger('change');
                }
            });

            dateInfiniteEditCheckbox.on('change', function () {
                const dateInputId = '#valid_at_files_edit_' + $(this).data('id');
                const dateInput = $(dateInputId);
                if ($(this).is(':checked')) {
                    dateInput.prop('disabled', true);
                    if (dateInput.val() !== '') {
                        dateInput.val('').trigger('change');
                    }
                } else {
                    dateInput.prop('disabled', false);
                }
            });

            function handleDateCheckbox(dateInput, checkbox) {
                dateInput.on('change', function () {
                    if ($(checkbox).is(':checked')) {
                        checkbox.prop('checked', $(this).val() === '').trigger('change');
                    }
                });
                checkbox.on('change', function () {
                    if ($(this).is(':checked')) {
                        dateInput.prop('disabled', true);
                        if (dateInput.val() !== '') {
                            dateInput.val('').trigger('change');
                        }
                    } else {
                        dateInput.prop('disabled', false);
                    }
                });
            }

            $('#accept_act_institution_type_id').on('change', function () {
                let selectedValue = $(this).val();
                if (selectedValue == parseInt('<?php echo \App\Models\AuthorityAcceptingStrategic::COUNCIL_MINISTERS; ?>')) {
                    $('#strategic_act_link').val('');
                    $('#document_date').val('');
                } else {
                    $('#document_date_pris').val('');
                    $('#pris_act_id').val('').trigger('change');
                }
            });

            const ekatteAreaDiv = $('#ekatte_area_div_id');
            const ekatteMunicipalityDiv = $('#ekatte_municipality_div_id');
            const policyAreaDiv = $('#policy_area_div');
            const strategicDocumentLevel = $('#strategic_document_level_id');

            strategicDocumentLevel.on('change', function () {
                let selectedValue = $(this).val();
                handleVisibility(selectedValue);
                acceptActInstitutionByLevel();
            });


            function acceptActInstitutionByLevel(init = false){
                let selectedLevel = strategicDocumentLevel.val();
                let acceptActInstitution = $('#accept_act_institution_type_id');
                //$('#accept_act_institution_type_id').val('').change();
                $('#accept_act_institution_type_id option').each(function (i){
                    if(typeof $(this).data('level') == 'undefined' || parseInt($(this).data('level')) == selectedLevel){
                        $(this).removeAttr('disabled');
                    } else{
                        $('#accept_act_institution_type_id').trigger('change')
                        $(this).attr('disabled', 'disabled');
                    }
                });
                if(!init) {
                    acceptActInstitution.val('');
                }
            }

            function handleVisibility(strategicDocumentLevel) {
                if (strategicDocumentLevel == areaLevel) {
                    ekatteMunicipalityDiv.hide();
                    policyAreaDiv.hide();
                    ekatteAreaDiv.show();
                } else if (strategicDocumentLevel == municipalityLevel) {
                    ekatteAreaDiv.hide();
                    policyAreaDiv.hide();
                    ekatteMunicipalityDiv.show();
                } else {
                    ekatteMunicipalityDiv.hide();
                    ekatteAreaDiv.hide();
                    policyAreaDiv.show();
                }
            }

            handleVisibility(strategicDocumentLevel.val());
            acceptActInstitutionByLevel(true);
            clearStartDate(true);

            if($('#date_expiring_indefinite').is(':checked')){
                $('#document_date_expiring').val('');
                $('#document_date_expiring').prop('disabled', true);
            } else{
                $('#document_date_expiring').prop('disabled', false);
            }
        });
    </script>
@endpush

