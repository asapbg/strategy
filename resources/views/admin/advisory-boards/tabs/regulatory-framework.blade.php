<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    <div class="col-auto">
                        <div class="custom-control custom-switch">
                            @php $checked = request()->get('show_deleted_regulatory_files', '0') == '1' ? 'checked' : '' @endphp
                            <input type="checkbox" class="custom-control-input"
                                   id="show-deleted-regulatory-framework-files" {{ $checked }} onchange="toggleDeletedFiles(this, 'regulatory')">
                            <label class="custom-control-label"
                                   for="show-deleted-regulatory-framework-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal-add-regulatory-framework-file">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . __('custom.file') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('custom.name') }}</th>
                        <th>{{ __('custom.description') }}</th>
                        <th>{{ __('validation.attributes.created_at') }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($regulatory_framework_files) && $regulatory_framework_files->count() > 0)
                        @foreach($regulatory_framework_files as $file)
                            <tr>
                                <td>{{ $file->id }}</td>
                                <td>{{ $file->custom_name ?? $file->filename }}</td>
                                <td>{{ $file->description }}</td>
                                <td>{{ $file->created_at }}</td>
                                <td>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @can('view', $item)
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-info preview-file-modal mr-2"
                                                                data-file="{{ $file->id }}"
                                                                data-url="{{ route('admin.preview.file.modal', ['id' => $file->id]) }}">
                                                            {!! fileIcon($file->content_type) !!}
                                                            {{ __('custom.preview') }}
                                                        </button>
                                                    </div>

                                                    <div class="col-auto">
                                                        <a class="btn btn-sm btn-info mr-2"
                                                           href="{{ route('admin.download.file', $file) }}"
                                                           target="_blank" title="{{ __('custom.download') }}">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endcan
                                        </div>

                                        <div class="col-auto">
                                            @can('update', $item)
                                                <button type="button"
                                                        class="btn btn-sm btn-warning mr-2"
                                                        data-toggle="modal"
                                                        data-target="#modal-edit-function-file"
                                                        title="{{ __('custom.edit') }}"
                                                        onclick="loadFunctionFileData('{{ route('admin.advisory-boards.file.edit', ['item' => $item, 'file' => $file]) }}', '{{ $file->locale }}');">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                        </div>

                                        <div class="col-auto">
                                            @can('delete', $item)
                                                @if(!$file->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                       data-target="#modal-delete-file"
                                                       data-resource-id="{{ $file->id }}"
                                                       data-resource-delete-url="{{ route('admin.advisory-boards.file.delete', ['item' => $item, 'file' => $file]) }}"
                                                       data-toggle="tooltip"
                                                       title="{{__('custom.delete')}}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('restore', $item)
                                                @if($file->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                       data-target="#modal-restore-resource"
                                                       data-resource-id="{{ $item->id }}"
                                                       data-resource-restore-url="{{ route('admin.advisory-boards.file.restore', ['item' => $item, 'file' => $file]) }}"
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
    </div>
</div>
