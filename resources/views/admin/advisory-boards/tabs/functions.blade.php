@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3 class="m-0">{{ trans_choice('custom.function', 1) }}</h3>
                    </div>

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_functions', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input" id="show_deleted_functions"
                                       {{ $checked }} onchange="toggleDeleted(this, 'functions', 'show_deleted_functions')"
                                >
                                <label class="custom-control-label" for="show_deleted_functions">
                                    {{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create-working-program">
                    {{ __('custom.add') . ' ' . trans_choice('custom.function', 1) }}
                </button>
                <button onclick="goToArchive(2)" role="tab" aria-controls="archive" class="btn btn-warning" aria-selected="false">
                    {{ __('custom.belongs_to') . ' ' . __('custom.archive') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div id="accordion">
                    @if(isset($item->advisoryFunctions) && $item->advisoryFunctions?->count() > 0)
                        @foreach($item->advisoryFunctions as $key => $working_program)
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-10">
                                                <a data-toggle="collapse" href="#collapse{{$key}}"
                                                   aria-expanded="true">
                                                    {{ trans_choice('custom.function', 1) . ' #' . $key + 1 }}
                                                    @if($working_program->working_year)
                                                        {{ __('custom.for') . ' ' .  \Carbon\Carbon::parse($working_program->working_year)->format('Y') . __('custom.year_short') }}
                                                    @endif
                                                </a>
                                            </div>

                                            @if(!$view_mode)
                                                <div class="col-auto">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            @can('update', $item)
                                                                <button type="button"
                                                                        class="btn btn-sm btn-info mr-2"
                                                                        data-toggle="modal"
                                                                        data-target="#modal-edit-function"
                                                                        title="{{ __('custom.edit') }}"
                                                                        onclick="loadFunctionData('{{ route('admin.advisory-boards.function.edit', ['item' => $item, 'working_program' => $working_program]) }}');">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                            @endcan
                                                        </div>

                                                        <div class="col-auto">
                                                            @can('delete', $item)
                                                                @if(!$working_program->deleted_at)
                                                                    <a href="javascript:;"
                                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                                       data-target="#modal-remove-working-program"
                                                                       data-resource-id="{{ $working_program->id }}"
                                                                       data-resource-delete-url="{{ route('admin.advisory-boards.function.delete', ['item' => $item, 'working_program' => $working_program]) }}"
                                                                       data-toggle="tooltip"
                                                                       title="{{__('custom.delete')}}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            @endcan

                                                            @can('restore', $item)
                                                                @if($working_program->deleted_at)
                                                                    <a href="javascript:;"
                                                                       class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                                       data-target="#modal-restore-section"
                                                                       data-resource-id="{{ $working_program->id }}"
                                                                       data-resource-restore-url="{{ route('admin.advisory-boards.function.restore', ['item' => $item, 'working_program' => $working_program]) }}"
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
                                        {!! $working_program->description !!}
                                    </div>

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
                                                                @php $checked = request()->get('show_deleted_functions_files', '0') == '1' ? 'checked' : '' @endphp
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="show_deleted_functions_files"
                                                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'functions')">
                                                                <label class="custom-control-label"
                                                                       for="show_deleted_functions_files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                @if(!$view_mode)
                                                    <button type="button" class="btn btn-success"
                                                            data-toggle="modal"
                                                            data-target="#modal-add-function-file"
                                                            onclick="setFunctionFileObjectId('{{ $working_program->id }}')">
                                                        <i class="fa fa-plus mr-3"></i>
                                                        {{ __('custom.add') . ' ' . __('custom.file') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                @include('admin.partial.files_table', ['files' => $working_program->files, 'item' => $item, 'view_mode' => $view_mode])
                                            </div>
                                        </div>
                                    </div>
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
        function setFunctionFileObjectId(id) {
            FUNCTIONS_FILE.querySelector('input[name=object_id]').value = id;
        }
    </script>
@endpush
