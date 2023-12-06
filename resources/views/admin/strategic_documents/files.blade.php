<div class="tab-pane" id="files" role="tabpanel" aria-labelledby="files-tab">
    <div class="card card-secondary p-0 mt-4">
        <div class="card-body">
            <form class="row" action="{{ route('admin.strategic_documents.file.upload') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="strategicDocumentId" name="id" value="{{ $item->id ?? 0 }}">
                @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsProperties(),'field' => 'display_name', 'required' => true])

                <div class="col-md-3">
                    <div class="form-group form-group-sm">
                        <label for="valid_at" class="col-sm-12 control-label">{{ __('custom.valid_at') }} <span
                                class="required">*</span> </label>
                        <div class="col-12">
                            <input id="valid_at_files" value="{{ old('valid_at', '') }}"
                                   class="form-control form-control-sm datepicker @error('valid_at') is-invalid @enderror"
                                   type="text" name="valid_at">
                            @error('valid_at')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-group-sm">
                        <label for="date_expring_indefinite" class="col-sm-12 control-label">{{ __('custom.date_expring_indefinite') }}
                            <span class="required">*</span> </label>
                        <div class="form-check">
                            <input type="hidden" name="date_valid_indefinite_files" value="0">
                            <input type="checkbox" id="date_valid_indefinite_files" name="date_valid_indefinite_files"
                                   class="form-check-input"
                                   value="1" {{ $item->expiration_date === null ? 'checked' : '' }}>
                            <label class="form-check-label" for="unlimited_date_expiring">
                                {{ __('custom.date_expring_indefinite') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-12 control-label"
                               for="strategic_document_type">{{ trans_choice('custom.strategic_document_type', 1) }}
                            <span class="required">*</span></label>
                        <div class="col-12">
                            <select id="strategic_document_type" name="strategic_document_type_file_id"
                                    class="form-control form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">
                                <option value="" @if(old('strategic_document_type_id', '') == '') selected @endif>---
                                </option>
                                @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                    @foreach($strategicDocumentTypes as $row)
                                        <option value="{{ $row->id }}"
                                                @if(old('strategic_document_type_id', 0) == $row->id) selected
                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('strategic_document_type_file_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12"></div>
                @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsProperties(),'field' => 'file_info', 'required' => false])
                <!--
    <div class="col-md-4">
        <div class="form-group form-group-sm">
            <label class="col-sm-12 control-label" for="ord">{{ __('custom.order') }}</label>
            <div class="col-12">
                <input type="number" id="ord" name="ord" class="form-control form-control-sm" value="{{ old('ord', 0) }}">
            </div>
            @error('ord')
                <span class="text-danger">{{ $message }}</span>
            @enderror
                </div>
            </div>
-->
                <div class="col-md-8">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-12 control-label" for="visible_in_report"><br>
                            <input type="checkbox" id="visible_in_report" name="visible_in_report" class="checkbox"
                                   value="1" @if (old('visible_in_report',0)) checked @endif>
                            {{ __('custom.visible_in_report') }}
                        </label>
                    </div>
                </div>
                @foreach(config('available_languages') as $lang)
                    @php($validationRules = \App\Enums\StrategicDocumentFileEnum::validationRules($lang['code']))
                    @php($fieldName = 'file_strategic_documents_'.$lang['code'])
                    <div class="col-md-6 mb-3">
                        <label for="{{ $fieldName }}"
                               class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))
                                <span class="required">*</span>
                            @endif </label>
                        <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror"
                               id="{{ $fieldName }}" type="file" name="{{ $fieldName }}">
                        @error($fieldName)
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach

                <div class="col-12">
                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                </div>
            </form>

            <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">{{ trans_choice('custom.files_hierarchy_bg', 2) }}</h5>
            <div class="row">
                <div class="col-12">
                    <br>
                    <div id="fileTree">
                    </div>
                    <div class="col-12">
                        <div class="col-6"></div>
                        <br>
                        <button id="saveTree" class="btn btn-success">{{ __('custom.save') }}</button>
                    </div>
                </div>
            </div>
            <!--
            <h5 class="mt-4 bg-primary py-2 px-4 w-100 rounded-1">{{ trans_choice('custom.files_hierarchy_en', 2) }}</h5>
            <div class="row">
                <div class="col-12">
                    <br>
                    <div id="fileTreeEn">
                    </div>

                    <div class="col-12">
                        <div class="col-6"></div>
                        <br>
                        <button id="saveTreeEn" class="btn btn-success">{{ __('custom.save') }}</button>
                    </div>
                </div>
            </div>
            -->

        @if($item->files)
                <table id="fileTable" class="table table-az-admin table-sm table-hover table-bordered mt-4">
                    <thead>
                    <!--
                     <label class="col-sm-12 control-label" for="visible_in_report"><br>
                            <input type="checkbox" id="visible_in_report" name="visible_in_report" class="checkbox"
                                   value="1" @if (old('visible_in_report',0)) checked @endif>
                            {{ __('custom.visible_in_report') }}
                    </label>
                    -->
                    <tr>
                        <th class="bg-primary">{{ __('custom.name') }}</th>
                        <th class="bg-primary">{{ trans_choice('custom.strategic_document_types', 1) }}</th>
                        <th class="bg-primary">{{ __('custom.valid_at') }}</th>
                        <th class="bg-primary">{{ __('custom.visible_in_report') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->files as $f)
                        <tr id="fileRow_head_{{ $f->id }}">
                            <td class="pt-4 bl-primary-2"> {{ $f->display_name }} </td>
                            <td class="pt-4 bl-primary-2">
                                {{ $f->documentType->name }}
                            </td>
                            <td class="pt-4">
                                @if (!$f->valid_at)
                                    {{ __('custom.date_expring_indefinite') }}
                                    @else
                                    {{ $f->valid_at }}
                                @endif
                            </td>

                            <td class="pt-4">
                                @if ($f->visible_in_report == 0)
                                    {{ __('custom.no') }}
                                    @else
                                    {{ __('custom.yes') }}
                                @endif
                            </td>

                        </tr>
                        <tr id="fileRow_body_{{ $f->id }}">
                            <form action="{{ route('admin.strategic_documents.file.update', ['id' => $f->id]) }}"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                <td colspan="5" class="edit-file-fields">
                                    <input type="hidden" name="id" value="{{ $f->id }}">
                                    @method('PUT')
                                    <div class="row @if(!$loop->last) pb-4 @endif">
                                        @include('admin.partial.edit_field_translate', ['item' => $f, 'value' => optional($f)->display_name ?? '', 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsPropertiesFileEdit(),'field' => 'display_name_file_edit', 'required' => true])
                                        @error('error_'.$f->id)
                                            <div class="text-danger mb-1">{{ $message }}</div>
                                        @enderror
                                        @include('admin.partial.edit_field_translate', ['item' => $f, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsPropertiesFileEdit(),'field' => 'file_info_file_edit', 'required' => false])

                                        <div class="col-12">
                                            <label class="col-sm-12 control-label"
                                                   for="strategic_document_type">{{ trans_choice('custom.strategic_document_type', 1) }}
                                                <span class="required">*</span></label>
                                            <select id="strategic_document_type_file_edit_{{ $f->id }}" name="strategic_document_type_file"
                                                    class="form-control form-control-sm select2 @error('strategic_document_type'){{ 'is-invalid' }}@enderror">
                                                @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                                    @foreach($strategicDocumentTypes as $row)
                                                        <option value="{{ $row->id }}"
                                                                @if(old('strategic_document_type_id', optional($f)->strategic_document_type_id) == $row->id) selected
                                                                @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-3">
                                            <label for="valid_at_files" class="col-sm-12 control-label">{{ __('custom.valid_at') }} <span
                                                    class="required">*</span> </label>
                                            <input id="valid_at_files_edit_{{ $f->id }}" value="{{ old('valid_at', $f->valid_at) }}"
                                                   class="form-control form-control-sm datepicker @error('valid_at_files') is-invalid @enderror"
                                                   type="text" name="valid_at_files">
                                            @error('valid_at_files')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-3">
                                            <label for="date_valid_indefinite_files" class="col-sm-12 control-label">{{ __('custom.date_expring_indefinite') }}
                                                <span class="required">*</span> </label>
                                            <div class="form-check">
                                                <input type="hidden" name="date_valid_indefinite_files" value="0">
                                                <input type="checkbox" data-id="{{ $f->id }}" id="date_valid_indefinite_files_edit_{{$f->id}}" name="date_valid_indefinite_files"
                                                       class="form-check-input"
                                                       value="1" {{ $f->expiration_date === null ? 'checked' : '' }}>
                                                <label class="form-check-label" for="unlimited_date_expiring">
                                                    {{ __('custom.date_expring_indefinite') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-sm-12 control-label" for="visible_in_report"> {{ __('custom.visible_in_report') }} </label>
                                                <input type="checkbox" id="visible_in_report" name="visible_in_report_files" class="checkbox"
                                                       value="1" @if (old('visible_in_report_files', optional($f)->visible_in_report) == 1) checked @endif>
                                                {{ __('custom.visible_in_report') }}
                                        </div>


                                            @foreach(config('available_languages') as $lang)
                                                @php($validationRules = \App\Enums\StrategicDocumentFileEnum::validationRules($lang['code']))
                                                @php($fieldName = 'file_strategic_documents_'.$lang['code'])
                                                <div class="col-md-6 mb-3">
                                                    <label for="{{ $fieldName }}"
                                                           class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))
                                                            <span class="required">*</span>
                                                        @endif </label>
                                                    <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror"
                                                           id="{{ $fieldName }}" type="file" name="{{ $fieldName }}">
                                                    @error($fieldName)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            @if(isset($f->parentFile?->versions))
                                                <div class="col-md-3 mb-3">
                                                    <a class="mr-3"
                                                       href="{{ route('admin.strategic_documents.file.download', $f->parentFile) }}"
                                                       target="_blank" title="{{ __('custom.download') }}">
                                                        {!! fileIcon($f->parentFile->content_type) !!} {{ $f->parentFile->document_display_name }}
                                                        - {{ __('custom.'.$f->parentFile->locale) }}
                                                        | {{ __('custom.version_short').' '.$f->parentFile->version }}
                                                        | {{ displayDate($f->parentFile->created_at) }}
                                                        | {{ $f->parentFile->user ? $f->parentFile->user->fullName() : '' }}
                                                    </a>
                                                </div>
                                                @php(
                                                    $currentLocal = app()->getLocale()
                                                )
                                                @foreach ($f->parentFile?->versions as $fileVersion)
                                                    @if ($currentLocal != $fileVersion->locale)
                                                        @continue
                                                    @endif

                                                    @if ($fileVersion->id == $f->id)
                                                        @continue
                                                    @endif
                                                    <div class="col-md-3 mb-3">
                                                        <a class="mr-3"
                                                           href="{{ route('admin.strategic_documents.file.download', $fileVersion) }}"
                                                           target="_blank" title="{{ __('custom.download') }}">
                                                            {!! fileIcon($fileVersion->content_type) !!} {{ $fileVersion->document_display_name }}
                                                            - {{ __('custom.'.$fileVersion->locale) }}
                                                            | {{ __('custom.version_short').' '.$fileVersion->version }}
                                                            | {{ displayDate($fileVersion->created_at) }}
                                                            | {{ $f->parentFile->user ? $fileVersion->user->fullName() : '' }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="save" type="submit"
                                                    class="btn btn-success w-100">{{ __('custom.save') }}</button>
                                        </div>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>
@endif
@includeIf('modals.delete-resource', ['resource' => trans_choice('custom.files', 1)])

@push('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css"/>
    <style>
        #fileTree, #fileTreeEn .jstree-node {
            padding-left: 30px;
            padding-top: 7px;
        }

        #fileTree, #fileTreeEn .jstree-themeicon {
            font-size: 17px; /* Adjust the size according to your preference */
        }

        #fileTree, #fileTreeEn .jstree-anchor {
            font-size: 17px; /* Adjust the size according to your preference */
        }

        .edit-button, .delete-button {
            cursor: pointer;
            margin-left: 5px; /* Adjust the margin as needed */
        }

        /* Style the icon within the "Press" and "Delete" buttons */
        .edit-button i, .delete-button i {
            margin-right: 5px; /* Adjust the margin as needed */
        }
    </style>
