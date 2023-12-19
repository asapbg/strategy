@php
    $view_mode ??= false;
@endphp

<style>
    .accordion-collapse.collapse.ui-accordion-content.ui-corner-bottom.ui-helper-reset.ui-widget-content.ui-accordion-content-active {
        height: auto !important;
    }
</style>

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
                    <div class="row">
                        <div class="col-auto">
                            <form action="{{ route('admin.advisory-boards.sections.order', ['item' => $item]) }}"
                                  name="CUSTOM_SECTIONS_ORDER" method="POST" class="d-none">
                                @csrf
                                <button type="submit" class="btn btn-success" name="order" onclick="this.classList.add('disabled')">
                                    {{ __('custom.save') . ' ' . Str::lower(__('custom.order')) }}
                                </button>
                            </form>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-create-section">
                                <i class="fa fa-plus mr-3"></i>
                                {{ __('custom.add') . ' ' . trans_choice('custom.section', 1) }}
                            </button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#custom' }}"
                       class="btn btn-info">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="accordion connectedSortable" id="accordionCustomSections">
                    @if(isset($item->customSections) && $item->customSections->count() > 0)
                        @foreach($item->customSections as $key => $section)
                            <div class="accordion-item" data-section-id="{{ $section->id }}">
                                <h2 class="accordion-header" id="heading{{ $key }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                                            aria-controls="collapse{{ $key }}">
                                        {{ $section->title }}
                                    </button>
                                </h2>

                                <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading{{ $key }}"
                                     data-bs-parent="#accordionCustomSections">
                                    <div class="accordion-body">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <div class="row justify-content-end align-items-center">
                                                        @if(!$view_mode)
                                                            <div class="col-auto">
                                                                <div class="row">
                                                                    <div class="col-auto">
                                                                        @can('update', $item)
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-info mr-2"
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
                                                                                <button href="javascript:;"
                                                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                                                   data-target="#modal-delete-section"
                                                                                   data-resource-id="{{ $section->id }}"
                                                                                   data-resource-delete-url="{{ route('admin.advisory-boards.sections.delete', ['item' => $item, 'section' => $section]) }}"
                                                                                   data-toggle="tooltip"
                                                                                   title="{{__('custom.delete')}}">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endcan

                                                                        @can('restore', $item)
                                                                            @if($section->deleted_at)
                                                                                <button href="javascript:;"
                                                                                   class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                                                   data-target="#modal-restore-section"
                                                                                   data-resource-id="{{ $section->id }}"
                                                                                   data-resource-restore-url="{{ route('admin.advisory-boards.sections.restore', ['item' => $item, 'section' => $section]) }}"
                                                                                   data-toggle="tooltip"
                                                                                   title="{{__('custom.restore')}}">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endcan
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            {!! $section->body!!}
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
        function setSectionFileObjectId(id) {
            CUSTOM_FILE.querySelector('input[name=object_id]').value = id;
        }

        $(function () {
            $("#accordionCustomSections")
                .accordion({
                    collapsible: true,
                    header: "> div > h2",
                    dropOnEmpty: true,
                    autoHeight: true,
                    active: false
                })
                .sortable({
                    axis: "y",
                    stop: function () {
                        stop1 = true;
                    },
                    connectWith: ".connectedSortable",
                    helper: "clone",
                    handle: "h2",
                    update: function (event, ui) {
                        const sortable = $(this)[0];
                        const items = sortable.querySelectorAll('.accordion-item');
                        let order = [];

                        for (let item of items) {
                            order.push(item.getAttribute('data-section-id'));
                        }

                        CUSTOM_SECTIONS_ORDER.querySelector('button[type=submit]').value = JSON.stringify(order);
                        CUSTOM_SECTIONS_ORDER.classList.remove('d-none');
                    }
                });
        });
    </script>
@endpush
