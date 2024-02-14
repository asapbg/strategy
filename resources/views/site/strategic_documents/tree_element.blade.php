@php
    $dItem = \App\Models\StrategicDocumentChildren::find($d->id);
    $translation = json_decode($d->translations, true);
    $defaultTranslation = array_filter($translation, function ($el){ return $el['locale'] == app()->getLocale(); });
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
                    {!! $defaultTranslation[0]['description'] !!}
                </div>
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
