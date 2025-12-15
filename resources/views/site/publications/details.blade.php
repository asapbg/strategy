@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', $publication->title)

@section('content')

<div class="row">

    @includeIf('site.publications.sidemenu')

    @php
        $current_type = ($type == App\Enums\PublicationTypesEnum::TYPE_NEWS->value) ? "news" : "publications";
    @endphp

    <div class="col-lg-10 py-2 right-side-content">
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
                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
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
        <div class="mb-3">
            <h2 class="mb-5">{{ $publication->title }}</h2>
            @if($publication->mainImg)
                <img src="{{ asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $publication->mainImg->path)) }}" alt="{{ $publication->title }}"
                     class="img-fluid col-md-5 float-md-start mb-4 me-md-4 news-single-img publication-main-img img-thumbnail"
                >
            @endif
            {!! $publication->content !!}
        </div>

        @php
            $files = $publication->files()
//                ->whereNotIn('content_type', App\Models\File::CONTENT_TYPE_IMAGES)
                ->whereLocale(currentLocale())
                ->orderByRaw("array_position(array[".\App\Models\File::ORDER_BY_CONTENT_TYPE."], content_type)")
                ->get();
        @endphp
        @if($files->count() > 0)
            <h5>{{ __('custom.files') }}</h5>
            <div class="row mb-3 w-100">
                @foreach($files as $f)
                    @if($f->id != $publication->file_id)
                        @if(in_array($f->content_type, \App\Models\File::IMG_CONTENT_TYPE))
                            {!! fileThumbnail($f) !!}
                        @else
                            <p>
                                @php
                                    $file_name = fileIcon($f->content_type)." ".$f->{'description_'.$f->locale} ?? $f->filename;
                                @endphp
                                @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
{{--                                <a class="text-decoration-none" href="{{ route('download.file', ['file' => $f->id]) }}">--}}
{{--                                    | {{ __('custom.download') }}--}}
{{--                                </a>--}}
                            </p>
                        @endif
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
@includeIf('modals.delete-resource', ['resource' => $current_type == 'news' ? trans_choice('custom.news', 1) : trans_choice('custom.publications', 1)])

@endsection
