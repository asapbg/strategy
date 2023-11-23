@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Стратегически документи - вътрешна страница')


@section('content')
    <div class="row edit-consultation m-0" style="top: 17.5%;">
        <div class="col-md-12 text-end">
            <button class="btn btn-sm btn-primary main-color mt-2">
                <i class="fas fa-pen me-2 main-color"></i>Редактиране на документ</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-3">Информация</h2>
                </div>
                <div class="col-md-12 text-старт">
                    <button class="btn btn-primary  main-color">
                        <i class="fa-solid fa-download main-color me-2"></i>Експорт</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-square-rss text-warning me-2"></i>RSS Абониране</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18"> {{ trans_choice('custom.accepted_date', 1) }}
                    </h3>
                    <span class="obj-icon-info">
                    <i class="far fa-calendar me-2 main-color" title="Дата на откриване"></i> {{ $strategicDocument->publicConsultation?->open_from }} {{ trans_choice('custom.date_small', 1) }}
                </span>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18"> {{ trans_choice('custom.validation_date', 1) }}
                    </h3>
                    <span class="obj-icon-info">
                    <i class="far fa-calendar-check me-2 main-color" title="Дата на приключване"></i>{{ $strategicDocument->publicConsultation?->open_to }} {{ trans_choice('custom.date_small', 1) }} </span>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.category', 1) }}</h3>
                    <a href="{{ route('admin.nomenclature.strategic_document_type.edit', [$strategicDocument->documentType?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2 main-color"
                           title="Област на политика"></i>{{ $strategicDocument->documentType->name }}</span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.policy_area', 1) }}</h3>
                    <a href="{{ route('admin.nomenclature.policy_area.edit', [$strategicDocument->policyArea?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="bi bi-mortarboard-fill me-2 main-color"
                           title="Номер на консултация "></i>{{ $strategicDocument->policyArea->name }}</span>
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.strategic_document_type', 1) }}</h3>
                    <a href="{{ route('admin.nomenclature.strategic_document_type.edit', [$strategicDocument->documentType?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color" title="Тип консултация"></i>{{ $strategicDocument->documentType->name }} </span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.strategic_act_type', 1) }}</h3>
                    <a href="{{ route('admin.nomenclature.strategic_act_type.edit', [$strategicDocument->strategicActType?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-solid fa-file-lines me-2 main-color" title="Вносител"></i>{{ $strategicDocument->strategicActType->name }}
                    </span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.accept_act_institution_type', 1) }}</h3>
                    <a href="{{ route('admin.strategic_documents.institutions.edit', [$strategicDocument->acceptActInstitution?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-school me-2 main-color" title="История"></i>{{ $strategicDocument->acceptActInstitution->name }}</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.unique_consultation_number', 1) }}</h3>
                    <a href="{{ route('admin.consultations.public_consultations.edit', [$strategicDocument->publicConsultation?->id]) }}" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-hashtag me-2 main-color" title="История"></i>{{ $strategicDocument->publicConsultation?->id }}</span>
                    </a>
                </div>
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
                <h3 class="mb-3">{{ trans_choice('custom.associated_documents', 1) }}</h3>
                <div class="col-md-12">
                    <ul class=" tab nav nav-tabs mb-3" id="myTab">
                        <li class="nav-item pb-0">
                            <a href="#table-view" class="nav-link tablinks active" data-bs-toggle="tab">{{ trans_choice('custom.table_view', 1) }}</a>
                        </li>
                        <li class="nav-item pb-0">
                            <a href="#tree-view" class="nav-link tablinks" data-bs-toggle="tab">{{ trans_choice('custom.tree_view', 1) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="table-view">
                            @php
                                $iconMapping = [
                                    'application/pdf' => 'fa-regular fa-file-pdf main-color me-2 fs-5',
                                    'application/msword' => 'fa-regular fa-file-word main-color me-2 fs-5',
                                    'application/vnd.ms-excel' => 'fa-regular fa-file-excel main-color me-2 fs-5',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-regular fa-file-excel main-color me-2 fs-5',
                                ];
                            @endphp
                            <ul class="list-group list-group-flush">
                                @foreach($strategicDocumentFiles as $strategicDocumentFile)
                                    @php
                                        $fileExtension = $strategicDocumentFile->content_type;
                                        $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file'; // Default to a generic file icon if no mapping is found
                                    @endphp
                                    <li class="list-group-item">
                                        <a href="#" class="main-color text-decoration-none">
                                            <i class="{{ $iconClass }}"></i>{{ $strategicDocumentFile->display_name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="tree-view">
                            <div id="fileTree"></div>

                            <!--
                            <ul class="tree list-unstyled">
                                <li class="tree-item">
                                    <a href="#" class="trigger text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от Протокол №
                                        13 от заседанието на Министерския съвет на 24 февруари 2021 година (публ. 08.03.2021
                                        г.)</a>
                                </li>
                                <ul class="tree list-unstyled">
                                    <li class="tree-item">
                                        <a href="#" class="trigger text-decoration-none"><i
                                                class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Стратегическа рамка
                                            за развитие на образованието, обучението и ученето в Република България (2021 -
                                            2030) (публ. 11.03.2021 г.)</a>

                                        <ul class="tree-parent open list-unstyled">
                                            <li class="tree-item view">
                                                <a href="#" class="text-decoration-none"> <i
                                                        class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение
                                                    от Протокол № 13 от заседанието на Министерския съвет на 22 март 2023
                                                    година (публ. на 29.03.2023 г.)</a>
                                                <ul class="tree-parent open list-unstyled">
                                                    <li class="tree-item view">
                                                        <a href="#" class="text-decoration-none"> <i
                                                                class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>План
                                                            за действие до 2024 към Стратегическа рамка за развитие на
                                                            образованието, обучението и ученето в Република България (2021 –
                                                            2030) (публ. на 29.03.2023 г.)</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="tree-item controllers">
                                        <a href="#" class="trigger text-decoration-none"><i
                                                class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Национална стратегия
                                            на Република България за равенство, приобщаване и участие на ромите (2021 -
                                            2030), приета с Решение № 278 от 5 май 2022 година (публ. 11.11.2022 г.)</a>
                                    </li>
                                </ul>
                            </ul>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="hori-timeline px-1" dir="ltr">
                <h3 class="mb-3">История</h3>
                <div class="timeline">
                    <ul class="timeline events">
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Включване на проекта</h5>
                            <p class="mb-2 fw-bold">12.05.2023</p>
                            <p> Tова събитие описва запис на акт в ЗП или ОП.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Начало на обществената консултация</h5>
                            <p class="mb-2 fw-bold">20.05.2023</p>
                            <p> Визуализира се „Начало на консултацията“.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Промяна на файл </h5>
                            <p class="mb-2 fw-bold">25.05.2023</p>
                            <p> Промяна на файл от консултацията.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приключване на консултацията</h5>
                            <p class="text-muted mb-2 fw-bold ">01.06.2023</p>
                            <p class="text-muted">Край на консултацията</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Справка за получените предложения</h5>
                            <p class="text-muted mb-2 fw-bold">15.06.2023</p>
                            <p class="text-muted">Справка или съобщение.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приемане на акта от Министерския съвет</h5>
                            <p class="text-muted mb-2 fw-bold text-muted">18.06.2023</p>
                            <p class="text-muted">Окончателен акт.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18"> Представяне на законопроекта</h5>
                            <p class="text-muted mb-2 fw-bold ">25.06.2023</p>
                            <p class="text-muted">Развито в обхвата на текущата поръчка.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
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
