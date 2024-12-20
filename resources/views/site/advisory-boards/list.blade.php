@php $addBtn = auth()->user() && auth()->user()->can('create', \App\Models\AdvisoryBoard::class) @endphp
@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.advisory-boards.create'), 'export_excel' => true, 'export_pdf' => true])
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
            @php
                $fRequest = $rf ?? ($requestFilter ?? request()->all());

                if (isset($fRequest['page'])) {
                    unset($fRequest['page']);
                }
            @endphp

            <select class="form-select w-100" id="groupByAjax" name="groupBy" data-container="#listContainer" onchange="removeUrlParameter('page');">
                @foreach($groupOptions as $group)
                    @php $fRequest['groupBy'] = $group['value'] @endphp
                    <option value="{{ $group['value'] }}" data-url="{{ url()->current(). '?' . http_build_query($fRequest) }}" @if((request()->input('groupBy') ?? '') == $group['value']) selected @endif>{{ $group['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@php $groupByField = request()->get('groupBy') @endphp
@if(!empty($groupByField))
    @php $currentGroupValue = '' @endphp
@endif

@if($groupByField)
    @if($items->count())
        @php $groups = collect(); @endphp

        @foreach($items as $item)
            @if($groupByField == 'fieldOfAction')
                @php groupItems($groups, $item, 'policy_area_id', 'policy_area_id', 'policyArea'); @endphp
            @elseif($groupByField == 'authority')
                @php groupItems($groups, $item, 'authority_id', 'authority_id', 'authority'); @endphp
            @elseif($groupByField == 'chairmanType')
                @php groupItems($groups, $item, 'advisory_chairman_type_id', 'advisory_chairman_type_id', 'advisoryChairmanType'); @endphp
            @elseif($groupByField == 'npo')
                @php
                    $found_group = $groups->where('id', $item->has_npo_presence)->first();

                    if (!$found_group) {
                        $groups->push([
                            'group_type' => 'has_npo_presence',
                            'id' => $item->has_npo_presence,
                            'name' => $item->has_npo_presence ? __('site.with_npo') : __('site.without_npo'),
                            'items' => collect([$item]),
                        ]);
                    } else {
                        $found_group['items']->push($item);
                    }
                @endphp
            @elseif($groupByField == 'actOfCreation')
                @php groupItems($groups, $item, 'advisory_act_type_id', 'advisory_act_type_id', 'advisoryActType'); @endphp
            @elseif($groupByField == 'status')
                @php
                    $found_group = $groups->where('id', $item->active)->first();

                    if (!$found_group) {
                        $groups->push([
                            'group_type' => 'active',
                            'id' => $item->active,
                            'name' => $item->active ? __('custom.active') : __('custom.inactive'),
                            'items' => collect([$item]),
                        ]);
                    } else {
                        $found_group['items']->push($item);
                    }
                @endphp
            @endif
        @endforeach

        @foreach($groups as $group)
            <div class="row @if($loop->first) mt-3 @else mt-5 @endif mb-2">
                <div class="col-md-12">
                    <div class="custom-left-border fs-18 fw-bold">{{ $group['name'] }}</div>
                    <hr class="my-2">
                </div>
            </div>

            @foreach($group['items'] as $item)
                @includeIf('site.advisory-boards.partial.item')
            @endforeach
        @endforeach
    @endif
@else
    @if($items->count())
        @foreach($items as $item)
            @if(!empty($groupByField))
                @if($groupByField == 'fieldOfAction')
                    @php $compareValue = $item->policyArea->name @endphp
                @elseif($groupByField == 'authority')
                    @php $compareValue = $item->authority->name @endphp
                @elseif($groupByField == 'chairmanType')
                    @php $compareValue = $item->advisoryChairmanType->name @endphp
                @elseif($groupByField == 'npo')
                    @php $compareValue = $item->has_npo_presence ? __('site.with_npo') : __('site.without_npo') @endphp
                @elseif($groupByField == 'actOfCreation')
                    @php $compareValue = $item->advisoryActType->name @endphp
                @endif

                @if($currentGroupValue != $compareValue)
                    @php $currentGroupValue = $compareValue @endphp
                    <div class="row @if($loop->first) mt-3 @else mt-5 @endif mb-2">
                        <div class="col-md-12">
                            <div class="custom-left-border fs-18 fw-bold">{{ $currentGroupValue }}</div>
                            <hr class="my-2">
                        </div>
                    </div>
                @endif
            @endif

            @includeIf('site.advisory-boards.partial.item')
        @endforeach
    @endif
@endif

@includeIf('modals.delete-resource', ['resource' => trans_choice('custom.advisory_boards', 1)])
<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
