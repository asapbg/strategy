<div class="row">
    <div class="col-md-12">
        @foreach($executors as $executor)
            @php($translation = $executor->translation)
            <div class="custom-card card p-3 mb-3">
                <div class="row m-0" style="position: relative;z-index: 900">
                    <div class="col-md-12 text-end p-0">
                        @canany(['manage.*', 'manage.executors'])
                            <a href="javascript:;"
                               class="js-toggle-delete-resource-modal"
                               data-target="#modal-delete-resource"
                               data-resource-id="{{ $executor->id }}"
                               data-resource-title="{{ $executor->title }}"
                               data-resource-delete-url="{{ route('admin.executors.destroy', $executor->id )}}"
                               data-toggle="tooltip"
                               title="{{ __('custom.deletion') }}">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2" role="button"></i>
                            </a>
                            <a href="{{ route('admin.executors.edit', $executor->id) }}" title="Редактиране" target="_blank">
                                <i class="fas fa-pen-to-square float-end main-color fs-4"></i>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <p class="fs-18 fw-600 mb-1">{{ __('Name of contractor') }}</p>
                    <a href="#collapse_{{ $executor->id }}" class="main-color text-decoration-none d-block accordion-link" data-toggle="collapse">
                        <span>{{ $executor->institution?->name ?? $translation?->contractor_name }}</span>
                    </a>
                </div>
                <div id="collapse_{{ $executor->id }}" class="collapse" data-parent="#accordion">
                    <div class="row single-record">
                        <div class="col-md-12 mb-2 d-none">
                            <p class="fs-18 fw-600 mb-1">{{ __('Name of contractor') }}</p>
                            <p>
                                <a href="javascript:;" class="main-color text-decoration-none filter_link"
                                   data-filter="institutions" data-id="{{ $executor->institution?->id }}"
                                >
                                    {{ $executor->institution?->name ?? $translation?->contractor_name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('Name of executor') }}</p>
                            <p>
                                <a href="javascript:;" class="main-color text-decoration-none filter_link" data-filter="executor_name">
                                    {{ $translation?->executor_name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('custom.eik') }}</p>
                            <p>
                                <a href="javascript:;" class="main-color text-decoration-none filter_link" data-filter="eik">
                                    {{ $executor->eik }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('Contract date') }}</p>
                            <p>{{ displayDate($executor->contract_date) }}</p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('custom.price_with_vat') }}</p>
                            <p>{{ $executor->price }}</p>
                        </div>
                        <div class="col-md-12 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('custom.contract_subject') }}</p>
                            <p>{!! $translation?->contract_subject !!}</p>
                        </div>
                        <div class="col-md-12 mb-2">
                            <p class="fs-18 fw-600 mb-1">{{ __('custom.services_description') }}</p>
                            <p>{!! $translation?->services_description !!}</p>
                        </div>

                        @if ($translation?->hyperlink)
                            <div class="col-md-8">
                                <p class="mb-0">
                                    <strong>{{ __('Order information') }}:</strong>
                                    <a href="{{ $translation?->hyperlink }}" class="text-decoration-none"
                                       @if($translation?->hyperlink) target="_blank" @endif title="ЦАИС"
                                    >ЦАИС</a>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0 text-end">
                                    <a href="{{ $translation?->hyperlink }}" title="линк към ЦАИС или друг източник"
                                       @if($translation?->hyperlink) target="_blank" @endif
                                    >
                                        <i class="fas fa-arrow-right read-more text-end"></i><span class="d-none">Линк</span>
                                    </a>
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="ajax-pagination" class="row">
        <div class="card-footer mt-2">
            @desktop
                @if($executors->count() > 0 && $executors instanceof Illuminate\Pagination\LengthAwarePaginator)
                    {{ $executors->appends(request()->query())->links() }}
                @endif
            @elsedesktop
                @if($executors->count() > 0 && $executors instanceof Illuminate\Pagination\LengthAwarePaginator)
                    {{ $executors->onEachSide(0)->appends(request()->query())->links() }}
                @endif
            @enddesktop
        </div>
    </div>

</div>
