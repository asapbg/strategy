@extends('layouts.site', ['fullwidth' => true])

<style>
    #siteLogo, #ok, #ms, .nav-link, .nav-item, #register-link, #login-btn, #search-btn, #back-to-admin, #profile-toggle {
        transition: 0.4s;
    }
</style>

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_detail_page')

        <!-- Right side -->
        <div class="col-lg-10 py-5 right-side-content">
            @if($news->count())
                <div class="row">
                    @foreach($news as $nItem)
                        <div class="col-lg-4 mb-4">
                            <div class="post-box">
                                <div class="post-img"><img src="{{ asset($nItem->mainImg->path) }}" class="img-fluid" alt=""></div>
                                <span class="post-date text-secondary">{{ displayDate($nItem->published_at) }}</span>
                                <h3 class="post-title">{{ $nItem->title }}</h3>
                                <div class="row mb-2">
                                    <div class="col-md-8">
                                        @if($nItem->category)
                                            <span class="blog-category">{{ $nItem->category->name }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="consult-item-header-edit">
                                            @can('delete', $nItem)
                                                <a href="javascript:;"
                                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $nItem->id }}"
                                                   data-resource-name="{{ $nItem->title }}"
                                                   data-resource-delete-url="{{ route('admin.advisory-boards.delete', $nItem) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                </a>
                                            @endcan
                                            @can('update', $nItem)
                                                <a href="{{ route('admin.advisory-boards.edit', ['type' => $nItem->type, 'item' => $nItem->id]) }}" target="_blank">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                    </i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <p class="short-decription text-secondary">
                                    {!! strip_tags($item->short_content) ? strip_tags(Str::limit($nItem->short_content, 200)) : "" !!}
                                </p>
                                <a href="{{ route('library.details', [$nItem->type, $nItem->id]) }}" class="readmore stretched-link mt-1" target="_blank" title="{{ $nItem->title }}">{{ __('site.read_more') }} <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection