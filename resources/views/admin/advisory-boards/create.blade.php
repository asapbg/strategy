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
                                           onchange="resetNpoContainer($(this));">
                                    <label class="form-check-label font-weight-semibold" for="npo_presence">
                                        {{ __('custom.presence_npo_representative') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Наличие на представител на НПО в състава на съвета -->
                        <div class="npo-container @if(!old('has_npo_presence', 0)) d-none @endif" id="npo-container">
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
                                            class="form-control form-control-sm select2-no-clear"
                                            onchange="this.value == @json(\App\Models\AuthorityAdvisoryBoard::getOtherAuthorityId()) ? $('#other_authority_container').show() : $('#other_authority_container').hide();">
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

                                    @php $display = !empty(old('other_authority_name_' . config('available_languages')[1]['code'], '')) ? 'masonry' : 'none'; @endphp
                                    <!-- Друг вид орган, към който е създаден съветът -->
                                    <div class="row mt-1" id="other_authority_container" style="display: {{ $display }};">
                                        @foreach(config('available_languages') as $lang)
                                            <div class="col-6">
                                                <label for="other_authority_name_{{ $lang['code'] }}">
                                                    {{ __('custom.other_authority') }}({{ Str::upper($lang['code']) }})
                                                </label>

                                                <input type="text" id="other_authority_name_{{ $lang['code'] }}"
                                                       name="other_authority_name_{{ $lang['code']}}"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('other_authority_name_' . $lang['code'], '') }}" autocomplete="off">
                                            </div>
                                        @endforeach
                                    </div>
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
                                            class="form-control form-control-sm select2-no-clear"
                                            onchange="this.value == @json(\App\Models\AdvisoryActType::getOtherId()) ? $('#other_act_type_container').show() : $('#other_act_type_container').hide();">
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

                                    @php $display = !empty(old('other_act_type_name_' . config('available_languages')[1]['code'], '')) ? 'masonry' : 'none'; @endphp
                                    <!-- Друг вид акт на създаване -->
                                    <div class="row mt-1" id="other_act_type_container" style="display: {{ $display }};">
                                        @foreach(config('available_languages') as $lang)
                                            <div class="col-6">
                                                <label for="other_act_type_name_{{ $lang['code'] }}">
                                                    {{ __('custom.other_act_type') }}({{ Str::upper($lang['code']) }})
                                                </label>

                                                <input type="text" id="other_act_type_name_{{ $lang['code'] }}"
                                                       name="other_act_type_name_{{ $lang['code']}}"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('other_act_type_name_' . $lang['code'], '') }}" autocomplete="off">
                                            </div>
                                        @endforeach
                                    </div>
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
                                            class="form-control form-control-sm select2-no-clear"
                                            onchange="this.value == @json(\App\Models\AdvisoryChairmanType::getOtherId()) ? $('#other_chairman_type_container').show() : $('#other_chairman_type_container').hide();">
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

                                    @php $display = !empty(old('other_chairman_type_name_' . config('available_languages')[1]['code'], '')) ? 'masonry' : 'none'; @endphp
                                        <!-- Друг вид акт на създаване -->
                                    <div class="row mt-1" id="other_chairman_type_container" style="display: {{ $display }};">
                                        @foreach(config('available_languages') as $lang)
                                            <div class="col-6">
                                                <label for="other_chairman_type_name_{{ $lang['code'] }}">
                                                    {{ __('custom.other_chairman_type') }}({{ Str::upper($lang['code']) }})
                                                </label>

                                                <input type="text" id="other_chairman_type_name_{{ $lang['code'] }}"
                                                       name="other_chairman_type_name_{{ $lang['code']}}"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('other_chairman_type_name_' . $lang['code'], '') }}" autocomplete="off">
                                            </div>
                                        @endforeach
                                    </div>
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
                                    </label>

                                    <input type="number" id="meetings_per_year" name="meetings_per_year"
                                           class="form-control form-control-sm"
                                           onkeyup="this.value < 1 ? $('#no_meetings_per_year').attr('checked', true) : $('#no_meetings_per_year').attr('checked', false)"
                                           value="{{ old('meetings_per_year', '') }}" @if(!$item->meetings_per_year) readonly @endif/>

                                    @error('meetings_per_year')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check pl-4">
                                    @php $checked = old('meetings_per_year', '') < 1 ? 'checked' : '' @endphp

                                    <input type="checkbox" name="no_meetings_per_year" class="form-check-input"
                                           id="no_meetings_per_year" {{ $checked }} value="1"
                                           onchange="this.checked ? $('#meetings_per_year').attr('readonly', true) : $('#meetings_per_year').attr('readonly', false)"/>

                                    <label class="form-check-label font-weight-semibold" for="no_meetings_per_year">
                                        {{ __('custom.no_meetings_per_year') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Модератор -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-auto">
                                        <label class="control-label m-0" for="moderator_id">
                                            {{ __('custom.moderator_advisory_board') }}
                                        </label>
                                    </div>

                                    <div class="col-md-auto">
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-register-user">
                                            <i class="fa fa-plus mr-3"></i>
                                            {{ __('custom.register') . ' ' . __('custom.of') . ' ' . trans_choice('custom.users', 1) }}
                                        </button>
                                    </div>
                                </div>

                                <select name="moderator_id" class="select2 form-control form-control-sm" id="moderator_id">
                                    <option value="">{{ __('custom.username') }}</option>

                                    @if(isset($all_users) && $all_users->count() > 0)
                                        @foreach($all_users as $user)
                                            @php $selected = old('moderator_id', '') == $user->id ? 'selected' : ''; @endphp

                                            <option value="{{ $user->id }}" {{ $selected }}>{{ $user->fullInformation }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                                        {{ __('validation.attributes.main_img') }}
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

    @includeIf('admin.advisory-boards.modals.register-user-form')

    @push('scripts')
        <script type="application/javascript">
            const available_languages = @json(config('available_languages'));
            const label_text = @json(__('validation.attributes.npo_presenter'));

            // Toggle vice president information fields
            document.querySelector('#has_vice_chairman').addEventListener('change', function (event) {
                document.querySelector('#member_information').classList.toggle('d-none');
            });

            function resetNpoContainer(el) {
                if(el.is(':checked')){
                    $('#npo-container').removeClass('d-none');
                } else{
                    document.querySelectorAll('.npo-custom-children').forEach(child => child.remove());
                }
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

                // id input
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = `npo_id[]`;
                row.append(inputId);

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

            /**
             * Submit basic ajax form.
             * You can pass the submit button and the url for storing data.
             *
             * @param element
             * @param url
             */
            function submitAjax(element, url) {
                // change button state
                changeButtonState(element);
                clearErrorMessages();

                // Get the form element
                const form = element.closest('.modal-content').querySelector('form');
                const formData = new FormData(form);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                    },
                    url: url,
                    data: formData,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        if (typeof result.errors != 'undefined') {
                            let errors = Object.entries(result.errors);
                            for (let i = 0; i < errors.length; i++) {
                                const search_class = '.error_' + errors[i][0];
                                form.querySelector(search_class).textContent = errors[i][1][0];
                            }
                            changeButtonState(element, 'finished');
                        } else {
                            if (!result.user) {
                                return;
                            }

                            const select2 = $('#moderator_id');

                            // Add new option dynamically
                            select2.append(new Option(result.user.name, result.user.id, false, true));

                            // Trigger the change event to refresh Select2
                            select2.trigger('change');

                            // close modal
                            $('#modal-register-user').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        changeButtonState(element, 'finished');
                        // Handle error response
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            for (let i in errors) {
                                const search_class = '.error_' + i;
                                form.querySelector(search_class).textContent = errors[i][0];
                            }
                        }
                    }
                });
            }

            /**
             * Change button state for ajax forms.
             * State can be either 'loading' or 'finished'.
             *
             * @param element
             * @param state
             */
            function changeButtonState(element, state = 'loading') {
                const button_text_translation = state === 'loading' ? @json(__('custom.loading')) : @json(__('custom.add'));
                const loader = element.querySelector('.spinner-grow');

                element.querySelector('.text').innerHTML = button_text_translation;
                element.disabled = state === 'loading';

                if (state === 'loading') {
                    loader.classList.remove('d-none');
                    return;
                }

                loader.classList.add('d-none');
            }

            /**
             * Clear all errors from previous ajax.
             * By default it will clear all elements with class ajax-error
             *
             * @param className
             */
            function clearErrorMessages(className = 'ajax-error') {
                document.querySelectorAll('.' + className).forEach(function(el) {
                    el.innerHTML = '';
                });
            }
        </script>
    @endpush
@endsection
