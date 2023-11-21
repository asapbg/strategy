@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <style>
        .tablinks.active {
            color: #21659e !important;
        }

        .tablinks {
            color: #333 !important;
            font-size: 22px !important;
        }
    </style>
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    <button class="btn btn-sm btn-primary main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</button>
                </div>
            </div>
        </div>
    </section>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                @include('site.pris.side_menu')

                <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">
                    <ul class="tab nav nav-tabs mb-3" id="myTab" role="tablist">
                        @if(isset($months) && sizeof($months))
                            @foreach($months as $m)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tablinks @if($loop->first) active @endif" id="{{ 't'.$m }}-tab" data-bs-toggle="tab" data-bs-target="#{{ 't'.$m }}" type="button" role="tab" aria-controls="{{ 't'.$m }}" aria-selected="true">{{ __('site.'.(int)$m) }}</button>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @if(isset($months) && sizeof($months))
                            @foreach($months as $m)
                                <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ 't'.$m }}" role="tabpanel" aria-labelledby="{{ 't'.$m }}-tab">
                                    <div class="accordion" id="accordion{{ $m }}">
                                        @if(isset($data) && sizeof($data))
                                            @foreach($data as $row)
                                                @if(str_contains($row->month, $m))
                                                    @php($rowData = json_decode($row->columns))
                                                    @if($rowData)
{{--                                                        @dd($rowData)--}}
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseOne" aria-expanded="@if($loop->first) true @else false @endif" aria-controls="collapseOne">
                                                                    {{ $rowData[0]->value }}
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="headingOne"
                                                                 data-bs-parent="#accordion{{ $m }}">
                                                                <div class="accordion-body">
                                                                    <div class="custom-card p-3 mb-5">
                                                                        @php($cnt = 1)
                                                                        @foreach($rowData as $r)
                                                                            @if($cnt == 1)
                                                                                <div class="row mb-3 mt-1 ">
                                                                            @endif
                                                                                <div class="col-md-6">
                                                                                    <p class="fw-bold fs-18 mb-1">{{ $r->label }}</p>
                                                                                    <p>
                                                                                        @if($r->dsc_id == \App\Http\Controllers\Admin\Consultations\OperationalProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
                                                                                            {{ $institutions[(int)$r->value] ?? '---' }}
                                                                                        @elseif($r->type == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                                            {!! $r->value !!}
                                                                                        @elseif($r->type == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                            {{ $r->value ? __('custom.yes') : __('custom.no') }}
                                                                                        @else
                                                                                            {{ $r->value }}
                                                                                        @endif
                                                                                    </p>
                                                                                </div>
                                                                            @if($cnt == 2 || $loop->last)
                                                                                    <hr class="custom-hr">
                                                                                </div>
                                                                            @endif
                                                                            @if($loop->last)
                                                                                <div class="row mb-3">
                                                                                    @if($item->rowFiles->count())
                                                                                        @foreach([\App\Enums\DocTypesEnum::PC_IMPACT_EVALUATION->value, \App\Enums\DocTypesEnum::PC_IMPACT_EVALUATION_OPINION->value] as $doc)
                                                                                            @foreach($item->rowFiles as $f)
                                                                                                @if($f->pivot->row_num == $row->row_num && $f->pivot->row_month == $row->month && $f->doc_type == $doc)
                                                                                                    <div class="col-md-6 ">
                                                                                                        <p class="fw-bold fs-18 mb-1">{{ __('custom.public_consultation.doc_type.'.$doc) }}</p>
                                                                                                        <p class="mb-0">
                                                                                                            <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
                                                                                                                {!! fileIcon($f->content_type) !!} {{ __('custom.version_short').' '.$f->version }} | {{ displayDate($f->created_at) }}
                                                                                                            </a>
{{--                                                                                                            <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                            @php($cnt = $cnt == 2 ? 1 : $cnt + 1)
                                                                        @endforeach

    {{--                                                                    <hr class="custom-hr">--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach

                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>


{{--                    <ul class=" tab nav nav-tabs mb-3">--}}
{{--                        <li class="nav-item pb-0">--}}
{{--                            <a class="nav-link tablinks" aria-current="page" href="#" onclick="openCity(event, 'July')" id="defaultOpen">Юли</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item pb-0">--}}
{{--                            <a class="nav-link tablinks" href="#" onclick="openCity(event, 'August')">Август</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item pb-0">--}}
{{--                            <a class="nav-link tablinks" href="#" onclick="openCity(event, 'September')">Септември</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}

{{--                    <div id="July" class="tabcontent">--}}
{{--                        <div class="accordion" id="accordionExample">--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingOne">--}}
{{--                                    <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                        Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}


{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingTwo">--}}
{{--                                    <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
{{--                                        ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}
{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}


{{--                    <div id="August" class="tabcontent">--}}


{{--                        <div class="accordion" id="accordionExample">--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingOne">--}}
{{--                                    <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                        АВГУСТ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}


{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        АВГУСТ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingTwo">--}}
{{--                                    <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
{{--                                        АВГУСТ - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}
{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        АВГУСТ - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}


{{--                    <div id="September" class="tabcontent">--}}
{{--                        <div class="accordion" id="accordionExample">--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingOne">--}}
{{--                                    <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                        СЕПТВЕМВРИ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}


{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        СЕПТВЕМВРИ  - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="accordion-item">--}}
{{--                                <h2 class="accordion-header" id="headingTwo">--}}
{{--                                    <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"--}}
{{--                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
{{--                                        СЕПТВЕМВРИ  - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия--}}
{{--                                    </button>--}}
{{--                                </h2>--}}
{{--                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"--}}
{{--                                     data-bs-parent="#accordionExample">--}}
{{--                                    <div class="accordion-body">--}}
{{--                                        <div class="custom-card py-4 px-3 mb-5">--}}
{{--                                            <div class="row mb-3 mt-1 ">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Наименование на законопроекта--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        СЕПТВЕМВРИ  - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Вносител--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Агенция за държавна финансова инспекция--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Цели, основни положения и очаквани резултати--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        С проекта на ЗИД на КСО се извършват--}}
{{--                                                        промени в регламентацията на допълнителното--}}
{{--                                                        пенсионно осигуряване, като част от тях са--}}
{{--                                                        свързани с изпълнение на мерките за засилване--}}
{{--                                                        на надзора и регулациите в областта на--}}
{{--                                                        небанковия финансов сектор, залегнали в--}}
{{--                                                        Националната програма за реформи.--}}

{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Изготвяне на цялостна оценка на въздействието (да/не)--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на публикуване за обществени консултации--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на изпращане за предварително съгласуване--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Да / Mярка № 87--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Месец на внасяне в Министерския съвет--}}

{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        юли 2023г.--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}

{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Необходими промени в други закони--}}
{{--                                                    </p>--}}

{{--                                                    <p>--}}
{{--                                                        Не--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                                <hr class="custom-hr">--}}
{{--                                            </div>--}}


{{--                                            <div class="row mb-3">--}}
{{--                                                <div class="col-md-6 ">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Оценка на въздействието--}}

{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <p class="fw-bold fs-18 mb-1">--}}
{{--                                                        Становище--}}
{{--                                                    </p>--}}

{{--                                                    <p class="mb-0">--}}
{{--                                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>--}}
{{--                                                    </p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <hr class="custom-hr">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}

                </div>

        </div>
    </section>
@endsection
