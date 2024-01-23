@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.advisory-boards.store') }}" method="post" name="form" id="form" enctype="multipart/form-data">
                        @csrf

                        <!-- Областна Политика -->
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
                                        @if(isset($field_of_actions) && $field_of_actions->count() > 0)
                                            @foreach($field_of_actions as $area)
                                                @php $selected = old('policy_area_id', '') == $area->id ? 'selected' : '' @endphp

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

                        <!-- Име на съвет -->
                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>
{{--                        <div class="row mb-4">--}}
{{--                            @foreach(config('available_languages') as $lang)--}}
{{--                                <div class="col-6">--}}
{{--                                    <label for="name_{{ $lang['code'] }}">--}}
{{--                                        {{ __('validation.attributes.advisory_name') }}--}}
{{--                                        ({{ Str::upper($lang['code']) }})--}}
{{--                                        <span class="required">*</span>--}}
{{--                                    </label>--}}

{{--                                    <input type="text" id="name_{{ $lang['code'] }}" name="name_{{ $lang['code']}}"--}}
{{--                                           class="form-control form-control-sm @error('name_' . $lang['code']){{ 'is-invalid' }}@enderror"--}}
{{--                                           value="{{ old('name_' . $lang['code'], '') }}" autocomplete="off">--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}

                        <div class="row mb-4">
                            <div class="col-auto">
                                <div class="form-check pl-4">
                                    @php $checked = old('has_npo_presence', '') === 'on' ? 'checked' : '' @endphp
                                    <input type="checkbox" name="has_npo_presence" class="form-check-input"
                                           id="npo_presence" {{ $checked }}
                                           onchange="document.querySelector('.npo-container').classList.toggle('d-none'); resetNpoContainer();">
                                    <label class="form-check-label font-weight-semibold" for="npo_presence">
                                        {{ __('custom.presence_npo_representative') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Наличие на представител на НПО в състава на съвета -->
                        <div class="npo-container d-none">
                            <div class="npo-children">
                                <div class="row">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-6">
                                            <label for="npo_{{ $lang['code'] }}[]">
                                                {{ __('validation.attributes.npo_presenter') }}
                                                ({{ Str::upper($lang['code']) }})
                                            </label>

                                            <input type="text" id="npo_{{ $lang['code'] }}[]"
                                                   name="npo_{{ $lang['code']}}[]"
                                                   class="form-control form-control-sm"
                                                   value="" autocomplete="off">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-auto mt-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="addNpo()">
                                            {{ __('custom.add_npo_presenter') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Съветът е създаден към -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="authority_id">
                                        {{ trans_choice('validation.attributes.authority_id', 1) }}
                                        <span class="required">*</span>
                                    </label>


                                    <select id="authority_id" name="authority_id"
                                            class="form-control form-control-sm select2-no-clear">
                                        <option value="">---</option>
                                        @if(isset($authorities) && $authorities->count() > 0)
                                            @foreach($authorities as $authority)
                                                @php $selected = old('authority_id', '') == $authority->id ? 'selected' : '' @endphp

                                                <option
                                                    value="{{ $authority->id }}" {{ $selected }}>{{ $authority->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('authority_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Акт на създаване -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="advisory_act_type_id">
                                        {{ trans_choice('validation.attributes.act_of_creation', 1) }}
                                        <span class="required">*</span>
                                    </label>


                                    <select id="advisory_act_type_id" name="advisory_act_type_id"
                                            class="form-control form-control-sm select2-no-clear">
                                        <option value="">---</option>
                                        @if(isset($advisory_act_types) && $advisory_act_types->count() > 0)
                                            @foreach($advisory_act_types as $type)
                                                @php $selected = old('advisory_act_type_id', '') == $type->id ? 'selected' : '' @endphp

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
                        </div>

                        <!-- Председател -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="advisory_chairman_type_id">
                                        {{ trans_choice('validation.attributes.advisory_chairman_type_id', 1) }}
                                        <span class="required">*</span>
                                    </label>


                                    <select id="advisory_chairman_type_id" name="advisory_chairman_type_id"
                                            class="form-control form-control-sm select2-no-clear">
                                        <option value="">---</option>
                                        @if(isset($advisory_chairman_types) && $advisory_chairman_types->count() > 0)
                                            @foreach($advisory_chairman_types as $type)
                                                @php $selected = old('advisory_chairman_type_id', '') == $type->id ? 'selected' : '' @endphp

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
                        </div>

                        <!-- Зам. Председател -->
                        <div class="row mb-4">
                            <div class="col-auto">
                                <div class="form-check pl-4">
                                    @php $checked = old('has_vice_chairman', '') === 'on' ? 'checked' : '' @endphp
                                    <input type="checkbox" name="has_vice_chairman" class="form-check-input"
                                           id="has_vice_chairman" {{ $checked }}>
                                    <label class="form-check-label font-weight-semibold" for="has_vice_chairman">
                                        {{ __('validation.attributes.vice_chairman') }}
                                    </label>
                                </div>
                            </div>

                            @php $class = $checked === 'checked' ? '' : 'd-none' @endphp
                            <div class="col-12 {{ $class }}" id="member_information">
                                <!-- Имена -->
                                <div class="row mb-4">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-6">
                                            <label for="member_name_{{ $lang['code'] }}">
                                                {{ __('validation.attributes.first_name') }}
                                                ({{ Str::upper($lang['code']) }})
                                                <span class="required">*</span>
                                            </label>

                                            <input type="text" id="member_name_{{ $lang['code'] }}"
                                                   name="member_name_{{ $lang['code']}}"
                                                   class="form-control form-control-sm @error('member_name_' . $lang['code']){{ 'is-invalid' }}@enderror"
                                                   value="{{ old('member_name_' . $lang['code'], '') }}"
                                                   autocomplete="off">

                                            @error('member_name_' . $lang['code'])
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Длъжност -->
                                <div class="row mb-4">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-6">
                                            <label for="member_job_{{ $lang['code'] }}">
                                                {{ __('validation.attributes.job') }}
                                                ({{ Str::upper($lang['code']) }})
                                            </label>

                                            <input type="text" id="member_job_{{ $lang['code'] }}"
                                                   name="member_job_{{ $lang['code']}}"
                                                   class="form-control form-control-sm @error('member_job_' . $lang['code']){{ 'is-invalid' }}@enderror"
                                                   value="{{ old('member_job_' . $lang['code'], '') }}"
                                                   autocomplete="off">

                                            @error('member_job_' . $lang['code'])
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Бележки и кратка информация -->
                                <div class="row mb-4">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-6">
                                            <label for="member_notes_{{ $lang['code'] }}">
                                                {{ __('validation.attributes.member_notes') }}
                                                ({{ Str::upper($lang['code']) }})
                                            </label>

                                            <textarea class="form-control form-control-sm summernote"
                                                      name="member_notes_{{ $lang['code'] }}"
                                                      id="member_notes_{{ $lang['code'] }}">
                                                {{ old('member_notes_' . $lang['code']) }}
                                            </textarea>

                                            @error('member_notes_' . $lang['code'])
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Регламентиран брой заседания на година -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="meetings_per_year">
                                        {{ trans_choice('validation.attributes.meetings_per_year', 1) }}
                                        <span class="required">*</span>
                                    </label>

                                    <input type="number" id="meetings_per_year" name="meetings_per_year"
                                           class="form-control form-control-sm"
                                           value="{{ old('meetings_per_year', '') }}"/>

                                    @error('meetings_per_year')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Препратка към Интегрираната информационна система на държавната администрация (ИИСДА) -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="integration_link">{{ __('validation.attributes.redirect_to_iisda') }}</label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" id="integration_link"
                                                   name="integration_link"
                                                   class="form-control form-control-sm @error('integration_link'){{ 'is-invalid' }}@enderror"
                                                   value="{{ old('integration_link', '') }}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="active">
                                        {{ __('validation.attributes.main_img') }} <span class="required">*</span>
                                        <br><span class="text-primary"><i>Препоръчителен размер 1900px x 400px</i></span>
                                    </label>
                                    @if($item->id && $item->mainImg)
                                        <img src="{{ $item->headerImg }}" class="img-thumbnail mt-2 mb-4">
                                    @endif
                                    <div class="col-12">
                                        <input type="file" name="file" class="form-control form-control-sm @error('file'){{ 'is-invalid' }}@enderror">
                                        @error('file')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit"
                                        class="btn btn-success">{{ __('custom.save') . ' ' . __('custom.as') . ' ' . Str::lower(__('custom.draft')) }}</button>

                                <button id="save" type="submit" name="public" value="1"
                                        class="btn btn-success">{{ __('custom.publish') }}</button>

                                <a href="{{ route('admin.advisory-boards.index') }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>

                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script type="application/javascript">
            const available_languages = @json(config('available_languages'));
            const label_text = @json(__('validation.attributes.npo_presenter'));

            // Toggle vice president information fields
            document.querySelector('#has_vice_chairman').addEventListener('change', function (event) {
                document.querySelector('#member_information').classList.toggle('d-none');
            });

            function resetNpoContainer() {
                document.querySelectorAll('.npo-custom-children').forEach(child => child.remove());
            }

            function addNpo() {
                const container = document.querySelector('.npo-container .npo-children');

                const row = document.createElement('div');
                row.classList.add('row', 'mt-3', 'align-items-center', 'npo-custom-child');

                for (let i in available_languages) {
                    const is_even = i % 2 === 0;

                    let column = generateNpoInput(available_languages[i]['code'], is_even);

                    row.appendChild(column);

                    if (is_even) {
                        const remove_btn_col = document.createElement('div');
                        remove_btn_col.classList.add('col-1');

                        const remove_btn = document.createElement('button');
                        remove_btn.classList.add('btn-close', 'float-right');
                        remove_btn.type = 'button';
                        remove_btn.onclick = () => remove_btn.closest('.npo-custom-child').remove();

                        remove_btn_col.appendChild(remove_btn);

                        row.appendChild(remove_btn_col);
                    }
                }

                container.appendChild(row);
            }

            function generateNpoInput(language, add_space_for_close_btn = false) {
                // Create column
                const column = document.createElement('div');
                column.classList.add(add_space_for_close_btn ? 'col-5' : 'col-6');

                // Create label
                const label = document.createElement('label');
                label.for = `npo_${language}[]`;
                label.textContent = label_text + ' (' + language.toUpperCase() + ')';

                // Create a new input element
                const input = document.createElement('input');

                // Set the input attributes
                input.type = 'text';
                input.id = `npo_${language}[]`;
                input.name = `npo_${language}[]`;
                input.classList.add('form-control', 'form-control-sm');

                column.appendChild(label);
                column.appendChild(input);

                return column;
            }
        </script>
    @endpush
@endsection
