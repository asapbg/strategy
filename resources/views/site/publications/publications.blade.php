@foreach($publications as $publication)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-library d-flex">
                    <div class="library-img-holder">
                        <img class="img-fluid" src="{{ asset($publication->mainImg?->path) }}" alt="{{ $publication->translation?->title }}">
                    </div>
                    <div class="consult-body">
                        <div class="consul-item">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <h3>
                                        <a href="{{ route('library.details', [$publication->type, $publication->id]) }}"
                                           class="text-decoration-none" title="{{ $publication->translation?->title }}"
                                        >
                                            {{ $publication->translation?->title }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                           role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                           title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('library.publications') }}?categories[]={{ $publication->publication_category_id }}"
                               title="{{ $publication->category?->name }}" class="text-decoration-none mb-3"
                            >
                                <i class="fas fa-sitemap me-1" title="{{ $publication->category?->name }}"></i>
                                {{ $publication->category?->name }}
                            </a>
                            <div class="anotation text-secondary mb-2 mt-2">
                                {!! $publication->translation?->short_content ? Str::limit($publication->translation?->short_content, 200) : "" !!}
                            </div>
                            <div class="meta-consul">
                                <span class="text-secondary">
                                    <i class="far fa-calendar text-secondary" title="Публикувано"></i> {{ displayDate($publication->published_at) }} г.
                                </span>
                                <a href="{{ route('library.details', [$publication->type, $publication->id]) }}" title="{{ $publication->translation?->title }}">
                                    <i class="fas fa-arrow-right read-more"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div id="publications_pagination" class="ajax_pagination row mb-4" data-id="publications">
    @desktop
    @if($publications->count() > 0 && $publications instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $publications->onEachSide(2)->appends(request()->query())->links() }}
    @endif
    @elsedesktop
    @if($publications->count() > 0 && $publications instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $publications->onEachSide(0)->appends(request()->query())->links() }}
    @endif
    @enddesktop
</div>
