@php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\OgpArea::class))
@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.ogp.plan.create')])
@include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'info' => __('site.sort_info_ogp_plans')])
<div class="col-12 mt-2 text-end">
    <input type="hidden" id="subscribe_model" value="App\Models\OgpPlan">
    <input type="hidden" id="subscribe_route_name" value="{{ request()->route()->getName() }}">
    @includeIf('site.partial.subscribe-buttons', ['subscribe_list' => true])
</div>
<div class="row mb-2">
{{--    <div class="col-md-6 mt-2">--}}
{{--        <div class="info-consul text-start">--}}
{{--            <p class="fw-600">--}}
{{--                {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}--}}
{{--            </p>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
</div>

@if($items->count())
    @foreach($items as $ogp)
        <div class="row mb-4" id="ogp-plan-row-{{ $ogp->id }}">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="{{ route($route_view_name, $ogp->id) }}" class="text-decoration-none" title="{{ $ogp->name }}">
                                            <h3>{{ $ogp->name }}</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        @can('delete', $ogp)
                                            @php($modalId = 'modal_ogp_plan_'. $ogp->id)
                                            @php($deleteUrl = route('admin.ogp.plan.delete', $ogp->id))
                                            @php($rowId = 'ogp-plan-row-'. $ogp->id)
                                            @php($warningTitle = __('ogp.ogp_plan_delete_title'))
                                            @php($warningMessage = __('ogp.ogp_plan_delete_warning'))
                                            <x-modal.delete :modal_id="$modalId" :url="$deleteUrl" :row_id="$rowId" :title="$warningTitle" :warning_message="$warningMessage" >
                                                <a href="javascript:;" class="show-delete-modal" data-id="{{ $modalId }}" data-toggle="modal" data-target="#{{ $modalId }}">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>
                                                </a>
                                            </x-modal.delete>
                                        @endcan
                                        @can('update', $ogp)
                                            <a href="{{ route('admin.ogp.plan.edit', ['id' => $ogp->id]) }}">
                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>{{ __('custom.status') }}: <span class="{{ $ogp->status->css_class }}">{{ $ogp->status->name }}</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">{{ __('custom.deadline') }}: </span> {{ displayDate($ogp->from_date) }} - {{ displayDate($ogp->to_date) }}
                                    </span>
                                    <a href="{{ route($route_view_name, $ogp->id) }}" title="{{ $ogp->name }}">
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
@endif
@if(isset($nationalOldPlans) && sizeof($nationalOldPlans))
    @foreach($nationalOldPlans as $oldPlan)
        <div class="row mb-4" id="ogp-plan-row-old-{{ $oldPlan['id'] }}">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="{{ $oldPlan['url'] }}" class="text-decoration-none" title="{{ $oldPlan['label'] }}">
                                            <h3>{{ $oldPlan['label'] }}</h3>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>{{ __('custom.status') }}: <span class="{{ $oldPlanStatus->css_class }}">{{ $oldPlanStatus->name }}</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                        <span class="text-secondary">
                                        <span class="text-dark">{{ __('custom.deadline') }}: </span> {{ \App\Enums\OldNationalPlanEnum::fromDateByValue($oldPlan['id']) }} - {{ \App\Enums\OldNationalPlanEnum::toDateByValue($oldPlan['id']) }}
                                        </span>
                                    <a href="{{ $oldPlan['url'] }}" title="{{ $oldPlan['label'] }}">
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
@endif

<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
