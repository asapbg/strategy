@php
    $dItem = \App\Models\StrategicDocumentChildren::find($d->id);
    $translation = json_decode($d->translations, true);
    $defaultTranslation = array_filter($translation, function ($el){ return $el['locale'] == app()->getLocale(); });
    $defaultTranslation = array_values($defaultTranslation);
    $docFiles = json_decode($d->files, true)
@endphp
<div class="card custom-card mb-2" @if(isset($doc->level) && $doc->level) style="margin-left: {{ ($doc->level * 3).'0px' }};" @endif>
    <div class="card-header" id="heading{{ $d->id }}">
        <h2 class="mb-0">
            <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $d->id }}" aria-expanded="false" aria-controls="collapse{{ $d->id }}">
                @if(isset($doc->level) && $doc->level)
                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                @else
                    <i class="me-1 bi bi-pin-map-fill main-color fs-18"></i>
                @endif
                {{ $defaultTranslation[0]['title'] }}
            </button>
        </h2>
    </div>
    <div id="collapse{{ $d->id }}" class="collapse" aria-labelledby="heading{{ $d->id }}" data-parent="#accordionExample" style="">
        <div class="card-body">
            <div class="row @if(!empty($defaultTranslation[0]['description'])) mb-3 @endif">
                <div class="col-12 mb-2">
                    {!! html_entity_decode($defaultTranslation[0]['description']) !!}
                </div>
            </div>
            <div class="row">
                @if($d->strategic_document_type_id)
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.strategic_document_type', 1) }}</h3>
                        <a href="#" class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                                <i class="fas fa-bezier-curve me-2 main-color fs-18"></i>{{ $d->strategic_document_type_name ?? __('custom.unidentified') }}
                            </span>
                        </a>
                    </div>
                @endif
                @if($d->document_date_accepted)
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.accepted_date', 1) }}</h3>
                        <a href="#" class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                            <i class="fas fa-calendar main-color me-2 fs-18"></i>{{ displayDate($d->document_date_accepted) }}
                            </span>
                        </a>
                    </div>
                @endif
                <div class="col-md-4 mb-4">
                    <h3 class="mb-2 fs-18">{{ trans_choice('custom.date_expiring', 1) }}</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-calendar-check me-2 main-color fs-18"></i>
                            @if($d->document_date_expiring)
                                {{ displayDate($d->document_date_expiring) }}
                            @else
                                {{ trans_choice('custom.date_indefinite_name', 1) }}
                            @endif
                        </span>
                    </a>
                </div>
                @if($d->public_consultation_id)
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.public_consultation_link', 1) }}</h3>
                        <a href="{{ route('public_consultation.view', [$d->public_consultation_id]) }}"
                           class="main-color text-decoration-none fs-18">
                            <span class="obj-icon-info me-2">
                                <i class="fas fa-link me-2 main-color fs-18"></i>{{ $d->consultation_reg_num }}</span>
                        </a>
                    </div>
                @endif
                @if($d->link_to_monitorstat)
                    <div class="col-md-4 mb-4">
                        <h3 class="mb-2 fs-18">{{ trans_choice('custom.link_to_monitorstrat', 1) }}</h3>
                        <a href="{{ $d->link_to_monitorstat }}" target="_blank" lass="main-color text-decoration-none fs-18">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-link me-2 main-color fs-18"></i>{{ trans_choice('custom.link_to_monitorstrat', 1) }}</span>
                        </a>
                    </div>
                @endif
            </div>


            @if(isset($docFiles) && sizeof($docFiles))
                <div class="row">
                    <div class="col-12 mb-2">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ trans_choice('custom.files', 2) }}</p>
                        <ul class="list-group list-group-flush">
                            @foreach($docFiles as $f)
                                @if($f['id'] && $f['locale'] == app()->getLocale())
                                    <li class="list-group-item">
                                        <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f['id'] }}" data-url="{{ route('modal.file_preview', ['id' => $f['id']]) }}">
                                            {!! fileIcon($f['type']) !!} {{ $f['description_'.$f['locale']] }} - {{ displayDate($f['created_at']) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(isset($d->children) && sizeof($d->children))
    @foreach($d->children as $doc)
        @include('site.strategic_documents.tree_element', ['d' => $doc])
    @endforeach
@endif