<form class="row" id="new_sd_child_form">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-12 text-danger" id="main_error"></div>
    </div>
    {{--    @csrf--}}
    <input type="hidden" name="sd" value="{{ $sd->id }}">
    <input type="hidden" id="strategic_document_level_id_new" name="strategic_document_level_id" value="{{ $sd->strategic_document_level_id }}">
{{--    <input type="hidden" id="accept_act_institution_type_id" name="accept_act_institution_type_id" value="{{ $sd->accept_act_institution_type_id }}">--}}
    @if(isset($doc) && $doc)
        <input type="hidden" name="doc" value="{{ $doc->id }}">
    @endif
    @php($defaultLang = config('app.default_lang'))
    <div class="row mb-4">
        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'title', 'required' => true])
    </div>
    <div class="row mb-4">
        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'description', 'required' => false])
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label">{{ trans_choice('custom.strategic_document_level', 1) }}</label>
                <div class="col-12">
                    {{ __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($sd->strategic_document_level_id)) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="strategic_document_type_id">{{ trans_choice('custom.strategic_document_type', 1) }}
                    <span class="required">*</span></label>
                <div class="col-12">
                    <select id="strategic_document_type_id" name="strategic_document_type_id"
                            class="form-control form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">
                        <option value="" @if(old('strategic_document_type_id', '') == '') selected @endif>
                            ---
                        </option>
                        @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                            @foreach($strategicDocumentTypes as $row)
                                @if($row->active)
                                    <option value="{{ $row->id }}"
                                            @if(old('strategic_document_type_id', 0) == $row->id) selected @endif
                                            data-id="{{ $row->id }}"
                                    >{{ $row->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @error('strategic_document_type_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_strategic_document_type_id"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="accept_act_institution_type_id">{{ trans_choice('custom.authority_accepting_strategic', 1) }}
                    <span class="required">*</span></label>
                <div class="col-12">
                    <select name="accept_act_institution_type_id" id="accept_act_institution_type_id_new"
                            class="form-control form-control-sm select2 @error('accept_act_institution_type_id'){{ 'is-invalid' }}@enderror">
                        <option value=""
                                @if(old('accept_act_institution_type_id', $sd->id ? $sd->accept_act_institution_type_id : '') == '') selected @endif>---
                        </option>
                        @if(isset($authoritiesAcceptingStrategic) && $authoritiesAcceptingStrategic->count())
                            @foreach($authoritiesAcceptingStrategic as $row)
                                <option value="{{ $row->id }}"
                                        @if(old('accept_act_institution_type_id', ($sd->id ? $sd->accept_act_institution_type_id : 0)) == $row->id) selected @endif
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
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label class="col-sm-12 control-label">{{ trans_choice('custom.authority_accepting_strategic', 1) }}</label>--}}
{{--                <div class="col-12">--}}
{{--                    {{ $sd->acceptActInstitution ? $sd->acceptActInstitution->name : 'Основният документ няма избран \''.trans_choice('custom.authority_accepting_strategic', 1).'\''}}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="active">{{ trans_choice('custom.public_consultations', 1) }}</label>
                <div class="col-12">
                    <select id="public_consultation_id" name="public_consultation_id" data-types2ajax="pc" data-urls2="{{ route('admin.select2.ajax', 'pc') }}" data-placeholders2="{{ __('custom.search_pc_record_js_placeholder') }}"
                            class="form-control form-control-sm select2-autocomplete-ajax @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                    </select>
                    @error('public_consultation_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_public_consultation_id"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 d-none" id="prisSection_new">
        <div class="col-md-12" id="pris-act">
            <div class="col-12">
                <div class="form-group">
                    <label class="col-sm-12 control-label">
                        {{ trans_choice('custom.category', 1) }}
                    </label>

                    <span class="text-danger" id="connect-doc-error"></span>
                    <select id="legal_act_type_filter_new" name="legal_act_type_filter" class="form-control form-control-sm select2 @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror">
                        <option value="" @if(old('legal_act_type_filter', '') == '') selected @endif>--</option>
                        @if(isset($legalActTypes) && $legalActTypes->count())
                            @foreach($legalActTypes as $row)
                                <option value="{{ $row->id }}" @if($row->id == old('legal_act_type_filter', '')) selected @endif>{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('legal_act_type_filter')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_legal_act_type_filter"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-12 control-label"
                           for="pris_act_id">Акт за приемане от раздел „Актове на МС“</label>
                    <select id="pris_act_id_new" name="pris_act_id" data-types2ajax="pris_doc" data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}"
                            class="form-control form-control-sm select2-autocomplete-ajax @error('pris_act_id'){{ 'is-invalid' }}@enderror">
                    </select>
                    @error('pris_act_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_pris_act_id"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="document_date_accepted">
                    <i class="fas fa-info-circle text-info mr-2" data-toggle="tooltip" title="Когато документът е свързан с Акт за приемане от раздел „Актове на МС“, дата на приемане се взима автоматично от акта. Когато Дата на приемане остане празно, автоматично се взима днешна дата."></i>
                    {{ __('custom.date_accepted') }}
                    <span class="required">*</span></label>
                <div class="col-12">
                    <input type="text" id="document_date_accepted_new" name="document_date_accepted"
                           class="form-control form-control-sm datepicker @error('document_date_accepted'){{ 'is-invalid' }}@enderror"
                           value="{{ old('document_date_accepted', '') }}">
                    @error('document_date_accepted')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_document_date_accepted"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="document_date_pris">{{ __('custom.date_expiring') }}
                    <span class="required">*</span></label>
                <div class="col-12">
                    <input type="text" id="document_date_expiring_new" name="document_date_expiring"
                           class="form-control form-control-sm datepicker @error('document_date_expiring'){{ 'is-invalid' }}@enderror"
                           value="{{ old('document_date_expiring', '') }}">
                    @error('document_date_expiring')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_document_date_expiring"></div>
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
                        <input type="checkbox" id="date_expiring_indefinite_new"
                               name="date_expiring_indefinite"
                               class="form-check-input"
                               value="1" @if(is_null(old('document_date_expiring'))) checked @endif>
                        <label class="form-check-label" for="date_valid_indefinite_main">
                            {{ __('custom.date_expring_indefinite') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       for="strategic_act_link">{{ __('validation.attributes.link_to_monitorstat') }}</label>
                <div class="col-12">
                    <input type="text" name="link_to_monitorstat"
                           class="form-control form-control-sm @error('link_to_monitorstat'){{ 'is-invalid' }}@enderror"
                           value="{{ old('link_to_monitorstat', '') }}">
                    @error('link_to_monitorstat')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_link_to_monitorstat"></div>
                </div>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        const centralLevelNew = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>';
        const counsilMinistersNew = '<?php echo \App\Models\AuthorityAcceptingStrategic::COUNCIL_MINISTERS; ?>';

        $('#new_sd_child_form .summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['view', ['fullscreen']],
                ['insert', ['link']]
            ],
            dialogsInBody: true,
            lang: typeof GlobalLang != 'undefined' ? GlobalLang + '-' + GlobalLang.toUpperCase() : 'en-US',
        });

        if($('.datepicker').length) {
            $('.datepicker').datepicker({
                language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
                format: 'dd.mm.yyyy',
                todayHighlight: true,
                orientation: "auto",
                autoclose: true,
                weekStart: 1
            });
        }

        if($('.select2').length) {
            $('.select2').select2(select2Options);
        }

        if($('.select2-autocomplete-ajax').length) {
            $('.select2-autocomplete-ajax').each(function (){
                MyS2Ajax($(this), $(this).data('placeholders2'), $(this).data('urls2'));
            });
        }

        function controlDateExpirationNew(){
            if($('#date_expiring_indefinite_new').is(':checked')){
                $('#document_date_expiring_new').val('');
                $('#document_date_expiring_new').prop('disabled', true);
            } else{
                $('#document_date_expiring_new').prop('disabled', false);
            }
        }

        function controlPrisSectionNew(){
            if(parseInt($('#strategic_document_level_id_new').val()) == parseInt(centralLevelNew)
                && (parseInt($('#accept_act_institution_type_id_new').val()) == counsilMinistersNew)
            ){
                $('#prisSection_new').removeClass('d-none');
            } else{
                $('#legal_act_type_filter_new').val('');
                $('#pris_act_id_new option').remove();
                $('#prisSection_new').addClass('d-none');
            }
        }

        $('#date_expiring_indefinite_new').on('change', function (){
            controlDateExpirationNew();
        });

        $('#accept_act_institution_type_id_new').on('change', function (){
            controlPrisSectionNew();
        });

        function clearStartDate(init = false){
            if(parseInt($('#pris_act_id_new').val()) > 0){
                $('#document_date_accepted_new').prop('disabled', true);
                if(!init){
                    //TODO get act date by data attribute from select2
                    $('#document_date_accepted_new').val('');
                }
            } else{
                $('#document_date_accepted_new').prop('disabled', false);
            }
        }

        $('#pris_act_id_new').on('change', function (){
            clearStartDate();
        });

        controlDateExpirationNew();
        controlPrisSectionNew();
    });
</script>

