@php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\AdvisoryBoard::class))
@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.advisory-boards.create')])
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
    @foreach($items as $item)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-body">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="{{ route('advisory-boards.view', $item) }}"
                                       class="text-decoration-none"
                                       title="{{ $item->name }}">
                                        <h3>{{ $item->name }}</h3>
                                    </a>
                                </div>

                                @if($item->active && auth()->user())
                                    @can('update', $item)
                                        <div class="consult-item-header-edit">
                                            <a href="{{ route('admin.advisory-boards.index') . '?keywords=' . $item->id . '&status=1' }}">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                   role="button" title="{{ __('custom.delete') }}"></i>
                                            </a>
                                            <a href="{{ route('admin.advisory-boards.edit', ['item' => $item]) }}" target="_blank"
                                               class="me-2">
                                                <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                   role="button"
                                                   title="{{ __('custom.edit') }}">
                                                </i>
                                            </a>
                                        </div>
                                    @endcan
                                @endif
                            </div>
                            <div class="meta-consul">
                                <span>{{ __('custom.status') }}:
                                    @php($class = $item->active ? 'active-ks' : 'inactive-ks')
                                    <span
                                        class="{{ $class }}">{{ $item->active ? __('custom.active') : __('custom.inactive_m') }}</span>
                                </span>
                                <a href="{{ route('advisory-boards.view', $item) }}">
                                    <i class="fas fa-arrow-right read-more text-end"></i>
                                </a>
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
