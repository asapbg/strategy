<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <form action="{{ route('admin.advisory-boards.update', $item) }}" method="post" name="form"
              id="form">
            @csrf

            <div class="row mb-4">
                @if($can_foreach_translations)
                    @foreach($item->translations as $translation)
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="name_{{ $translation->locale }}">{{ __('validation.attributes.advisory_name') }}
                                    ({{ Str::upper($translation->locale) }}) <span
                                        class="required">*</span></label>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" id="name_{{ $translation->locale }}"
                                               name="name_{{ $translation->locale }}"
                                               class="form-control form-control-sm @error('name_' . $translation->locale){{ 'is-invalid' }}@enderror"
                                               value="{{ old('name_' . $translation->locale, $translation->name ?? '') }}"
                                               autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row mb-4">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label class="control-label" for="policy_area_id">
                            {{ trans_choice('custom.field_of_actions', 1) }}
                            <span class="required">*</span>
                        </label>

                        <select id="policy_area_id" name="policy_area_id"
                                class="form-control form-control-sm select2-no-clear">
                            <option value="">---</option>

                            @if(isset($policy_areas) && $policy_areas->count() > 0)
                                @foreach($policy_areas as $area)
                                    @php $selected = old('policy_area_id', $item->policy_area_id ?? '') == $area->id ? 'selected' : '' @endphp

                                    <option
                                        value="{{ $area->id }}" {{ $selected }}>{{ $area->name }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('policy_area_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label class="control-label" for="advisory_chairman_type_id">
                            {{ trans_choice('validation.attributes.council_attached_to', 1) }}
                            <span class="required">*</span>
                        </label>


                        <select id="advisory_chairman_type_id" name="advisory_chairman_type_id"
                                class="form-control form-control-sm select2-no-clear">
                            <option value="">---</option>
                            @if(isset($advisory_chairman_types) && $advisory_chairman_types->count() > 0)
                                @foreach($advisory_chairman_types as $type)
                                    @php $selected = old('advisory_chairman_type_id', $item->advisory_chairman_type_id ?? '') == $type->id ? 'selected' : '' @endphp

                                    <option
                                        value="{{ $type->id }}" {{ $selected }}>{{ $type->name }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('advisory_chairman_type_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        @if($can_foreach_translations)
                            @foreach($item->translations as $translation)
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="advisory_specific_name_{{ $translation->locale }}">
                                            {{ trans_choice('validation.attributes.specific_name', 1) }}
                                            ({{ Str::upper($translation->locale) }})
                                        </label>

                                        <input type="text"
                                               id="advisory_specific_name_{{ $translation->locale }}"
                                               name="advisory_specific_name_{{ $translation->locale }}"
                                               class="form-control form-control-sm"
                                               value="{{ old('advisory_specific_name_' . $translation->locale, $translation->advisory_specific_name ?? '') }}"/>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label class="control-label" for="advisory_act_type_id">
                            {{ trans_choice('validation.attributes.act_of_creation', 1) }}
                        </label>


                        <select id="advisory_act_type_id" name="advisory_act_type_id"
                                class="form-control form-control-sm select2-no-clear">
                            <option value="">---</option>

                            @if(isset($advisory_act_types) && $advisory_act_types->count() > 0)
                                @foreach($advisory_act_types as $type)
                                    @php $selected = old('advisory_act_type_id', $item->advisory_act_type_id ?? '') == $type->id ? 'selected' : '' @endphp

                                    <option
                                        value="{{ $type->id }}" {{ $selected }}>{{ $type->name }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('advisory_act_type_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        @if($can_foreach_translations)
                            @foreach($item->translations as $translation)
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="advisory_act_specific_name_{{ $translation->locale }}">
                                            {{ trans_choice('validation.attributes.specific_name', 1) }}
                                            ({{ Str::upper($translation->locale) }})
                                        </label>

                                        <input type="text"
                                               id="advisory_act_specific_name_{{ $translation->locale }}"
                                               name="advisory_act_specific_name_{{ $translation->locale }}"
                                               class="form-control form-control-sm"
                                               value="{{ old('advisory_act_specific_name_' . $translation->locale, $translation->advisory_act_specific_name ?? '') }}"/>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label class="control-label" for="meetings_per_year">
                            {{ trans_choice('validation.attributes.meetings_per_year', 1) }}
                            <span class="required">*</span>
                        </label>

                        <input type="number" id="meetings_per_year" name="meetings_per_year"
                               class="form-control form-control-sm"
                               value="{{ old('meetings_per_year', $item->meetings_per_year ?? '') }}"/>

                        @error('meetings_per_year')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label class="control-label" for="report_institution_id">
                            {{ trans_choice('validation.attributes.report_at', 1) }}
                            <span class="required">*</span>
                        </label>


                        <select id="report_institution_id" name="report_institution_id"
                                class="form-control form-control-sm select2-no-clear">
                            <option value="">---</option>
                            @if(isset($institutions) && $institutions->count() > 0)
                                @foreach($institutions as $institution)
                                    @php $selected = old('report_institution_id', $item->report_institution_id ?? '') == $institution->id ? 'selected' : '' @endphp

                                    <option
                                        value="{{ $institution->id }}" {{ $selected }}>{{ $institution->name }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('report_institution_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        @if($can_foreach_translations)
                            @foreach($item->translations as $translation)
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="report_institution_specific_name_{{ $translation->locale }}">
                                            {{ trans_choice('validation.attributes.specific_name', 1) }}
                                            ({{ Str::upper($translation->locale) }})
                                        </label>

                                        <input type="text"
                                               id="report_institution_specific_name_{{ $translation->locale }}"
                                               name="report_institution_specific_name_{{ $translation->locale }}"
                                               class="form-control form-control-sm"
                                               value="{{ old('report_institution_specific_name_' . $translation->locale, $translation->report_institution_specific_name ?? '') }}"/>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 col-md-offset-3">
                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                    <a href="{{ route('admin.advisory-boards.index') }}"
                       class="btn btn-primary">{{ __('custom.cancel') }}</a>
                </div>
            </div>

            <br/>
        </form>
    </div>
</div>
