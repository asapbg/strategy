@extends('layouts.site', ['fullwidth' => true])

@section('content')

<div class="row">
    @include('site.strategic_documents.side_menu')

    <div class="col-lg-10 right-side-content py-5" id="listContainer">
        @can('update', $strategicDocument)
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    <a href="{{ route('admin.strategic_documents.edit', [$strategicDocument->id]) }}"
                       class="btn btn-sm btn-primary main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>{{ trans_choice('custom.edit_document', 1) }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="row">
            @if (isset($pageTopContent) && !empty($pageTopContent->value))
                <div class="col-12 my-5">
                    {!! $pageTopContent->value !!}
                </div>
            @endif
            <div class="col-lg-12 py-5">
                <div class="row mb-4 action-btn-wrapper">
                    <div class="col-md-12">
                        <h2 class="mb-3">{{ trans_choice('custom.information', 1) }}</h2>
                    </div>
                    <div class="col-md-12 text-start">
                        <button class="btn btn-primary  main-color">
                            <i class="fa-solid fa-download main-color me-2"></i>{{ trans_choice('custom.export', 1) }}</button>
                        <input type="hidden" id="subscribe_model" value="App\Models\StrategicDocument">
                        <input type="hidden" id="subscribe_model_id" value="{{ $strategicDocument->id }}">
                        @includeIf('site.partial.subscribe-buttons')
{{--                        <button class="btn rss-sub main-color">--}}
{{--                            <i class="fas fa-square-rss text-warning me-2"></i>{{ trans_choice('custom.rss_subscribe', 1) }}</button>--}}
{{--                        <button class="btn rss-sub main-color">--}}
{{--                            <i class="fas fa-envelope me-2 main-color"></i>{{ trans_choice('custom.subscribe', 1) }}</button>--}}
                    </div>
                </div>
                @if($strategicDocument->policyArea)
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center mb-4 policy-area-single">
                            <h3 class="mb-2 fs-4 me-2">
                                @if($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value)
                                    {{ trans_choice('custom.field_of_actions', 1) }}:
                                @elseif($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::AREA->value)
                                    {{ trans_choice('site.strategic_document.areas', 1) }}:
                                @elseif($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value)
                                    {{ trans_choice('custom.municipalities', 1) }}:
                                @endif
                            </h3>

                            @php
                                $searchFieldPolicy = $strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value ? 'fieldOfActions' : ($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::AREA->value ? 'areas' : 'municipalities');
                            @endphp
                            <div class="fs-4">
                                <a target="_blank" href="{{ route('strategy-documents.index').'?'.$searchFieldPolicy.'[]='.$strategicDocument->policyArea->id }}" class="main-color text-decoration-none">
                            <span class="obj-icon-info me-2">
                                <i class="bi bi-mortarboard-fill me-1 main-color" ></i>{{ $strategicDocument->policyArea?->name }} </span>
                                </a>
                            </div>

                        </div>
                    </div>
                @endif
                <div class="row">
                    @if($strategicDocument->strategic_document_level_id)
                        <div class="col-md-4 mb-4">
                            <h3 class="mb-2 fs-18">
                                {{ __('site.strategic_document.level') }}
                            </h3>
                            @php
                                $searchFieldPolicy = $strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value ? 'fieldOfActions' : ($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::AREA->value ? 'areas' : 'municipalities');
                            @endphp
                            <a target="_blank" href="{{ route('strategy-documents.index').'?level[]='.$strategicDocument->strategic_document_level_id }}" class="main-color text-decoration-none">
                                <span class="obj-icon-info">
                                    <i class="fa-solid fa-arrow-right-to-bracket main-color me-2 fs-18" title="{{ trans_choice('custom.category', 1) }}"></i>
                                </span>
                                {{ $strategicDocument->strategic_document_level_id ? __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($strategicDocument->strategic_document_level_id)) : '---' }}
                            </a>
                        </div>
                    @endif

                    @if($strategicDocument->documentType)
                        <div class="col-md-4 mb-4">
                            <h3 class="mb-2 fs-18">{{ trans_choice('custom.strategic_document_type', 1) }}</h3>

                            <a href="#" class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                                <i class="fas fa-bezier-curve me-2 main-color fs-18" ></i>{{ $strategicDocument->documentType->name ?? __('custom.unidentified') }}
                            </span>
                            </a>
                        </div>
                    @endif

                    @if($strategicDocument->parentDocument)
                        <div class="col-md-4 mb-4">
                            <h3 class="mb-2 fs-18">{{ trans_choice('custom.document_to', 1) }} </h3>
                            @if ($strategicDocument->parent_document_id)
                                <a href="{{ route('strategy-document.view', [$strategicDocument->parent_document_id]) }}"  target="_blank"
                                   class="main-color text-decoration-none fs-18">
                                <span class="obj-icon-info me-2">
                                    <i class="fas fa-bezier-curve me-2 main-color fs-18" ></i>{{ $strategicDocument->parentDocument?->title }}</span>
                                </a>
                                @can('update', $strategicDocument)
                                    <a href="{{ route('admin.strategic_documents.edit', [$strategicDocument->parentDocument->id]) }}"  target="_blank">
                                        <i class="fas fa-pen me-2 main-color"></i>
                                    </a>
                                @endcan
                            @else
                                <span class="obj-icon-info me-2">
                                <i class="fas fa-bezier-curve me-2 main-color fs-18" ></i>{{ trans_choice('custom.strategic_document_link_missing', 1) }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ __('custom.effective_at') }}</h3>
                        <a href="#" class="main-color text-decoration-none fs-18" id="dateAccepted"
                           data-document-date-accepted="{{ \Carbon\Carbon::parse($strategicDocument->document_date_accepted)->format('d.m.Y') }}">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-calendar main-color me-2 fs-18"
                               title="Тип консултация"></i>{{ \Carbon\Carbon::parse($strategicDocument->document_date_accepted)->format('d.m.Y') }}</span>
                        </a>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.date_expiring', 1) }}</h3>

                        <a href="#" class="main-color text-decoration-none fs-18" id="dateExpiring"
                           @if ($strategicDocument->document_date_expiring) data-document-date-expiring="{{ \Carbon\Carbon::parse($strategicDocument->document_date_expiring)->format('d.m.Y') }}"
                           @else
                               data-document-date-expiring="true" @endif>
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>
                            @if ($strategicDocument->document_date_expiring)
                                {{ \Carbon\Carbon::parse($strategicDocument->document_date_expiring)->format('d.m.Y') }}
                            @else
                                {{ trans_choice('custom.date_indefinite_name', 1) }}
                            @endif
                        </span>
                        </a>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.acceptment_act', 1) }}</h3>
                        <div class="fs-18">
                            <span>{{ $strategicDocument->strategicActType?->name }}</span>
                            @if ($strategicDocument->pris?->doc_num && $strategicDocument->pris?->published_at)
                                <a href="{{ route('pris.view', ['category' => \Illuminate\Support\Str::slug($strategicDocument->pris?->actType->name), 'id' => $strategicDocument->pris?->id]) }}"
                                   class="main-color text-decoration-none">
                                    {{ $strategicDocument->pris?->name . ' №/' . $strategicDocument->pris?->doc_num . '/' . $strategicDocument->pris?->doc_date }}
                                </a>
                            @else
                                <a href="{{ $strategicDocument->strategic_act_link }}" class="main-color text-decoration-none">
                                    {{ $strategicDocument->strategic_act_number }}
                                </a>
                            @endif
                            @if($strategicDocument->acceptActInstitution)
                                <span>{{ trans_choice('custom.of', 1) }}</span>
                                <a href="#" class="main-color text-decoration-none">
                                    {{ $strategicDocument->acceptActInstitution?->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    @if($strategicDocument->public_consultation_id)
                        <div class="col-md-4 mb-4">
                            <h3 class="mb-2 fs-18">{{ trans_choice('custom.public_consultation_link', 1) }}</h3>
                            <a href="{{ route('public_consultation.view', [$strategicDocument->public_consultation_id]) }}"
                               class="main-color text-decoration-none fs-18">
                                <span class="obj-icon-info me-2">
                                    <i class="fas fa-link me-2 main-color fs-18"
                                       title="Тип консултация"></i>{{ $strategicDocument->publicConsultation?->title }}</span>
                            </a>
                        </div>
                    @endif
                    @isset($strategicDocument->link_to_monitorstat)
                        <div class="col-md-4 mb-4">
                            <h3 class="mb-2 fs-18">{{ trans_choice('custom.link_to_monitorstrat', 1) }}</h3>
                            <a href="{{ $strategicDocument->link_to_monitorstat }}"
                               class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                                <i class="fas fa-link me-2 main-color fs-18"
                                   title="Тип консултация"></i>{{ trans_choice('custom.link_to_monitorstrat', 1) }}</span>
                            </a>
                        </div>
                    @endisset
                </div>


                <div class="row mt-4 mb-4">
                    <div class="col-md-12">
                        <h3 class="mb-3">{{ trans_choice('custom.description', 1) }}</h3>
                        <div class="str-doc-info">
                            {!! htmlspecialchars_decode($strategicDocument->description) !!}
                        </div>
                    </div>
                </div>


                @if($strategicDocument->files->count())
                    <div class="row mb-4 mt-4">
                        {{--                        <h3 class="mb-3">{{ trans_choice('custom.files', 2) }}</h3>--}}
                        <div class="row table-light">
                            <div class="col-12 mb-2">
                                <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ trans_choice('custom.files', 2) }}</p>
                                <ul class="list-group list-group-flush">
                                    @foreach ($strategicDocument->files as $f)
                                        <li class="list-group-item">
                                            <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('strategy-document.preview.file_modal', ['id' => $f->id]) }}">
                                                {!! fileIcon($f->content_type) !!} {{ $f->description }} - {{ displayDate($f->created_at) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($documents) && sizeof($documents))
                    <div class="row mb-0 mt-5">
                        <div class="mb-2">
                            <h2 class="mb-1">{{ trans_choice('custom.strategic_documents.documents', 2) }}</h2>
                        </div>
                    </div>
                    <div class="row p-1 mb-2">
                        <div class="accordion" id="accordionExample">
                            @foreach($documents as $d)
                                @include('site.strategic_documents.tree_element')
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>



@endsection
