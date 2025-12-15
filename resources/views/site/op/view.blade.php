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
                    @can('update', $item)
                    <a href="{{ route('admin.consultations.operational_programs.edit', $item) }}" target="_blank" class="btn btn-sm btn-primary main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </section>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                @include('site.pris.side_menu')

                <div class="col-lg-10  right-side-content py-5">
                    <div class="col-12 mb-2">
                        <input type="hidden" id="subscribe_model" value="App\Models\Consultations\OperationalProgram">
                        <input type="hidden" id="subscribe_model_id" value="{{ $item->id }}">
                        @includeIf('site.partial.subscribe-buttons', ['no_rss' => true])
                    </div>
{{--                    @if(isset($pageTopContent) && !empty($pageTopContent->value))--}}
{{--                        <div class="col-12 mb-5">--}}
{{--                            {!! $pageTopContent->value !!}--}}
{{--                        </div>--}}
{{--                    @endif--}}

                    @if($item->filesLocale->count())
                        <div class="col-12 mb-4">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ __('custom.files') }}</h3>
                                <ul class="list-group list-group-flush">
                                    @foreach($item->filesLocale as $f)
                                        <li class="list-group-item">
                                            @php($file_name = fileIcon($f->content_type)." ".!empty($f->{'description_'.$f->locale}) ? $f->{'description_'.$f->locale} : $f->filename." | ".displayDate($f->created_at))
                                            @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <ul class="tab nav nav-tabs mb-3" id="myTab" role="tablist">
                        @if(isset($months) && sizeof($months))
                            @foreach($months as $m)
                                <li class="nav-item" role="presentation">
                                    <a href="#{{ 't'.$m }}" class="nav-link tablinks @if($loop->first) active @endif" id="{{ 't'.$m }}-tab" data-toggle="tab" data-target="#{{ 't'.$m }}" type="button" role="tab" aria-controls="{{ 't'.$m }}" aria-selected="true">{{ __('site.'.(int)$m) }}</a>
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
                                            @foreach($data as $k => $row)
                                                @if(str_contains($row->month, $m))
                                                    @php($actionPlan = false)
                                                    @php($rowData = json_decode($row->columns))
                                                    @if($rowData)
                                                        @php(usort($rowData, function ($a, $b) { return $a->ord > $b->ord; }))
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="heading{{ $m.'_'.$k }}">
                                                                <button class="accordion-button text-dark fs-18 fw-600" type="button" data-toggle="collapse"
                                                                        data-target="#collapse{{ $m.'_'.$k }}" aria-expanded="@if($loop->first) true @else false @endif" aria-controls="collapse{{ $m.'_'.$k }}">
                                                                    @if(isset($rowData[1]) && $rowData[1]->dsc_id == (int)config('lp_op_programs.op_ds_col_number_id')){{ __('custom.number_symbol').' '.$rowData[1]->value.' | ' }}@endif {{ html_entity_decode($rowData[0]->value) }}
                                                                </button>
                                                            </h2>
                                                            <div id="collapse{{ $m.'_'.$k }}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $m.'_'.$k }}"
                                                                 data-bs-parent="#accordion{{ $m }}">
                                                                <div class="accordion-body">
                                                                    <div class="custom-card p-3 mb-5">
{{--                                                                        @php($cnt = 1)--}}
                                                                        @foreach($rowData as $r)
                                                                            @if($r->dsc_id == config('lp_op_programs.op_ds_col_include_in_action_plan_id') && $r->value)
                                                                                @php($actionPlan = true)
                                                                            @endif
{{--                                                                            @if($cnt == 1)--}}
{{--                                                                                <div class="row mb-3 mt-1 ">--}}
{{--                                                                            @endif--}}
                                                                                @if(!($r->dsc_id == config('lp_op_programs.op_ds_col_include_action_plan_number_id')) || $actionPlan)
                                                                                    <div class="row mb-3 mt-1 ">
                                                                                        <div class="col-12">
                                                                                            <p class="fw-bold fs-18 mb-1">{{ $r->label }}</p>
                                                                                                @if($r->dsc_id == config('lp_op_programs.op_ds_col_institution_id'))
                                                                                                    @if(!empty($row->name_institutions))
                                                                                                        @php($nameInstitutions = json_decode($row->name_institutions))
                                                                                                        @if(sizeof($nameInstitutions))
                                                                                                            <p>
                                                                                                                @foreach($nameInstitutions as $name)
                                                                                                                    @if(!$loop->first){{ ',' }}<br>@endif{{ $name }}
                                                                                                                @endforeach
                                                                                                            </p>
                                                                                                        @else
                                                                                                            {{ '---' }}
                                                                                                        @endif
                                                                                                    @else
                                                                                                        {{ '---' }}
                                                                                                    @endif
                                                                                                @elseif($r->type == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                                                    {!! html_entity_decode($r->value) !!}
                                                                                                @elseif($r->type == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                                    <p>{{ $r->value ? __('custom.yes') : __('custom.no') }}</p>
                                                                                                @else
                                                                                                    <p>{{ html_entity_decode($r->value) }}</p>
                                                                                                @endif
                                                                                        </div>
                                                                                        <hr class="custom-hr">
                                                                                    </div>
                                                                                @endif
                                                                            @if($loop->last)
                                                                                <div class="row mb-3">
                                                                                    @if($item->rowFilesLocale->count())
                                                                                        @foreach($item->rowFilesLocale as $f)
                                                                                            @if($f->pivot->row_num == $row->row_num && $f->pivot->row_month == $row->month)
                                                                                                <div class="col-md-12 mb-2">
                                                                                                    <p class="mb-0">
                                                                                                        @php($file_name = fileIcon($f->content_type)." ".!empty($f->{'description_'.$f->locale}) ? $f->{'description_'.$f->locale} : $f->filename." | ".displayDate($f->created_at))
                                                                                                        @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
                                                                                                    </p>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
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
