@foreach($news as $news_row)
    <div class="col-lg-4 mb-4">
        <div class="post-box">
            <div class="post-img">
                <img class="img-fluid" src="{{ asset($news_row->mainImg?->path) }}" alt="{{ $news_row->translation?->title }}">
            </div>
            <span class="post-date text-secondary">{{ displayDate($news_row->published_at) }} г.</span>
            <h3 class="post-title">
                <a href="{{ route('library.details', [$news_row->type, $news_row->id]) }}" class="text-decoration-none" title="{{ $news_row->translation?->title }}">
                    {{ $news_row->translation?->title }}
                </a>
            </h3>
            <div class="row mb-2">
                <div class="col-md-8">
                    <a href="{{ route('library.news') }}?categories[]={{ $news_row->publication_category_id }}"
                       title="{{ $news_row->category?->name }}"
                    >
                        <span class="blog-category">{{ $news_row->category?->name }}</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="consult-item-header-edit">
                        @can('delete', $news_row)
                            <a href="javascript:;"
                               data-target="#modal-delete-resource"
                               data-resource-id="{{ $news_row->id }}"
                               data-resource-name="{{ $news_row->title }}"
                               data-resource-delete-url="{{ route('admin.publications.delete', $news_row) }}"
                               data-toggle="tooltip"
                               title="{{ __('custom.delete') }}">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                        @endcan
                        @can('update', $news_row)
                            <a href="{{ route('admin.publications.edit' , [$news_row->id]) }}" data-toggle="tooltip" title="{{ __('custom.edit') }}">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <p class="short-decription text-secondary">
                {!! $news_row->translation?->short_content ? Str::limit($news_row->translation?->short_content, 200) : "" !!}
            </p>
            <a href="{{ route('library.details', [$news_row->type, $news_row->id]) }}" class="readmore mt-1"
               title="{{ $news_row->translation?->title }}"
            >
                Прочетете още <i class="fas fa-long-arrow-right"></i>
            </a>
        </div>
    </div>
@endforeach

<div id="news_pagination" class="ajax_pagination row mb-4" data-id="news">
    @desktop
    @if($news->count() > 0 && $news instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $news->onEachSide(2)->appends(request()->query())->links() }}
    @endif
    @elsedesktop
    @if($news->count() > 0 && $news instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $news->onEachSide(0)->appends(request()->query())->links() }}
    @endif
    @enddesktop
</div>
