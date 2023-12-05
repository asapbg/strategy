
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\Poll::class))
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.polls.edit', ['id' => 0])])
{{--    @include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer'])--}}

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
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-img-holder">
                                <i class="fa-solid fa-clipboard-question dark-blue"></i>
                            </div>
                            <div class="consult-body">
                                <div href="#" class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('poll.show', ['id' => $item->id]) }}" class="text-decoration-none" title="{{ $item->name }}">
                                                <h3>{{ $item->name }}</h3>
                                            </a>
                                        </div>
                                        <div class="consult-item-header-edit">
                                            @can('delete', $item)
                                                <a href="javascript:;"
                                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->name }}"
                                                   data-resource-delete-url="{{ route('admin.poll.delete', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                </a>
                                            @endcan
                                            @can('update', $item)
                                                <a href="{{ route('admin.polls.edit', ['id' => $item->id]) }}" target="_blank">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                    </i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>


                                    <div class="status mt-2">
                                        <div>
                                            <span>{{ __('custom.status') }}:
                                                @if($item->inPeriod)
                                                    <span class="active-li">{{ __('custom.active_f') }}</span>
                                                @else
                                                    <span class="closed-li">{{ __('custom.closed_f') }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="meta-consul mt-2">
                                            <span class="text-secondary">
                                                <i class="far fa-calendar text-secondary me-1"></i> {{ displayDate($item->start_date) }}
                                            </span>

                                        <a href="{{ route('poll.show', ['id' => $item->id]) }}" title="{{ $item->name }}">
                                            <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
                                        </a>
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
            {{ $items->appends(request()->query())->links() }}
        @endif
    </div>
