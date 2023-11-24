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
                                    <button class="nav-link tablinks @if($loop->first) active @endif" id="{{ 't'.$m }}-tab" data-toggle="tab" data-target="#{{ 't'.$m }}" type="button" role="tab" aria-controls="{{ 't'.$m }}" aria-selected="true">{{ __('site.'.(int)$m) }}</button>
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
                                                                                        @if($r->dsc_id == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
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
                                                                                                                {!! fileIcon($f->content_type) !!} {{ !empty($f->{'description_'.$f->locale}) ? $f->{'description_'.$f->locale}.' ('.strtoupper($f->locale).')' : $f->filename }} | {{ displayDate($f->created_at) }}
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
                </div>

        </div>
    </section>
@endsection
