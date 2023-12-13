
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\Pris::class))
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.pris.edit', ['item' => 0])])
    @include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer'])

    <div class="row mb-2">
        <div class="col-md-6 mt-2">
            <div class="info-consul text-start">
                <p class="fw-600">
                    {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->count()]) }}
                </p>
            </div>
        </div>
        @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
    </div>

    @if($items->count())
        @foreach($items as $item)
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-body">
                                <div class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('pris.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name),'id' => $item->id]) }}" class="text-decoration-none" title="{{ $item->mcDisplayName }}">
                                                <h3>{{ $item->mcDisplayName }}</h3>
                                            </a>
                                        </div>
                                        <div class="consult-item-header-edit">
                                            @can('delete', $item)
                                                <a href="javascript:;"
                                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->mcDisplayName }}"
                                                   data-resource-delete-url="{{ route('admin.pris.delete', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                </a>
                                            @endcan
                                            @can('update', $item)
                                                <a href="{{ route('admin.pris.edit', ['item' => $item->id]) }}" target="_blank">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                    </i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="meta-consul d-flex justify-content-start">
                                        <a href="#" title="{{ __('custom.category') }}" class="text-decoration-none mb-2 me-2">
                                            <i class="fa-solid fa-thumbtack  main-color" title="{{ $item->actType->name }}"></i>
                                            {{ $item->actType->name }}
                                        </a>
                                        @if($item->institutions->count())
                                            @foreach($item->institutions as $i)
                                                <a href="{{ route('pris.index').'?institutions[]='.$i->id }}" title="{{ trans_choice('custom.institutions', 1) }}" class="text-decoration-none mb-2 me-2" target="_blank">
                                                    <i class="fas fa-university fa-link main-color" title="{{ $i->name }}"></i>
                                                    {{ $i->name }}
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="meta-consul">
                                        <div class="anotation text-secondary mb-2">
                                            <span class="main-color me-2">
                                                {{ __('site.pris.about') }}:
                                            </span> {!! $item->about !!}
                                        </div>
                                    </div>
                                    <div class="meta-consul">
                                        <span class="text-secondary d-flex flex-row align-items-baseline lh-normal"><i class="far fa-calendar text-secondary me-1"></i> {{ displayDate($item->doc_date) }} {{ __('site.year_short') }}</span>
                                        <a href="{{ route('pris.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name), 'id' => $item->id]) }}"><i class="fas fa-arrow-right read-more mt-2"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


    <div class="row">
        @if(isset($items) && $items->count() > 0)
            {{ $items->onEachSide(0)->appends(request()->query())->links() }}
        @endif
    </div>
