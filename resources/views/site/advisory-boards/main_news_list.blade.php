@if(isset($pageTopContent) && !empty($pageTopContent->value))
    <div class="col-12 mb-5">
        {!! $pageTopContent->value !!}
    </div>
@endif
@php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\Publication::class))
@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.publications.edit', ['type' => \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value,'item' => 0])])
@include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer'])

<div class="row mb-2">
    <div class="col-md-6 mt-2">
        <div class="info-consul text-start">
            <p class="fw-600">
                {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}
            </p>
        </div>
    </div>
    @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
</div>

@if($items->count())
    <div class="row">
        @foreach($items as $item)
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="{{ asset($item->mainImg->path) }}" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">{{ displayDate($item->published_at) }}</span>
                    <h3 class="post-title">{{ $item->title }}</h3>
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">{{ $item->advCategory }}</span>
                        </div>
                        <div class="col-md-4">
                            <div class="consult-item-header-edit">
                                @can('delete', $item)
                                    <a href="javascript:;"
                                       class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                       data-target="#modal-delete-resource"
                                       data-resource-id="{{ $item->id }}"
                                       data-resource-name="{{ $item->title }}"
                                       data-resource-delete-url="{{ route('admin.advisory-boards.delete', $item) }}"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                    </a>
                                @endcan
                                @can('update', $item)
                                    <a href="{{ route('admin.advisory-boards.edit', ['type' => $item->type, 'item' => $item->id]) }}" target="_blank">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                        </i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <p class="short-decription text-secondary">
                        {!! strip_tags($item->short_content) ? strip_tags(Str::limit($item->short_content, 200)) : "" !!}
                    </p>
                    <a href="{{ route('library.details', [$item->type, $item->id]) }}" class="readmore stretched-link mt-1" target="_blank" title="{{ $item->title }}">{{ __('site.read_more') }} <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
        @endforeach
    </div>
@endif


<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
