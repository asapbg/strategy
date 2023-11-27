<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.section', 1) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_FUNCTION.submit();">
                    {{ __('custom.save') . ' ' . trans_choice('custom.section', 1) }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <form name="ADVISORY_BOARD_FUNCTION" action="{{ route('admin.advisory-boards.function.store', $item) }}"
                      method="post">
                    @csrf

                    @foreach(config('available_languages') as $lang)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                    ({{ Str::upper($lang['code']) }})</label>

                                @php
                                    $description = $function?->translations->count() === 2 ?
                                        $function->translations->first(fn($row) => $row->locale == $lang['code'])->description :
                                        old('description_' . $lang['code'], '');
                                @endphp

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}">
                                    {{ $description }}
                                </textarea>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <hr/>
            </div>
        </div>

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.files', 2) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal-add-function-file">
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
                    @if(isset($files) && $files->count() > 0)
                        @foreach($files as $file)
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
                                                        class="btn btn-sm btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#modal-edit-function-file"
                                                        title="{{ __('custom.edit') }}"
                                                        onclick="loadFunctionFileData('{{ route('admin.advisory-boards.function.file.edit', ['item' => $item, 'file' => $file]) }}', '{{ $file->locale }}');">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                        </div>

                                        <div class="col-auto">
                                            @can('delete', $item)
                                                @if(!$item->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                       data-target="#modal-delete-resource"
                                                       data-resource-id="{{ $item->id }}"
                                                       data-resource-delete-url="{{ route('admin.advisory-boards.delete', $item) }}"
                                                       data-toggle="tooltip"
                                                       title="{{__('custom.delete')}}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('restore', $item)
                                                @if($item->deleted_at)
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                       data-target="#modal-restore-resource"
                                                       data-resource-id="{{ $item->id }}"
                                                       data-resource-restore-url="{{ route('admin.advisory-boards.restore', $item) }}"
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
