@php($storeRoute = route($storeRouteName))
<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <form action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

            <h3>Основна информация</h3>
            <div class="row">
                @include('admin.partial.edit_field_translate', [
                 'field' => 'title',
                 'required' => true,
                ])
            </div>

            <div class="row">
                @include('admin.partial.edit_field_translate', ['field' => 'description', 'required' => true])
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-sm-12 control-label"
                               for="strategic_document_level_id">{{ trans_choice('custom.strategic_document_level', 1) }}
                            <span class="required">*</span></label>
                        <div class="col-12">
                            <select id="strategic_document_level_id" name="strategic_document_level_id"
                                    class="form-control form-control-sm select2 @error('strategic_document_level_id'){{ 'is-invalid' }}@enderror">
                                @if(!$item->id)
                                    <option value="" @if(old('strategic_document_level_id', '') == '') selected @endif>
                                        ---
                                    </option>
                                @endif
                                @if(isset($strategicDocumentLevels) && $strategicDocumentLevels->count())
                                    @foreach($strategicDocumentLevels as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('strategic_document_level_id', ($item->id ? $item->strategic_document_level_id : '')) == $row->id) selected
                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('strategic_document_level_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label"
                               for="policy_area_id">{{ trans_choice('custom.policy_area', 1) }}<span
                                class="required">*</span></label>
                        <div class="col-12">
                            <select id="policy_area_id" name="policy_area_id"
                                    class="form-control form-control-sm select2 @error('policy_area_id'){{ 'is-invalid' }}@enderror">
                                @if(!$item->id)
                                    <option value="" @if(old('policy_area_id', '') == '') selected @endif>---</option>
                                @endif
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
                                    <option value="" @if(old('strategic_document_type_id', '') == '') selected @endif>
                                        ---
                                    </option>
                                @endif
                                @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                    @foreach($strategicDocumentTypes as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('strategic_document_type_id', ($item->id ? $item->strategic_document_type_id : 0)) == $row->id) selected
                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-sm-12 control-label"
                               for="strategic_act_type_id">{{ trans_choice('custom.strategic_act_type', 1) }}<span
                                class="required">*</span></label>
                        <div class="col-12">
                            <select id="strategic_act_type_id" name="strategic_act_type_id"
                                    class="form-control form-control-sm select2 @error('strategic_act_type_id'){{ 'is-invalid' }}@enderror">
                                @if(!$item->id)
                                    <option value="" @if(old('strategic_act_type_id', '') == '') selected @endif>---
                                    </option>
                                @endif
                                @if(isset($strategicActTypes) && $strategicActTypes->count())
                                    @foreach($strategicActTypes as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('strategic_act_type_id', ($item->id ? $item->strategic_act_type_id : 0)) == $row->id) selected
                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('strategic_act_type_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label"
                               for="accept_act_institution_type_id"><br>{{ trans_choice('custom.authority_accepting_strategic', 1) }}
                            <span class="required">*</span></label>
                        <div class="col-12">
                            <select id="accept_act_institution_type_id" name="accept_act_institution_type_id"
                                    class="form-control form-control-sm select2 @error('accept_act_institution_type_id'){{ 'is-invalid' }}@enderror">
                                @if(!$item->id)
                                    <option value=""
                                            @if(old('accept_act_institution_type_id', '') == '') selected @endif>---
                                    </option>
                                @endif
                                @if(isset($authoritiesAcceptingStrategic) && $authoritiesAcceptingStrategic->count())
                                    @foreach($authoritiesAcceptingStrategic as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('accept_act_institution_type_id', ($item->id ? $item->accept_act_institution_type_id : 0)) == $row->id) selected
                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('accept_act_institution_type_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- test -->

            <div class="row">
                <div class="col-md-12 d-none" id="pris-act">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">
                                Категории:
                            </label>
                            <span class="text-danger" id="connect-doc-error"></span>
                            <select id="the_legal_act_type_filter" name="legal_act_type_filter"
                                    class="form-control form-control-sm select2 @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror">
                                <option value="">--</option>
                                @if(isset($legalActTypes) && $legalActTypes->count())
                                    @foreach($legalActTypes as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('legal_act_type_filter', '') == $row->id) selected @endif>{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('legal_act_type_filter')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-6 control-label"
                                   for="pris_act_id">{{ trans_choice('custom.acts_pris', 1) }}</label>
                            <select id="pris_act_id" name="pris_act_id"
                                    class="form-control form-control-sm select2 @error('pris_act_id'){{ 'is-invalid' }}@enderror">
                                <!--
                        <option value="{{ $item->pris?->id }}"
                                {{ old('pris_act_id', ($item->pris ? $item->pris?->id : null)) == $item->id ? 'selected' : '' }}
                                data-id="{{ $item->pris?->id }}"> {{ $item->pris?->actType?->name . ' N' . $item->pris?->doc_num . ' ' . $item->pris?->doc_date }} </option>
                                -->
                                @foreach ($prisActs as $prisAct)
                                    <option value="{{ $prisAct->id }}">
                                        {{ $prisAct->actType->name . ' N' . $prisAct->doc_num . ' ' . $prisAct->doc_date }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pris_act_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- end testing -->

            <div class="col-md-3 act-custom-fields d-none">
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
            <div class="col-md-3 act-custom-fields d-none">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="document_date">{{ __('custom.document_act') }}</label>
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
                       for="active">{{ trans_choice('custom.public_consultations', 1) }}</label>
                <div class="col-12">
                    <select id="public_consultation_id" name="public_consultation_id"
                            class="form-control form-control-sm select2 @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                        @if(!$item->id)
                            <option value="" @if(old('public_consultation_id', '') == '') selected @endif>---</option>
                        @endif
                        @if(isset($consultations) && $consultations->count())
                            @foreach($consultations as $consultation)
                                <option value="{{ $consultation->id }}"
                                        @if(old('public_consultation_id', ($item->id ? $item->public_consultation_id : 0)) == $consultation->id) selected
                                        @endif
                                        data-id="{{ $consultation->id }}"
                                >{{ $consultation->reg_num }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('public_consultation_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-12 control-label"
                           for="active">{{ trans_choice('custom.strategic_documents', 1) }}</label>
                    <div class="col-12">
                        <div class="col-12">
                            <select id="parent_document_id" name="parent_document_id"
                                    class="form-control form-control-sm select2 @error('parent_document_id'){{ 'is-invalid' }}@enderror">
                                @if($item->parent_document_id === null || $item->parent_document_id === '')
                                    <option value="" @if(old('parent_document_id', '') == '') selected @endif>---
                                    </option>
                                @endif
                                @if(isset($strategicDocuments) && $strategicDocuments->count())
                                    @foreach($strategicDocuments as $strategicDocument)
                                        @if ($strategicDocument->id === $item->id)
                                            @continue
                                        @endif
                                        <option value="{{ $strategicDocument->id }}"
                                                @if(old('parent_document_id', ($item->parent_document_id ? $item->parent_document_id : 0)) == $strategicDocument->id) selected
                                                @endif
                                                data-id="{{ $strategicDocument->id }}"
                                        >{{ $strategicDocument->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('parent_document_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @error('parent_document_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="document_date_accepted">{{ __('custom.date_accepted') }}
                        <span class="required">*</span></label>
                    <div class="col-12">
                        <input type="text" id="document_date_accepted" name="document_date_accepted"
                               class="form-control form-control-sm datepicker @error('date_accepted'){{ 'is-invalid' }}@enderror"
                               value="{{ old('document_date_accepted', ($item->id ? $item->document_date : '')) }}">
                        @error('document_date_accepted')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="document_date_pris">{{ __('custom.date_expiring') }}
                        <span class="required">*</span></label>
                    <div class="col-12">
                        <input type="text" id="document_date_expiring" name="document_date_expiring"
                               class="form-control form-control-sm datepicker @error('document_date_expiring'){{ 'is-invalid' }}@enderror"
                               value="{{ old('document_date_expiring', ($item->id ? $item->document_date : '')) }}">
                        @error('document_date_expiring')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-12 control-label" for="document_date_pris">{{ __('custom.date_indefinite') }}
                        <span class="required">*</span></label>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="date_expiring_indefinite" value="0">
                            <input type="checkbox" id="date_expiring_indefinite" name="date_expiring_indefinite"
                                   class="form-check-input"
                                   value="1" {{ $item->expiration_date === null ? 'checked' : '' }}>
                            <label class="form-check-label" for="unlimited_date_expiring">
                                {{ __('custom.date_expring_indefinite') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Files -->
        <h3>Основен файл</h3>

        <div class="row">
            @include('admin.partial.edit_field_translate', [
                'item' => null,
                'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsPropertiesMain(),
                'field' => 'display_name_main',
                'required' => true,
                'value' => optional($mainFile)->display_name ?? ''
            ])
            <div class="col-md-3">
                <div class="form-group form-group-sm">
                    <label for="valid_at" class="col-sm-12 control-label">{{ __('custom.valid_at') }} <span
                            class="required">*</span> </label>
                    <div class="col-12">
                        <input value="{{ old('valid_at', optional($mainFile)->valid_at) }}"
                               class="form-control form-control-sm datepicker @error('valid_at_main') is-invalid @enderror"
                               type="text" name="valid_at_main">
                        @error('valid_at_main')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-group-sm">
                    <label class="col-sm-12 control-label"
                           for="strategic_document_type">{{ trans_choice('custom.strategic_document_type', 1) }}<span
                            class="required">*</span></label>
                    <div class="col-12">
                        <select id="strategic_document_type" name="strategic_document_type_id"
                                class="form-control form-control-sm select2 @error('strategic_document_type'){{ 'is-invalid' }}@enderror">
                            <option value="" @if(old('strategic_document_type_id', '') == '') selected @endif>---
                            </option>
                            @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                @foreach($strategicDocumentTypes as $row)
                                    <option value="{{ $row->id }}"
                                            @if(old('strategic_document_type_id', optional($mainFile)->strategic_document_type_id) == $row->id) selected
                                            @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('strategic_document_type')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12"></div>
            @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsPropertiesMain(),'field' => 'file_info_main', 'required' => false])
            <div class="col-md-8">
                <div class="form-group form-group-sm">
                    <label class="col-sm-12 control-label" for="visible_in_report"><br>
                        <input type="checkbox" id="visible_in_report" name="visible_in_report" class="checkbox"
                               value="1" @if (old('visible_in_report',0)) checked @endif>
                        {{ __('custom.visible_in_report') }}
                    </label>
                </div>
            </div>
            <div class="row">
                @foreach(config('available_languages') as $lang)
                    @php($fieldName = 'file_strategic_documents_'.$lang['code'])
                    @php(
                        $mainFileForLang = $mainFiles->first(function($file) use ($lang) {
                            return $file->locale === $lang['code'];
                        })
                    )
                    @php($validationRules = \App\Enums\StrategicDocumentFileEnum::validationRules($lang['code']))
                    <div class="col-md-6 mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="{{ $fieldName }}"
                                   class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))
                                    <span class="required">*</span>
                                @endif </label>
                            @if ($mainFileForLang)
                                {{ $mainFileForLang->display_name }}
                            @endif
                        </div>
                        @if ($mainFileForLang)
                            @php(
                                $fieldName = $fieldName. '_main'
                            )
                            <div>
                                <input class="form-control form-control-sm" type="file" name="{{ $fieldName }}">
                                <input type="hidden" name="main_fileId_{{ $lang['code'] }}"
                                       value="{{ $mainFileForLang->id }}">
                                @error($fieldName)
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div>
                                <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror"
                                       type="file" name="{{ $fieldName }}">
                                @error($fieldName)
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <!-- End Files -->
        <div class="row">
            <div class="form-group row">
                <div class="col-md-6 col-md-offset-3">
                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                    <button id="stay" type="submit" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>

                    @if ($item->active)
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
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#pris_options').select2();
            const dateExpiring = $('#document_date_expiring');
            const dateExpiringCheckbox = $('#date_expiring_indefinite');
            dateExpiring.on('change', function () {
                dateExpiringCheckbox.prop('checked', false);
            });

            dateExpiring.on('change', function () {
                if (!dateExpiringCheckbox.is(':checked')) {

                    dateExpiringCheckbox.prop('checked', $(this).val() === '').trigger('change');
                }
            })

            dateExpiringCheckbox.on('change', function () {
                if ($(this).is(':checked')) {
                    if (dateExpiring.val() !== '') {
                        dateExpiring.val('').trigger('change');
                    }
                }
            })

            $('#the_legal_act_type_filter').on('change', function () {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: `/admin/strategic-documents/pris-option/${selectedValue}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            let prisOptions = data.prisOptions;
                            let parentActId = $('#pris_act_id');
                            parentActId.empty();

                            parentActId.append('<option value="">---</option>');

                            $.each(prisOptions, function (index, option) {
                                let selected = (option.id == '{{ old('pris_act_id', $item->pris ? $item->pris?->id : null) }}') ? 'selected' : '';
                                parentActId.append('<option value="' + option.id + '" ' + selected + '>' + option.text + '</option>');
                            });

                            parentActId.trigger('change');
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }
            });

            $('#accept_act_institution_type_id').on('change', function () {
                let selectedValue = $(this).val();
                if (selectedValue == 1) {
                    $('#strategic_act_link').val('');
                    $('#document_date').val('');
                } else {
                    $('#document_date_pris').val('');
                    $('#pris_act_id').val('').trigger('change');
                }
            });

            $('#pris_act_id').on('change', function () {
                const selectedValue = $(this).val();
                console.log(selectedValue);
                if (selectedValue) {
                    $.ajax({
                        url: `/admin/strategic-documents/pris-option/${selectedValue}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#document_date_pris').val(data.doc_date);
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }
            });
        });
    </script>
@endpush

