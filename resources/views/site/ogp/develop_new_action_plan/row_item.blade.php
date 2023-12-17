<div class="row mb-4" id="ogp-area-row-{{ $item->id }}">
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
                                <a href="{{ route('ogp.develop_new_action_plans.show', $item->id) }}" class="text-decoration-none" title="{{ $item->name }}">
                                    <h3>{{ $item->name }}</h3>
                                </a>
                            </div>
                            <div class="consult-item-header-edit">
                                @can('delete', $item)
                                    @php
                                        $modalId = 'modal_ogp_area_'. $item->id;
                                        $deleteUrl = route('admin.ogp.area.delete', $item->id);
                                        $rowId = 'ogp-area-row-'. $item->id;
                                        $warningTitle = __('ogp.comment_delete_title');
                                        $warningMessage = __('ogp.ogp_area_delete_warning');
                                    @endphp
                                    <x-modal.delete :modal_id="$modalId" :url="$deleteUrl" :row_id="$rowId" :title="$warningTitle" :warning_message="$warningMessage" >
                                        <a href="javascript:;" class="show-delete-modal" data-id="{{ $modalId }}" data-toggle="modal" data-target="#{{ $modalId }}">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>
                                        </a>
                                    </x-modal.delete>
                                @endcan
                                @can('update', $item)
                                <a href="{{ route('admin.ogp.area.edit', ['id' => $item->id]) }}">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                </a>
                                @endcan
                            </div>
                        </div>

                        <div class="status mt-2">
                            <span>{{ __('custom.status') }}: <span class="{{ $item->status->css_class }}">{{ $item->status->name }}</span></span>
                        </div>
                        <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">{{ __('custom.deadline') }}: </span> {{ displayDate($item->from_date) }} - {{ displayDate($item->to_date) }}
                                    </span>
                            <a href="{{ route('ogp.develop_new_action_plans.show', $item->id) }}" title="{{ $item->name }}">
                                <i class="fas fa-arrow-right read-more"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
