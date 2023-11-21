<form class="row" action="{{ route('admin.strategic_documents.file.upload') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="strategicDocumentId" name="id" value="{{ $item->id ?? 0 }}">
    @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsProperties(),'field' => 'display_name', 'required' => true])

    <div class="col-md-3">
        <div class="form-group form-group-sm">
            <label for="valid_at" class="col-sm-12 control-label">{{ __('custom.valid_at') }} <span class="required">*</span> </label>
            <div class="col-12">
                <input value="{{ old('valid_at', '') }}" class="form-control form-control-sm datepicker @error('valid_at') is-invalid @enderror" type="text" name="valid_at">
                @error('valid_at')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-group-sm">
            <label class="col-sm-12 control-label" for="strategic_document_type">{{ trans_choice('custom.strategic_document_type', 1) }}<span class="required">*</span></label>
            <div class="col-12">
                <select id="strategic_document_type" name="strategic_document_type" class="form-control form-control-sm select2 @error('strategic_document_type'){{ 'is-invalid' }}@enderror">
                    <option value="" @if(old('strategic_document_type', '') == '') selected @endif>---</option>
                    @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                        @foreach($strategicDocumentTypes as $row)
                            <option value="{{ $row->id }}" @if(old('strategic_document_type', 0) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('strategic_document_type')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <!--
    <div class="col-md-4">
        <div class="form-group form-group-sm">
            <label for="file" class="col-sm-12 control-label">{{ __('custom.select_file') }} <span class="required">*</span> </label>
            <div class="col-12">
                <input class="form-control form-control-sm @error('file') is-invalid @enderror" id="file" type="file" name="file">
                @error('file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    -->
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
                <input type="checkbox" id="visible_in_report" name="visible_in_report" class="checkbox" value="1" @if (old('visible_in_report',0)) checked @endif>
                {{ __('custom.visible_in_report') }}
            </label>
        </div>
    </div>
    @foreach(config('available_languages') as $lang)
        @php($validationRules = \App\Enums\StrategicDocumentFileEnum::validationRules($lang['code']))
        @php($fieldName = 'file_strategic_documents_'.$lang['code'])
        <div class="col-md-6 mb-3">
            <label for="{{ $fieldName }}" class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))<span class="required">*</span>@endif </label>
            <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror" id="{{ $fieldName }}" type="file" name="{{ $fieldName }}">
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

@if($item->files)
    <table class="table table-az-admin table-sm table-hover table-bordered mt-4">
        <thead>
            <tr>
                <th class="bg-primary">{{ __('custom.name') }}</th>
                <th class="bg-primary">{{ trans_choice('custom.strategic_document_types', 1) }}</th>
                <th class="bg-primary">{{ __('custom.valid_at') }}</th>
                <th class="bg-primary"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($item->files as $f)
            <tr>
                <td class="pt-4 bl-primary-2">{{ $f->display_name }}</td>
                <td class="pt-4">{{ $f->documentType->name }}</td>
                <td class="pt-4">{{ $f->valid_at }}</td>
                <td class="pt-4">
                    <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.strategic_documents.file.download', $f) }}">
                        <i class="fas fa-download me-1" role="button"
                           data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                    </a>
                    <a href="javascript:;"
                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                       data-target="#modal-delete-resource"
                       data-resource-id="{{ $f->id }}"
                       data-resource-name="{{ "$f->display_name" }}"
                       data-resource-delete-url="{{route('admin.strategic_documents.file.delete',$f->id)}}"
                       data-toggle="tooltip"
                       title="{{__('custom.deletion')}}">
                        <i class="fa fa-trash"></i>
                    </a>
{{--                    <a class="btn btn-sm btn-danger" type="button" href="">--}}
{{--                        <i class="fas fa-trash me-1" role="button"--}}
{{--                           data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>--}}
{{--                    </a>--}}
                </td>
            </tr>
            <tr>
                <td colspan="5" class="edit-file-fields">
                    <form action="{{ route('admin.strategic_documents.file.update', ['id' => $f->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $f->id }}">
                        @method('PUT')
                        <div class="row @if(!$loop->last) pb-4 @endif">
                            @include('admin.partial.edit_field_translate', ['item' => $f, 'translatableFields' => \App\Models\StrategicDocumentFile::translationFieldsProperties(),'field' => 'file_info', 'required' => false])
                            @error('error_'.$f->id)
                                <div class="col-12 text-danger mb-1">{{ $message }}</div>
                            @enderror
                            <div class="col-12">
                                <button id="save" type="submit" class="btn btn-success w-100">{{ __('custom.save') }}</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
@includeIf('modals.delete-resource', ['resource' => trans_choice('custom.files', 1)])

@push('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <style>
        #fileTree,#fileTreeEn .jstree-node {
            padding-left: 30px;
            padding-top: 7px;
        }
        #fileTree,#fileTreeEn .jstree-themeicon {
            font-size: 20px; /* Adjust the size according to your preference */
        }
        #fileTree,#fileTreeEn .jstree-anchor {
            font-size: 20px; /* Adjust the size according to your preference */
        }
    </style>
@endpush

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
    <script type="text/javascript">
        fileData = {!! json_encode($fileData) !!};
        fileDataEn = {!! json_encode($fileDataEn) !!};

        $(document).ready(function() {
            const fileTree = $("#fileTree");
            const saveTree = $('#saveTree');

            function initializeFileTree(treeSelector, data) {
                console.log(treeSelector);
                $(treeSelector).jstree({
                    "plugins": ["dnd", "themes"],
                    'core': {
                        'check_callback': true,
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
                }).on('ready.jstree', function() {
                    $(treeSelector).jstree('open_all');
                }).on('move_node.jstree', function() {
                    $(treeSelector).jstree('open_all');
                });
            }

            initializeFileTree("#fileTree", {!! json_encode($fileData) !!});
            initializeFileTree("#fileTreeEn", {!! json_encode($fileDataEn) !!});

            initializeSaveTree("#saveTreeEn", "#fileTreeEn");

            initializeSaveTree("#saveTree", "#fileTree");
            function initializeSaveTree(saveButtonSelector, treeSelector) {
                $(saveButtonSelector).on('click', function() {
                    const currentTreeState = $(treeSelector).jstree(true).get_json('#', { flat: false });
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
                        success: function(response) {
                            location.reload(1);
                        },
                        error: function(error) {
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
            /*
            fileTree.jstree({
                "plugins": ["dnd", "themes"],
                'core': {
                    'check_callback': true,
                    'data': fileData,
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
            }).on('ready.jstree', function() {
                fileTree.jstree('open_all');
            }).on('move_node.jstree', function() {
                fileTree.jstree('open_all');
            });
            */

            /*
            saveTree.on('click', function() {
                const currentTreeState = $('#fileTree').jstree(true).get_json('#', { flat: false });
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
                    success: function(response) {
                        location.reload(1);
                    },
                    error: function(error) {
                    }
                });
            });
            */


            /*
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
            */
        });
    </script>
@endpush

