<div class="row">
    <div class="col-md-8">
        <a href="javascript:;" class="text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>{{ displayDate($publication->published_at) }} г.
                    </span>
        </a>
        <span class="dark-blue"><i class="fas fa-sitemap me-1" title="{{ $publication->advCategory }}"></i> {{ $publication->advCategory }}</span>
    </div>
    <div class="col-md-4 text-end">
        @can('updateAdvBoard', $publication)
            <a href="{{ route('admin.advisory-boards.news.edit' , $publication) }}" target="_blank" class="btn btn-sm btn-primary main-color">
                <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}
            </a>
        @endcan
        @can('deleteAdvBoard', $publication)
            <a href="javascript:;"
               class="btn btn-sm btn-danger"
               data-target="#modal-delete-resource"
               data-resource-id="{{ $publication->id }}"
               data-resource-name="{{ $publication->title }}"
               data-resource-delete-url="{{ route('admin.advisory-boards.news.delete', $publication) }}"
               data-toggle="tooltip"
               title="{{ __('custom.delete') }}">
                <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>{{ __('custom.delete') }}
            </a>
        @endcan
    </div>
</div>
<hr>

<div class="mb-3">
    @if($publication->mainImg)
        <img src="{{ $publication->mainImgAsset }}" alt="{{ $publication->title }}"
             class="img-fluid col-md-5 float-md-start mb-4 me-md-4 news-single-img publication-main-img img-thumbnail"
        >
    @endif
    {!! $publication->content !!}
</div>

@php
    $files = $publication->files()
//        ->whereNotIn('content_type', App\Models\File::CONTENT_TYPE_IMAGES)
        ->whereLocale(currentLocale())
        ->orderByRaw("array_position(array[".\App\Models\File::ORDER_BY_CONTENT_TYPE."], content_type)")
        ->get();
@endphp
@if($files->count() > 0)
    @php($fileFound = false)
    <div class="mb-3 row w-100">
        @foreach($files as $f)
            @if($f->id != $publication->file_id)
                @if(!$fileFound)
                    <h5>{{ __('custom.files') }}</h5>
                @endif
                @if(in_array($f->content_type, \App\Models\File::IMG_CONTENT_TYPE))
                    {!! fileThumbnail($f) !!}
                @else
                    <p>
                        <a class="text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
                            {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} ?? $f->filename }}
                        </a> |
                        {{--                        @if(!in_array($f->content_type, App\Models\File::CONTENT_TYPE_IMAGES))--}}
                        <a class="text-decoration-none" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                            {{ __('custom.download') }}
                        </a>
                        {{--                        @endif--}}
                    </p>
                @endif
                @php($fileFound = true)
            @endif
        @endforeach
    </div>
@endif
<div class="row">
    <a class="btn btn-primary mt-4 mb-5 col-auto" href="{{ url()->previous() }}">{{ __('site.back_to_news') }}</a>
</div>