@endpush

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
    <script type="text/javascript">
        fileData = {!! json_encode($fileData) !!};
        fileDataEn = {!! json_encode($fileDataEn) !!};
        mainFileId = {!! json_encode($mainFile->id) !!};
        const hasErrors = @json(session('hasErrorsFromFileTab'));
        $(document).ready(function () {
            $('#strategic_document_type_file_edit').select2();
            /*
            const dateValidAt = $('#valid_at');
            const dateValidAtMain = $('#valid_at_main');
            const dateValidAtFiles = $('#valid_at_files');
            const dateExpiringCheckbox = $('#date_valid_indefinite');
            const dateInfiniteFilesCheckbox = $('#date_valid_indefinite_files');
            const dateInfiniteEditCheckbox = $('#date_valid_indefinite_files_edit');
            const dateValidAtFileEdit = $('#valid_at_files_edit');

            dateValidAtMain.on('change', function () {
                dateExpiringCheckbox.prop('checked', false);
            });

            dateValidAt.on('change', function () {
                if (!dateExpiringCheckbox.is(':checked')) {
                    dateExpiringCheckbox.prop('checked', $(this).val() === '').trigger('change');
                }
            })

            dateExpiringCheckbox.on('change', function () {
                if ($(this).is(':checked')) {
                    if (dateValidAtFileEdit.val() !== '') {
                        dateValidAtFileEdit.val('').trigger('change');
                    }
                }
            })

            dateInfiniteEditCheckbox.on('change', function() {
                if ($(this).is(':checked')) {
                    if (dateValidAt.val() !== '') {
                        dateValidAt.val('').trigger('change');
                    }
                }
            });
            */
            $('[id^=fileRow_head_]').hide();
            $('[id^=fileRow_body_]').hide();
            const fileTree = $("#fileTree");
            const saveTree = $('#saveTree');

            function initializeFileTree(treeSelector, data) {
                $(treeSelector).jstree({
                    "plugins": ["dnd", "themes"],
                    'core': {
                        //'check_callback': true,
                        'check_callback': function (operation, node, node_parent, node_position, more) {
                            // Restrict to parent
                            if (operation === "move_node") {
                                return node_parent.id == mainFileId;
                            }
                            return true;
                        },
                        'data': data,
                        'themes': {
                            'dots': true,
                            'responsive': true
                        }
                    },
                    "types": {
                        "default": {
                            "icon": "glyphicon glyphicon-flash"
                        },
                        "demo": {
                            "icon": "glyphicon glyphicon-ok"
                        }
                    },
                }).on('ready.jstree', function () {
                    $(treeSelector).jstree('open_all');
                }).on('move_node.jstree', function () {
                    $(treeSelector).jstree('open_all');
                });
            }

            initializeFileTree("#fileTree", {!! json_encode($fileData) !!});
            initializeFileTree("#fileTreeEn", {!! json_encode($fileDataEn) !!});
            initializeSaveTree("#saveTreeEn", "#fileTreeEn");
            initializeSaveTree("#saveTree", "#fileTree");

            function initializeSaveTree(saveButtonSelector, treeSelector) {
                $(saveButtonSelector).on('click', function () {
                    const currentTreeState = $(treeSelector).jstree(true).get_json('#', {flat: false});
                    const filesStructure = extractFilesStructure(currentTreeState);
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    const strategicDocumentId = $('#strategicDocumentId').val();

                    $.ajax({
                        url: '/admin/strategic-documents/save-tree',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: csrfToken,
                            filesStructure: filesStructure,
                            strategicDocumentId: strategicDocumentId,
                        },
                        success: function (response) {
                            location.reload(1);
                        },
                        error: function (error) {
                            // Handle error
                        }
                    });
                });

                function extractFilesStructure(treeState) {
                    let filesStructure = [];

                    function traverse(node) {
                        let file = {
                            id: node.id,
                            text: node.text,
                            icon: node.icon,
                            children: [],
                        };
                        if (node.children && node.children.length > 0) {
                            for (let i = 0; i < node.children.length; i++) {
                                file.children.push(traverse(node.children[i]));
                            }
                        }
                        return file;
                    }

                    for (let i = 0; i < treeState.length; i++) {
                        filesStructure.push(traverse(treeState[i]));
                    }

                    return filesStructure;
                }
            }

            $('#fileTree, #fileTreeEn').on("select_node.jstree", function (e, data) {
                if ($(data.event.target).hasClass('fas fa-edit')) {
                    const fileId = data.node.id;

                    $('[id^=fileRow_head_]').hide();
                    $('[id^=fileRow_body_]').hide();
                    $('#fileRow_head_' + fileId).toggle();
                    $('#fileRow_body_' + fileId).toggle();
                }

                if ($(data.event.target).hasClass('fas fa-download')) {
                    const fileId = data.node.id;
                    window.location.href = `/admin/strategic-documents/download-file/${fileId}`;
                }

                if ($(data.event.target).hasClass('fas fa-trash')) {
                    const fileId = data.node.id;
                    const deleteModal = $('#modal-delete-resource');
                    const nodeName = $('<div/>').html(data.node.text).text();
                    const deleteForm = deleteModal.find('form');
                    const deleteUrl = '/admin/strategic-documents/delete-file/' + fileId;
                    //deleteModal.find('.modal-body').text(nodeName);
                    deleteForm.attr('action', deleteUrl);
                    deleteForm.attr('method', "GET");
                    $('#resource_id').val(fileId);
                    deleteModal.show();
                    deleteModal.modal('show');
                }
            });
        });
    </script>
@endpush

