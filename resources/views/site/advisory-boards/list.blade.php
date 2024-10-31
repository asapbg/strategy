@php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\AdvisoryBoard::class))
@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.advisory-boards.create')])
@include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'info' => __('site.sort_info_adv_board'), 'customRequestParam' => $customRequestParam ?? null])
<input type="hidden" id="subscribe_model" value="App\Models\AdvisoryBoard">
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
<div class="row mb-2">
    <div class="col-md-6 mt-2">
        <div class="col-md-6 mb-2 text-start col-sm-12 d-flex align-items-center justify-content-start flex-direction-row">
            <label for="groupBy" class="form-label fw-bold mb-0 me-3 no-wrap group-by-label">Групиране по:</label>
            @php($fRequest = $rf ?? ($requestFilter ?? request()->all()))
            <select class="form-select w-100" id="groupByAjax" name="groupBy" data-container="#listContainer">
                @foreach($groupOptions as $group)
                    @php($fRequest['groupBy'] = $group['value'])
                    <option value="{{ $group['value'] }}" data-url="{{ url()->current(). '?' . http_build_query($fRequest) }}" @if((request()->input('groupBy') ?? '') == $group['value']) selected @endif>{{ $group['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@php($groupByField = request()->get('groupBy'))
@if(!empty($groupByField))
    @php($currentGroupValue = '')
@endif
@if($items->count())
    @foreach($items as $item)
        @if(!empty($groupByField))
            @if($groupByField == 'fieldOfAction')
                @php($compareValue = $item->policyArea->name)
            @elseif($groupByField == 'authority')
                @php($compareValue = $item->authority->name)
            @elseif($groupByField == 'chairmanType')
                @php($compareValue = $item->advisoryChairmanType->name)
            @elseif($groupByField == 'npo')
                @php($compareValue = $item->has_npo_presence ? __('site.with_npo') : __('site.without_npo'))
            @elseif($groupByField == 'actOfCreation')
                @php($compareValue = $item->advisoryActType->name)
            @endif

            @if($currentGroupValue != $compareValue)
                @php($currentGroupValue = $compareValue)
                <div class="row @if($loop->first) mt-3 @else mt-5 @endif mb-2">
                    <div class="col-md-12">
                        <div class="custom-left-border fs-18 fw-bold">{{ $currentGroupValue }}</div>
                        <hr class="my-2">
                    </div>
                </div>
            @endif
        @endif
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
                            @if($item->policyArea)
                                <a href="{{ route('advisory-boards.index').'?fieldOfActions[]='.$item->policyArea->id }}"
                                   title="{{ $item->policyArea->name }}" class="text-decoration-none mb-2">
                                    <i class="text-primary {{ $item->policyArea->icon_class }} me-1" title="{{ $item->policyArea->name }}"></i>
                                    {{ $item->policyArea->name }}
                                </a>
                            @endif
                            <div class="meta-consul mt-2">
                                <span>{{ __('custom.status') }}:
                                    @php($class = $item->active ? 'active-ks' : 'inactive-ks')
                                    <span
                                        class="{{ $class }}">{{ $item->active ? __('custom.active_m') : __('custom.inactive_m') }}</span>
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
