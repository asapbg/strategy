@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.advisory_board_moderator_info', 1) }}</h3>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_MODERATOR.submit();">
                        {{ __('custom.save') . ' ' . trans_choice('custom.section', 1) }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#secretariat' }}"
                       class="btn btn-info">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @if(!$view_mode)
                    <form name="ADVISORY_BOARD_MODERATOR"
                          action="{{ route('admin.advisory-boards.moderator.information.store', ['item' => $item, 'information' => $item->moderatorInformation]) }}"
                          method="post">
                        @csrf

                        <div class="row mb-3">
                            @include('admin.partial.edit_field_translate', ['item' => $item->moderatorInformation, 'translatableFields' => \App\Models\AdvisoryBoardModeratorInformation::translationFieldsProperties(), 'field' => 'description', 'required' => true])
                        </div>
{{--                        @foreach(config('available_languages') as $lang)--}}
{{--                            <div class="row mb-3">--}}
{{--                                <div class="col-12">--}}
{{--                                    <label for="description_{{ $lang['code'] }}">{{ __('custom.description') }}--}}
{{--                                        ({{ Str::upper($lang['code']) }})</label>--}}

{{--                                    @php--}}
{{--                                        $description = $item->moderatorInformation?->translations->count() === 2 ?--}}
{{--                                            $item->moderatorInformation?->translations->first(fn($row) => $row->locale == $lang['code'])->description :--}}
{{--                                            old('description_' . $lang['code'], '');--}}
{{--                                    @endphp--}}

{{--                                    <textarea class="form-control form-control-sm summernote"--}}
{{--                                              name="description_{{ $lang['code'] }}"--}}
{{--                                              id="description_{{ $lang['code'] }}">--}}
{{--                                    {{ $description }}--}}
{{--                                </textarea>--}}

{{--                                    @error('description_' . $lang['code'])--}}
{{--                                    <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
                    </form>
                @else
                    @foreach(config('available_languages') as $lang)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                    ({{ Str::upper($lang['code']) }})</label>

                                @php
                                    $description = $item->moderatorInformation?->translations->count() === 2 ?
                                        $item->moderatorInformation?->translations->first(fn($row) => $row->locale == $lang['code'])->description : '';
                                @endphp

                                <div class="row">
                                    {!! $description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <hr/>
            </div>
        </div>

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    <div class="col-auto">
                        @if(!$view_mode)
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_moderator_files', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show_deleted_moderator_files"
                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'moderator')">
                                <label class="custom-control-label"
                                       for="show_deleted_moderator_files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-add-moderator-file">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . __('custom.file') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $item->moderatorInformation?->files, 'item' => $item])
            </div>
        </div>

        @if(auth()->user()->hasRole(\App\Models\CustomRole::MODERATOR_ADVISORY_BOARDS) || auth()->user()->hasRole(\App\Models\CustomRole::ADMIN_USER_ROLE))
            <div class="row">
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row mt-3 justify-content-between align-items-center">
                <div class="col-md-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <h3>{{ trans_choice('custom.moderators', 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-10">
                    <form method="POST" name="ADVISORY_BOARD_ADD_MODERATOR" id="submit_moderator"
                          action="{{ route('admin.advisory-boards.moderator.store', ['item' => $item]) }}">
                        @csrf

                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <select name="user_id" class="select2 form-control form-control-sm" id="user_id">
                                    <option value="">{{ __('custom.username') }}</option>

                                    @if(isset($all_users) && $all_users->count() > 0)
                                        @foreach($all_users as $user)
                                            <option value="{{ $user->id }}">{{ implode(' ', [$user->first_name, $user->middle_name, $user->last_name]) }}({{ $user->email }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-success" id="add_moderator">
                                    <i class="fa fa-plus mr-3"></i>
                                    {{ __('custom.add') . ' ' . trans_choice('custom.moderators', 1) }}
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-register-advisory-moderator">
                                    <i class="fa fa-plus mr-3"></i>
                                    {{ __('custom.register') . ' ' . __('custom.of') . ' ' . trans_choice('custom.users', 1) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('custom.name') }}</th>
                            <th>{{ __('validation.attributes.created_at') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($moderators) && $moderators->count() > 0)
                            @foreach($moderators as $moderator)
                                <tr>
                                    <td>{{ $moderator->id }}</td>
                                    <td>{{ $moderator->user->fullName() }}</td>
                                    <td>{{ $moderator->created_at }}</td>
                                    <td>
                                        @can('update', $item)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                               data-target="#modal-remove-moderator"
                                               data-resource-id="{{ $moderator->id }}"
                                               data-resource-delete-url="{{ route('admin.advisory-boards.moderator.delete', ['item' => $item, 'moderator' => $moderator]) }}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.delete')}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#submit_moderator').submit(function (e){
                if(!(parseInt($('#user_id').val()) > 0)){
                    new MyModal({
                        title: '{{ __('custom.error') }}',
                        body: '<p>{{ __('custom.select_moderator_user') }}</p>',
                        footer: '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="{{ __('custom.close') }}">{{ __('custom.close') }}</button>',
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
