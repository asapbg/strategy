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

           @includeIf('admin.partial.advisory-board-moderator')
        @endif
    </div>
</div>
