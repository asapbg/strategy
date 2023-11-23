@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Стратегически документи - вътрешна страница')
@section('content')
    <div class="row edit-consultation m-0" style="top: 17.5%;">
        <div class="col-md-12 text-end">
            <button class="btn btn-sm btn-primary main-color mt-2">
                <i class="fas fa-pen me-2 main-color"></i>{{ trans_choice('custom.edit_document', 1) }}</button>
        </div>
    </div>

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
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->documentType->name }} </span>
                        </a>
                    @endcan
                </div>

                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.document_to', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>Линк и заглавие на родителски стратегически документ</span>
                    </a>
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.accepted_date', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18" title="Тип консултация"></i>{{ $strategicDocument->document_date_accepted }}</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.date_expiring', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>{{ $strategicDocument->document_date_expiring }}</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">{{ trans_choice('custom.acceptment_act', 1) }}</h3>
                    <div class="mb-2 fs-18">
                        <span>{{ $strategicDocument->strategicActType->name }}</span>
                        @if ($strategicDocument->pris?->doc_num)
                            @can('view',  $strategicDocument->documentType)
                                <a href="{{ route('admin.pris.edit', [$strategicDocument->documentType?->id]) }}" class="main-color text-decoration-none">
                                    {{ $strategicDocument->pris?->doc_num }}
                                </a>
                            @else
                                <a href="#" class="main-color text-decoration-none">
                                    {{ $strategicDocument->pris?->doc_num }}
                                </a>
                            @endcan
                        @else
                            <a href="{{ $strategicDocument->strategic_act_link }}" class="main-color text-decoration-none">
                                {{ $strategicDocument->strategic_act_number }}
                            </a>
                        @endif
                        <span>{{ trans_choice('custom.for', 1) }}</span>
                        @can('view',  $strategicDocument->acceptActInstitution)
                            <a href="{{ route('admin.nomenclature.authority_accepting_strategic.edit', [$strategicDocument->acceptActInstitution?->id]) }}" class="main-color text-decoration-none">
                                {{ $strategicDocument->acceptActInstitution->name }}
                            </a>
                        @else
                            <a href="#" class="main-color text-decoration-none">
                                {{ $strategicDocument->acceptActInstitution->name }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <!--
                    Трети ред: категория, линк към обществена консултация (ако има), линк към Мониторстат (ако има).
                -->
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

                    @can('viewAny',  $strategicDocument->publicConsultation)
                        <a href="{{ route('admin.consultations.public_consultations.edit', [$strategicDocument->publicConsultation?->id]) }}" class="main-color text-decoration-none fs-18">
                           <span class="obj-icon-info me-2">
                            <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>{{ trans_choice('custom.link_to_oc', 1) }}</span>
                        </a>
                    @else
                        <a href="#" class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                            <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>{{ trans_choice('custom.link_to_oc', 1) }}</span>
                        </a>
                    @endcan

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
                                <a href="#" class="main-color text-decoration-none preview-file-modal2" type="button" data-file="{{ $mainDocument->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $mainDocument->id]) }}">
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

                    <div class="modal fade" id="theModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog file-display" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Документ 1</h5>
                                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="fa-solid fa-xmark main-color fs-5"></i></span>
                                    </a>
                                </div>
                                <div class="modal-body">

                                    <div class="col-md-12">
                                        <p> С проекта на наредба се предлагат: I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407). Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи: „в приложение II раздел II.1 се заменя със следното: „II.1. RID </p>
                                        <p>
                                            <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е уместно „RID договаряща държава" се заменя с „държава членка“.</strong>
                                        </p>
                                        <p> Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в параграф единствен от проекта на наредба. Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за прилагането на новата уредба. На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на транспорта, информационните технологии и съобщенията и на Портала за обществени консултации на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени предложения или становища в 14-дневен срок от публикуването им. </p>
                                        <p>
                                            <strong> Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г., която вече е в сила за Република България. </strong>
                                        </p>
                                        <p> В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007 г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010 г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм., бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр. 36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр. 39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група. </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Изтегляне</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <h3 class="mb-3">{{ trans_choice('custom.documents', 1) }}</h3>
                <div class="col-md-12">
                    <ul class=" tab nav nav-tabs mb-3" id="myTab">
                        <li class="nav-item pb-0">
                            <a href="#table-view" class="nav-link tablinks active" data-bs-toggle="tab">{{ trans_choice('custom.applied_documents', 1) }}</a>
                        </li>
                        <li class="nav-item pb-0">
                            <a href="#tree-view" class="nav-link tablinks" data-bs-toggle="tab">{{ trans_choice('custom.reports_and_docs', 1) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="table-view">
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
                            <!--
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от Протокол №
                                        13 от
                                        заседанието на Министерския съвет на 24 февруари 2021 година
                                        <span class="fw-bold">&#123;</span>
                                        <span class="valid-date fw-bold"> Публикувано 10.11.2023</span>
                                        <span> /</span>
                                        <span class="valid-date fw-bold"> Валидност 10.11.2023 </span>
                                        <span> /</span>
                                        <span class="str-doc-type fw-bold"> Програма </span>
                                        <span class="fw-bold">&#125;</span>
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Стратегическа рамка за
                                        развитие на образованието, обучението и ученето в Република България
                                        <span class="fw-bold">&#123;</span>
                                        <span class="valid-date fw-bold"> Публикувано 21.11.2023</span>
                                        <span> /</span>
                                        <span class="valid-date fw-bold"> Валидност 04.02.2024 </span>
                                        <span> /</span>
                                        <span class="str-doc-type fw-bold"> Стратегия </span>
                                        <span class="fw-bold">&#125;</span>
                                    </a>
                                </li>
                            </ul>
                            -->
                        </div>
                        <div class="tab-pane fade" id="tree-view">
                            <ul class="list-group list-group-flush">
                                @foreach($reportsAndDocs as $document)
                                    <li class="list-group-item">
                                        <a href="{{ $document->path }}" class="main-color text-decoration-none">
                                            <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>{{ $document->display_name }}
                                            <span class="fw-bold">&#123;</span>
                                            <span class="valid-date fw-bold"> Публикувано {{ $document->created_at->format('d.m.Y') }}</span>
                                            <span> /</span>
                                            <span class="valid-date fw-bold"> Валидност {{ $document->valid_at ?? 'Няма валидност' }} </span>
                                            <span> /</span>
                                            <!--
                                            $document->strategicDocument->documentType->name
                                            -->
                                            <span class="str-doc-type fw-bold">{{ $document->documentType->name }}</span>
                                            <span class="fw-bold">&#125;</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <!--
                            <div id="fileTree"></div>
                            -->
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
                    let titleTxt = '';
                    let continueTxt = 'Изтегляне';
                    let cancelBtnTxt = 'Отказ';
                    const buttonId = id="strategicFileId_" +fileId;
                    new MyModal({
                        title: titleTxt,
                        footer: '<button id="strategicFileId_' + fileId + '" class="btn btn-sm btn-primary ms-3">' + continueTxt + '</button>' +
                            '<button id="' + buttonId + '" class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                        body: `<embed src="${previewUrl}" type="application/pdf" width="100%" height="800px" />`,
                        customClass: 'strategicDocumentClass',
                    });
            });
        });
        $('body').on('click', '[id^="strategicFileId_"]', function() {
            const fileId = this.id.replace('strategicFileId_', '');
            window.location.href = `/admin/strategic-documents/download-file/${fileId}`;
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
