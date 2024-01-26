@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', $publication->title)

@section('content')

<div class="row">

    @includeIf('site.publications.sidemenu')

    @php
        $current_type = ($type == App\Enums\PublicationTypesEnum::TYPE_NEWS->value) ? "news" : "publications";
    @endphp

    <div class="col-lg-10 py-5 right-side-content">
        <div class="row">
            <div class="col-md-8">
                <a href="javascript:;" class="text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>{{ displayDate($publication->published_at) }} г.
                    </span>
                </a>
                @if($publication->category)
                    <a href="{{ route("library.$current_type") }}?categories[]={{ $publication->publication_category_id }}" class="text-decoration-none">
                        <span class="obj-icon-info me-2">
                            <i class="fas fa-sitemap me-1 dark-blue" title="{{ $publication->category?->name }}"></i>{{ $publication->category?->name }}
                        </span>
                    </a>
                @else
                    <span class="dark-blue"><i class="fas fa-sitemap me-1" title="{{ $publication->advCategory }}"></i> {{ $publication->advCategory }}</span>
                @endif
            </div>
            <div class="col-md-4 text-end">
                @can('update', $publication)
                    <a href="{{ route('admin.publications.edit' , ['type' => $publication->type, 'item' => $publication->id]) }}" class="btn btn-sm btn-primary main-color">
                        <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}
                    </a>
                @endcan
                @can('delete', $publication)
                    <a href="javascript:;"
                       class="btn btn-sm btn-danger"
                       data-target="#modal-delete-resource"
                       data-resource-id="{{ $publication->id }}"
                       data-resource-name="{{ $publication->title }}"
                       data-resource-delete-url="{{ route('admin.publications.delete', $publication) }}"
                       data-toggle="tooltip"
                       title="{{ __('custom.delete') }}">
                        <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>{{ __('custom.delete') }}
                    </a>
                @endcan
            </div>
        </div>
        <hr>
        <div class="mb-3 row">
            <h2 class="mb-5">{{ $publication->title }}</h2>
            @if($publication->mainImg)
                <img src="{{ asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $publication->mainImg->path)) }}" alt="{{ $publication->title }}"
                     class="img-fluid col-md-5 float-md-start mb-4 me-md-4 news-single-img publication-main-img img-thumbnail"
                >
            @endif
            {!! $publication->content !!}

            <a href=""></a>
        </div>

        @php
            $files = $publication->files()
//                ->whereNotIn('content_type', App\Models\File::CONTENT_TYPE_IMAGES)
                ->whereLocale(currentLocale())
                ->get();
        @endphp
        @if($files->count() > 0)
            <div class="row mb-3">
                <h5>Файлове</h5>
                @foreach($files as $f)
                    @if($f->id != $publication->file_id)
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
                @endforeach
            </div>
        @endif

        <div class="row">
            @if($publication->type == \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
                <a class="btn btn-primary mt-4 mb-5 col-auto" href="{{ url()->previous() }}">{{ __('site.back_to_news') }}</a>
            @else
                <a class="btn btn-primary mt-4 mb-5 col-auto" href="{{ route("library.$current_type") }}">{{ __('site.back_to_'.$current_type) }}</a>
            @endif
        </div>
    </div>

</div>

@endsection
