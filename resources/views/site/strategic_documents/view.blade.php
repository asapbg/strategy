@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Стратегически документи - вътрешна страница')
@section('content')
    @can('update',  $strategicDocument)
        <div class="row edit-consultation m-0" style="top: 17.5%;">
            <div class="col-md-12 text-end">
                <a href="{{ route('admin.strategic_documents.edit', [$strategicDocument->id]) }}"
                   class="btn btn-sm btn-primary main-color mt-2">
                    <i class="fas fa-pen me-2 main-color"></i>{{ trans_choice('custom.edit_document', 1) }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-3">{{ trans_choice('custom.information', 1) }}</h2>
                </div>
                <div class="col-md-12 text-старт">
                    <button class="btn btn-primary  main-color">
                        <i class="fa-solid fa-download main-color me-2"></i>{{ trans_choice('custom.export', 1) }}</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-square-rss text-warning me-2"></i>{{ trans_choice('custom.rss_subscribe', 1) }}</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-envelope me-2 main-color"></i>{{ trans_choice('custom.subscribe', 1) }}</button>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 d-flex align-items-center">
                    <h3 class="mb-2 fs-4">{{ trans_choice('custom.policy_area_single', 1) }} :</h3>
                    <div class="mb-2 ms-2 fs-4">
                        @can('view',  $strategicDocument->policyArea?->name)
                            <a href="{{ route('admin.nomenclature.strategic_document_type.edit', [$strategicDocument->policyArea?->id]) }}"
                               class="main-color text-decoration-none">
                                <span class="obj-icon-info me-2">
                                <i class="bi bi-mortarboard-fill me-1 main-color" title="Тип консултация"></i>{{ $strategicDocument->policyArea?->name }} </span>
                            </a>
                        @else
                            <a href="#"
                               class="main-color text-decoration-none">
                                <span class="obj-icon-info me-2">
                                <i class="bi bi-mortarboard-fill me-1 main-color" title="Тип консултация"></i>{{ $strategicDocument->policyArea?->name }} </span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.strategic_document_type', 1) }}</h3>
                    @can('view',  $strategicDocument->documentType)
                        <a href="{{ route('admin.nomenclature.strategic_document_type.edit', [$strategicDocument->documentType?->id]) }}"
                           class="main-color text-decoration-none fs-18">
                         <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->documentType->name }} </span>
                        </a>
                    @else
                        <a href="#"
                           class="main-color text-decoration-none fs-18">
                         <span class="obj-icon-info me-2">
                            <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->documentType->name }}
                         </span>
                        </a>
                    @endcan
                </div>

                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.document_to', 1) }} </h3>
                    @if($strategicDocument->parent_document_id)
                        <a href="{{ route('strategy-document.view', [$strategicDocument->parent_document_id]) }}"
                           class="main-color text-decoration-none fs-18">
                         <span class="obj-icon-info me-2">
                            <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->parentDocument?->title }}</span>
                        </a>
                        @can('update',  $strategicDocument)
                                <a href="{{ route('admin.strategic_documents.edit', [$strategicDocument->parentDocument->id]) }}">
                                    <i class="fas fa-pen me-2 main-color"></i>
                                </a>
                        @endcan
                    @else
                        <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ trans_choice('custom.strategic_document_link_missing', 1) }}</span>
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.accepted_date', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18" title="Тип консултация"></i>{{ \Carbon\Carbon::parse($strategicDocument->document_date_accepted)->format('Y-m-d') }}</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.date_expiring', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>@if($strategicDocument->document_date_expiring)
                                {{ \Carbon\Carbon::parse($strategicDocument->document_date_expiring)->format('Y-m-d') }}
                            @else
                                {{ trans_choice('custom.date_indefinite_name', 1) }}
                            @endif</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.acceptment_act', 1) }}</h3>
                    <div class="mb-2 fs-18">
                        <span>{{ $strategicDocument->strategicActType->name }}</span>
                        @if ($strategicDocument->pris?->doc_num && $strategicDocument->pris?->published_at)
                            <a href="{{ route('pris.view', [$strategicDocument->pris?->id]) }}" class="main-color text-decoration-none">
                                {{ $strategicDocument->pris?->name . ' №/' . $strategicDocument->pris?->doc_num . '/' . $strategicDocument->pris?->doc_date }}
                            </a>
                        @else
                            <a href="{{ $strategicDocument->strategic_act_link }}" class="main-color text-decoration-none">
                                {{ $strategicDocument->strategic_act_number }}
                            </a>
                        @endif
                        <span>{{ trans_choice('custom.for', 1) }}</span>
                            <a href="#" class="main-color text-decoration-none">
                                {{ $strategicDocument->acceptActInstitution->name }}
                            </a>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.category', 1) }}</h3>
                    @can('view',  $strategicDocument->documentLevel)
                        <a href="{{ route('admin.nomenclature.strategic_document_level.edit', [$strategicDocument->documentLevel?->id]) }}" class="main-color text-decoration-none">
                            <span class="obj-icon-info me-2">
                            <i class="fa-solid fa-arrow-right-to-bracket main-color me-2 fs-18" title="Тип консултация"></i>{{ $strategicDocument->documentLevel?->name }}</span>
                        </a>
                    @else
                        <a href="#" class="main-color text-decoration-none">
                            <span class="obj-icon-info me-2">
                                <i class="fa-solid fa-arrow-right-to-bracket main-color me-2 fs-18" title="Тип консултация"></i>
                            </span>
                            {{ $strategicDocument->documentLevel?->name }}
                        </a>
                    @endcan
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.public_consultation_link', 1) }}</h3>
                        @if ($strategicDocument->public_consultation_id)
                            <a href="{{ route('public_consultation.view', [$strategicDocument->public_consultation_id]) }}" class="main-color text-decoration-none fs-18">
                               <span class="obj-icon-info me-2">
                                <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->publicConsultation?->title }}</span>
                            </a>
                        @else
                            <a href="#" class="main-color text-decoration-none fs-18">
                                <span class="obj-icon-info me-2">
                                <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>{{ trans_choice('custom.public_consultation_link_missing', 1) }}</span>
                            </a>
                        @endif
                </div>
                @if (! $strategicDocument->link_to_monitorstat)
                    <div class="col-md-4">
                        <h3 class="mb-2 fs-5">{{ trans_choice('custom.link_to_monitorstrat', 1) }}</h3>
                        <a href="{{ $strategicDocument->link_to_monitorstat  }}" class="main-color text-decoration-none fs-18">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>{{ trans_choice('custom.link_to_monitorstrat', 1) }}</span>
                        </a>
                    </div>
                @endif
            </div>


            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <h3 class="mb-3">{{ trans_choice('custom.description', 1) }}</h3>
                    <div class="str-doc-info">
                        {!! $strategicDocument->description !!}
                    </div>
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <!-- При клик върху основния документ, трябва да се визуализира в модал и да има изтегляне.
                Пример: https://strategy.asapbg.com/templates/public_consultations_view от файловете Пакет основни документи -->
                <h3 class="mb-3">{{ trans_choice('custom.main_document', 1) }}</h3>
                <div class="col-md-12">
                    @php
                        $iconMapping = [
                            'application/pdf' => 'fa-regular fa-file-pdf main-color me-2 fs-5',
                            'application/msword' => 'fa-regular fa-file-word main-color me-2 fs-5',
                            'application/vnd.ms-excel' => 'fa-regular fa-file-excel main-color me-2 fs-5',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-regular fa-file-excel main-color me-2 fs-5',
                        ];
                    @endphp
                    <ul class="list-group list-group-flush">
                        @if(!$mainDocument)
                            <li class="list-group-item">
                            </li>
                        @else
                            <li class="list-group-item">
                                <a href="#" class="main-color text-decoration-none preview-file-modal2" type="button" data-file="{{ $mainDocument->id }}" data-url="{{ route('strategy-document.preview.file_modal', ['id' => $mainDocument->id]) }}">
                                @php
                                    $fileExtension = $mainDocument->content_type;
                                    $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';
                                @endphp
                                    <i class="{{ $iconClass }}"></i>{{ $mainDocument->display_name }}
                                        <!--
                                        <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $mainDocument->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $mainDocument->id]) }}"> {{ __('custom.preview') }}</button>
                                        -->
                                    </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <h3 class="mb-3">{{ trans_choice('custom.documents', 1) }}</h3>
                <div class="col-md-12">
                    <ul class=" tab nav nav-tabs mb-3" id="myTab">
                        <li class="nav-item pb-0">
                            <a href="#table-view" class="nav-link tablinks active" data-toggle="tab">{{ trans_choice('custom.applied_documents', 1) }}</a>
                        </li>
                        <li class="nav-item pb-0">
                            <a href="#tree-view" class="nav-link tablinks" data-toggle="tab">{{ trans_choice('custom.reports_and_docs', 1) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="table-view">
                            <ul class="list-group list-group-flush">
                                @foreach($strategicDocumentFiles as $strategicDocumentFile)
                                    <li class="list-group-item">
                                        <a href="#" class="main-color text-decoration-none preview-file-modal2" type="button" data-file="{{ $strategicDocumentFile['id'] }}" data-url="{{ route('strategy-document.preview.file_modal', ['id' => $strategicDocumentFile['id']]) }}">
                                            <i class="{{ $strategicDocumentFile['icon'] }}"></i>{{ $strategicDocumentFile['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="tree-view">
                            <ul class="list-group list-group-flush">
                                @foreach($reportsAndDocs as $document)
                                    <li class="list-group-item">
                                        <a href="#" class="main-color text-decoration-none preview-file-modal2" type="button" data-file="{{ $document->id }}" data-url="{{ route('strategy-document.preview.file_modal', ['id' => $document->id]) }}">
                                            <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>{{ $document->display_name }}
                                            <span class="fw-bold">&#123;</span>
                                            <span class="valid-date fw-bold"> Публикувано: {{ $document->created_at->format('d.m.Y') }}</span>
                                            <span> /</span>
                                            <span class="valid-date fw-bold"> Валидност: {{ $document->valid_at ?: 'Няма валидност' }} </span>
                                            <span> /</span>
                                            <span class="str-doc-type fw-bold">{{ $document->documentType->name }}</span>
                                            <span class="fw-bold">&#125;</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <style>
        .strategicDocumentClass .modal-dialog {
            width: 80%;
            max-width: 80% !important;
        }

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
            $(document).ready(function() {
                $('#myTab a').on('click', function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
                $('.preview-file-modal2').on('click', function (e) {
                    let fileId = $(this).data('file');
                    let previewUrl = $(this).data('url');
                    console.log(previewUrl);
                    let titleTxt = '';
                    let continueTxt = 'Изтегляне';
                    let cancelBtnTxt = 'Отказ';
                    const buttonId = id="strategicFileId_" +fileId;
                    new MyModal({
                        title: titleTxt,
                        footer: '<button id="strategicFileId_' + fileId + '" class="btn btn-primary">' + continueTxt + '</button>' +
                            '<button id="stategicFieldCancelButton' + buttonId + '" class="btn btn-danger" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                        body: `<embed src="${previewUrl}" type="application/pdf" width="100%" height="800px" />`,
                        customClass: 'strategicDocumentClass',
                    });
            });
        });
        $('body').on('click', '[id^="strategicFileId_"]', function(event) {
            event.preventDefault();
            const fileId = this.id.replace('strategicFileId_', '');
            window.location.href = `/strategy-document/download-file/${fileId}`;
        });
        $('body').on('click', '#stategicFieldCancelButton', function(event) {
            event.stopPropagation();
        });

        $(document).ready(function() {
            fileData = {!! json_encode($fileData) !!};
            const fileTree = $("#fileTree");
            fileTree.jstree({
                "plugins": ["themes"],
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
            })
        });
    </script>


@endpush
