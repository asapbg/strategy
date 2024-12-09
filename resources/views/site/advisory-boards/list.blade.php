@php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\AdvisoryBoard::class))
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
                                    @canany(['update', 'delete'], $item)
                                        <div class="consult-item-header-edit">
                                            @can(['delete'], $item)
                                                <a href="javascript:;"
                                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->name }}"
                                                   data-resource-delete-url="{{ route('admin.advisory-boards.delete', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                </a>
                                            @endcan
                                            @can(['update'], $item)
                                                <a href="{{ route('admin.advisory-boards.edit', ['item' => $item]) }}" target="_blank"
                                                   class="me-2">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                       role="button"
                                                       title="{{ __('custom.edit') }}">
                                                    </i>
                                                </a>
                                            @endcan
                                        </div>
                                    @endcanany
                                @endif
                            </div>
                            @if($item->policyArea)
                                <a href="{{ route('advisory-boards.index').'?fieldOfActions[]='.$item->policyArea->id }}"
                                   title="{{ $item->policyArea->name }}" class="text-decoration-none mb-2 me-3">
                                    <i class="text-primary {{ $item->policyArea->icon_class }} me-1" title="{{ $item->policyArea->name }}"></i>
                                    {{ $item->policyArea->name }}
                                </a>
                            @endif
                            @if($item->authority)
                                <br/>
                                <span class="me-1"><strong>{{ trans_choice('custom.authority_advisory_board', 1) }}:</strong></span>

                                <a href="{{ route('advisory-boards.index').('?authoritys[]='.$item->authority->id) }}" class="main-color text-decoration-none me-3">
                                    <i class="fa-solid fa-right-to-bracket me-1 main-color"
                                       title="{{ $item->authority->name }}"></i>
                                    {{ $item->authority->name }}
                                </a>
                            @endif
                            @if(isset($item->chairmen) && $item->chairmen->count() > 0)
                                <br/>
                                <span class="me-1"><strong>{{ __('custom.chairman_site') }}:</strong></span>

                                @foreach($item->chairmen as $chairmen)
                                    @php($dataChairmen = [])
                                    @foreach(['member_name', 'member_job', 'institution'] as $n)
                                        @if(!empty($chairmen->{$n}))
                                            @php($dataChairmen[] = $n != 'institution' ? $chairmen->{$n} : $chairmen->institution->name)
                                        @endif
                                    @endforeach
                                        @if(sizeof($dataChairmen))
                                            <span class="mb-2">{{ Str::ucfirst(implode(', ', $dataChairmen)) }}</span>
                                        @endif
                                        @if(!empty($chairmen->member_notes))
                                            {!! $chairmen->member_notes !!}
                                        @endif
                                @endforeach
                            @endif
                            @if($item->advisoryActType)
                                <br/>
                                <span class="me-1"><strong>{{ __('validation.attributes.act_of_creation') }}:</strong></span>

                                <a href="{{ route('advisory-boards.index').('?actOfCreations[]=' . $item->advisoryActType->id) }}" class="main-color text-decoration-none me-3">
                                    {{ $item->advisoryActType?->translation->name }}
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
@includeIf('modals.delete-resource', ['resource' => trans_choice('custom.advisory_boards', 1)])
<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
