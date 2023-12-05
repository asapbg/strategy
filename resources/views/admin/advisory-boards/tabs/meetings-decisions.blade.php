@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.meetings', 2) }}</h3>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-create-meeting">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . trans_choice('custom.meetings', 1) }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#decisions' }}"
                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('validation.attributes.next_meeting') }}</th>
                        <th>{{ __('custom.description') }}</th>
                        <th>{{ __('validation.attributes.created_at') }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($meetings) && $meetings->count() > 0)
                        @foreach($meetings as $meeting)
                            <tr>
                                <td>{{ $meeting->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($meeting->next_meeting)->format('d.m.Y') }}</td>
                                <td class="col-8">{!! $meeting->description !!}</td>
                                <td>{{ $meeting->created_at }}</td>
                                <td>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @can('update', $item)
                                                <button type="button"
                                                        class="btn btn-sm btn-success mr-2"
                                                        data-toggle="modal"
                                                        data-target="#modal-add-meeting-decisions"
                                                        title="{{ __('custom.add') . ' ' . __('custom.information') }}"
                                                        onclick="prepareMeetingId('{{ $meeting->id }}', MEETING_DECISIONS_FORM)">
                                                    <i class="fa fa-handshake"></i>
                                                </button>
                                            @endcan
                                        </div>

                                        <div class="col-auto">
                                            @can('update', $item)
                                                <button type="button"
                                                        class="btn btn-sm btn-warning mr-2"
                                                        data-toggle="modal"
                                                        data-target="#modal-edit-meeting"
                                                        title="{{ __('custom.edit') }}"
                                                        onclick="loadMeetingData('{{ route('admin.advisory-boards.meetings.edit', ['item' => $item, 'meeting' => $meeting]) }}');">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                        </div>

                                        <div class="col-auto">
                                            @can('delete', $item)
                                                @if(!$meeting->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                       data-target="#modal-delete-meeting"
                                                       data-resource-id="{{ $meeting->id }}"
                                                       data-resource-delete-url="{{ route('admin.advisory-boards.meetings.delete', ['item' => $item, 'meeting' => $meeting]) }}"
                                                       data-toggle="tooltip"
                                                       title="{{__('custom.delete')}}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('restore', $item)
                                                @if($meeting->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                       data-target="#modal-restore-meeting"
                                                       data-resource-id="{{ $meeting->id }}"
                                                       data-resource-restore-url="{{ route('admin.advisory-boards.meetings.restore', ['item' => $item, 'meeting' => $meeting]) }}"
                                                       data-toggle="tooltip"
                                                       title="{{__('custom.restore')}}">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
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

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_decisions_files', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show-deleted-decisions-files"
                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'decisions')">
                                <label class="custom-control-label"
                                       for="show-deleted-decisions-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-add-meeting-file">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . __('custom.file') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $meetings_decisions_files, 'item' => $item])
            </div>
        </div>
    </div>
</div>
