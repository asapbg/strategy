@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.section', 2) }}</h3>
                    </div>

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_sections', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show_deleted_sections"
                                       {{ $checked }} onchange="toggleDeleted(this, 'custom', 'show_deleted_sections')">
                                <label class="custom-control-label"
                                       for="show_deleted_sections">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)

                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-create-section">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . trans_choice('custom.section', 1) }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#custom' }}"
                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div id="accordion">
                    @if(isset($sections) && $sections->count() > 0)
                        @foreach($sections as $key => $section)
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-10">
                                                <a data-toggle="collapse" href="#collapse{{$key}}"
                                                   aria-expanded="true">
                                                    {{ $section->title }}
                                                </a>
                                            </div>

                                            @if(!$view_mode)
                                                <div class="col-auto">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            @can('update', $item)
                                                                <button type="button"
                                                                        class="btn btn-sm btn-warning mr-2"
                                                                        data-toggle="modal"
                                                                        data-target="#modal-edit-section"
                                                                        title="{{ __('custom.edit') }}"
                                                                        onclick="loadSectionData('{{ route('admin.advisory-boards.sections.edit', ['item' => $item, 'section' => $section]) }}');">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                            @endcan
                                                        </div>

                                                        <div class="col-auto">
                                                            @can('delete', $item)
                                                                @if(!$section->deleted_at)
                                                                    <a href="javascript:;"
                                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                                       data-target="#modal-delete-section"
                                                                       data-resource-id="{{ $section->id }}"
                                                                       data-resource-delete-url="{{ route('admin.advisory-boards.sections.delete', ['item' => $item, 'section' => $section]) }}"
                                                                       data-toggle="tooltip"
                                                                       title="{{__('custom.delete')}}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            @endcan

                                                            @can('restore', $item)
                                                                @if($section->deleted_at)
                                                                    <a href="javascript:;"
                                                                       class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                                       data-target="#modal-restore-section"
                                                                       data-resource-id="{{ $section->id }}"
                                                                       data-resource-restore-url="{{ route('admin.advisory-boards.sections.restore', ['item' => $item, 'section' => $section]) }}"
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
                                        </div>
                                    </h4>
                                </div>

                                @php $show = $key === 0 ? 'show' : '' @endphp
                                <div id="collapse{{ $key }}" class="collapse {{ $show }}" data-parent="#accordion">
                                    <div class="card-body">
                                        {!! $section->body !!}
                                    </div>

                                    @if(isset($section->files) && $section->files->count() > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <hr/>
                                            </div>
                                        </div>

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
                                                                    @php $checked = request()->get('show_deleted_custom_files', '0') == '1' ? 'checked' : '' @endphp
                                                                    <input type="checkbox" class="custom-control-input"
                                                                           id="show_deleted_custom_files"
                                                                           {{ $checked }} onchange="toggleDeletedFiles(this, 'custom')">
                                                                    <label class="custom-control-label"
                                                                           for="show_deleted_custom_files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-auto">
                                                    @if(!$view_mode)
                                                        <button type="button" class="btn btn-success"
                                                                data-toggle="modal"
                                                                data-target="#modal-add-custom-file"
                                                                onclick="setSectionFileObjectId('{{ $section->id }}')">
                                                            <i class="fa fa-plus mr-3"></i>
                                                            {{ __('custom.add') . ' ' . __('custom.file') }}
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    @include('admin.partial.files_table', ['files' => $section->files, 'item' => $item, 'view_mode' => $view_mode])
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function setMeetingFileObjectId(id) {
            CUSTOM_FILE.querySelector('input[name=object_id]').value = id;
        }
    </script>
@endpush
