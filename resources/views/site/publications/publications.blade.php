@foreach($publications as $publication)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-library d-flex">
                    <div class="library-img-holder">
                        <img class="img-fluid" src="{{ $publication->mainImg ? $publication->mainImgAsset :  $publication->defaultImg }}" alt="{{ $publication->title }}">
                    </div>
                    <div class="consult-body">
                        <div class="consul-item">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <h3>
                                        <a href="{{ route('library.details', [$publication->type, $publication->id]) }}"
                                           class="text-decoration-none" title="{{ $publication->title }}"
                                        >
                                            {{ $publication->title }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="consult-item-header-edit">
                                    @can('delete', $publication)
                                        <a href="javascript:;"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $publication->id }}"
                                           data-resource-name="{{ $publication->title }}"
                                           data-resource-delete-url="{{ route('admin.publications.delete', $publication) }}"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.delete') }}">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                        </a>
                                    @endcan
                                    @can('update', $publication)
                                        <a href="{{ route('admin.publications.edit' , ['type' => $type, $publication->id]) }}" data-toggle="tooltip" title="{{ __('custom.edit') }}">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            <a href="{{ route('library.publications') }}?categories[]={{ $publication->publication_category_id }}"
                               title="{{ $publication->category?->name }}" class="text-decoration-none mb-3"
                            >
                                <i class="fas fa-sitemap me-1" title="{{ $publication->category?->name }}"></i>
                                {{ $publication->category?->name }}
                            </a>
                            <div class="anotation text-secondary mb-2 mt-2">
                                {!! strip_tags($publication->short_content) ? strip_tags(Str::limit($publication->short_content, 200)) : "" !!}
                            </div>
                            <div class="meta-consul">
                                <span class="text-secondary">
                                    <i class="far fa-calendar text-secondary" title="Публикувано"></i> {{ displayDate($publication->published_at) }} г.
                                </span>
                                <a href="{{ route('library.details', [$publication->type, $publication->id]) }}" title="{{ $publication->title }}">
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

<div id="ajax-pagination" class="row mb-4" data-id="publications">
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
