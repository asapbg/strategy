@php
    /** @var $current_tab - used to determine the fragment for the pagination */
    $current_tab ??= '';
    $archive_category ??= 0;
    $view_mode ??= false;
@endphp
<div class="row justify-content-between align-items-center">
    @if(!$view_mode)
        <div class="col-auto mb-2">
            @if($archive_category == 2)
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal-create-working-program">
                    {{ __('custom.add') . ' ' . trans_choice('custom.function', 1) }}
                </button>
            @endif
            @if($archive_category == 1)
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal-create-meeting">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . trans_choice('custom.meetings', 1) }}
                </button>
            @endif
        </div>
    @endif
</div>

<div id="accordion">
    @if(isset($items) && $items->count() > 0)
        @foreach($items as $key => $archiveItem)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-10">
                                <a data-toggle="collapse" href="#collapse{{$key}}"
                                   aria-expanded="true" class="font-weight-bold">
                                    @if($archive_category == 1)
                                        {{ trans_choice('custom.meetings', 1) . ' ' .  __('custom.from') . ' ' . \Carbon\Carbon::parse($archiveItem->next_meeting)->format('d.m.Y') . __('custom.year_short') }}
                                    @endif

                                    @if($archive_category == 2)
                                        {{ __('custom.function') . ' ' .  __('custom.from') . ' ' . \Carbon\Carbon::parse($archiveItem->working_year)->format('Y') . __('custom.year_short') }}
                                    @endif
                                </a>
                            </div>
                            @if(!$view_mode)
                                @if($archive_category == 2)
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                @can('update', $item)
                                                    <button type="button"
                                                            class="btn btn-sm btn-info mr-2"
                                                            data-toggle="modal"
                                                            data-target="#modal-edit-function"
                                                            title="{{ __('custom.edit') }}"
                                                            onclick="loadFunctionData('{{ route('admin.advisory-boards.function.edit', ['item' => $item, 'working_program' => $archiveItem]) }}');">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endcan
                                            </div>

                                            <div class="col-auto">
                                                @can('delete', $item)
                                                    @if(!$archiveItem->deleted_at)
                                                        <a href="javascript:;"
                                                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                           data-target="#modal-remove-working-program"
                                                           data-resource-id="{{ $archiveItem->id }}"
                                                           data-resource-delete-url="{{ route('admin.advisory-boards.function.delete', ['item' => $item, 'working_program' => $archiveItem]) }}"
                                                           data-toggle="tooltip"
                                                           title="{{__('custom.delete')}}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('restore', $item)
                                                    @if($archiveItem->deleted_at)
                                                        <a href="javascript:;"
                                                           class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                           data-target="#modal-restore-section"
                                                           data-resource-id="{{ $archiveItem->id }}"
                                                           data-resource-restore-url="{{ route('admin.advisory-boards.function.restore', ['item' => $item, 'working_program' => $archiveItem]) }}"
                                                           data-toggle="tooltip"
                                                           title="{{__('custom.restore')}}">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($archive_category == 1)
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                @can('update', $item)
                                                    <button type="button"
                                                            class="btn btn-sm btn-success mr-2"
                                                            data-toggle="modal"
                                                            data-target="#modal-add-meeting-decisions"
                                                            title="{{ __('custom.add') . ' ' . __('custom.information') }}"
                                                            onclick="prepareMeetingId('{{ $archiveItem->id }}', MEETING_DECISIONS_FORM)">
                                                        <i class="fa fa-handshake"></i>
                                                    </button>
                                                @endcan
                                            </div>

                                            <div class="col-auto">
                                                @can('update', $item)
                                                    <button type="button"
                                                            class="btn btn-sm btn-info mr-2"
                                                            data-toggle="modal"
                                                            data-target="#modal-edit-meeting"
                                                            title="{{ __('custom.edit') }}"
                                                            onclick="loadMeetingData('{{ route('admin.advisory-boards.meetings.edit', ['item' => $item, 'meeting' => $archiveItem]) }}');">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endcan
                                            </div>

                                            <div class="col-auto">
                                                @can('delete', $item)
                                                    @if(!$archiveItem->deleted_at)
                                                        <a href="javascript:;"
                                                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                           data-target="#modal-delete-meeting"
                                                           data-resource-id="{{ $archiveItem->id }}"
                                                           data-resource-delete-url="{{ route('admin.advisory-boards.meetings.delete', ['item' => $item, 'meeting' => $archiveItem]) }}"
                                                           data-toggle="tooltip"
                                                           title="{{__('custom.delete')}}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('restore', $item)
                                                    @if($archiveItem->deleted_at)
                                                        <a href="javascript:;"
                                                           class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                           data-target="#modal-restore-meeting"
                                                           data-resource-id="{{ $archiveItem->id }}"
                                                           data-resource-restore-url="{{ route('admin.advisory-boards.meetings.restore', ['item' => $item, 'meeting' => $archiveItem]) }}"
                                                           data-toggle="tooltip"
                                                           title="{{__('custom.restore')}}">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </h4>
                </div>

                @php $show = $key === 0 ? 'show' : '' @endphp
                <div id="collapse{{ $key }}" class="collapse {{ $show }}"
                     data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            @if(!empty($archiveItem->translations[0]?->description))
                                <div class="col-6 border-right">
                                    <p>(BG)</p>
                                    {!! $archiveItem->translations[0]->description !!}
                                </div>
                            @endif

                            @if($archiveItem->translations->count() > 1 && !empty($archiveItem->translations[1]?->description))
                                <div class="col-6">
                                    <p>(EN)</p>
                                    {!! $archiveItem->translations[1]->description !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr/>
                        </div>
                    </div>
                    @if($archive_category == 1)
                        <div class="p-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>{{ __('custom.information') }}</h4>
                                </div>

                                @if(isset($archiveItem->decisions) && $archiveItem->decisions->count() > 0)
                                    @foreach($archiveItem->decisions as $information)
                                        <div class="col-12">
                                            <p>
                                                {{ __('custom.meeting_date') . ':' . ' ' . \Carbon\Carbon::parse($information->date_of_meeting)->format('d.m.Y') }}
                                            </p>
                                        </div>

                                        <div class="col-12">
                                            <p>
                                                {{ __('validation.attributes.agenda') . ':' . ' ' . $information->agenda }}
                                            </p>
                                        </div>

                                        <div class="col-12">
                                            <p>
                                                {{ __('validation.attributes.protocol') . ':' . ' ' . $information->protocol }}
                                            </p>
                                        </div>

                                        <div class="col-12">
                                            <p>
                                                {{ __('validation.attributes.decisions') . ':' }} {!! $information->decisions !!}
                                            </p>
                                        </div>

                                        <div class="col-12">
                                            <p>
                                                {{ __('validation.attributes.suggestions') . ':' }} {!! $information->suggestions !!}
                                            </p>
                                        </div>

                                        <div class="col-12">
                                            <p>
                                                {{ __('validation.attributes.other') . ':' }} {!! $information->other !!}
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                                    </div>

                                    @if(!$view_mode)
                                        <div class="col-auto">
                                            <div class="custom-control custom-switch">
                                                @php $checked = request()->get('show_deleted_archive_files', '0') == '1' ? 'checked' : '' @endphp
                                                <input type="checkbox" class="custom-control-input"
                                                       id="show_deleted_archive_files"
                                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'archive')">
                                                <label class="custom-control-label"
                                                       for="show_deleted_archive_files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-auto">
                                @if(!$view_mode)
                                    @if($archive_category == 2)
                                        <button type="button" class="btn btn-success"
                                                data-toggle="modal"
                                                data-target="#modal-add-function-file"
                                                onclick="setFunctionFileObjectId('{{ $archiveItem->id }}')">
                                            <i class="fa fa-plus mr-3"></i>
                                            {{ __('custom.add') . ' ' . __('custom.file') }}
                                        </button>
                                    @endif

                                    @if($archive_category == 1)
                                            <button type="button" class="btn btn-success"
                                                    data-toggle="modal"
                                                    data-target="#modal-add-meeting-file"
                                                    onclick="setMeetingFileObjectId('{{ $archiveItem->id }}')">
                                                <i class="fa fa-plus mr-3"></i>
                                                {{ __('custom.add') . ' ' . __('custom.file') }}
                                            </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @php($archiveFiles = request()->get('show_deleted_archive_files', 0) ? $archiveItem->files()->withTrashed()->get() : $archiveItem->files)
                        <div class="row mt-3">
                            <div class="col-12">
                                @include('admin.partial.files_table', ['files' => $archiveFiles, 'item' => $item, 'view_mode' => $view_mode, 'delete_tab' => 'archive'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="row">
    <nav aria-label="Page navigation example">
        @if(isset($items) && $items->count() > 0)
            {{ $items->appends(request()->query())->fragment($current_tab)->links() }}
        @endif
    </nav>
</div>
