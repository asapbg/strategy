<div class="row">
    <div class="col-md-12">
        @foreach($executors as $executor)
            <div class="custom-card p-3 mb-3">
                <div class="row m-0">
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
                <div class="row single-record">
                    <div class="col-md-12 mb-2">
                        <p class="fs-18 fw-600 mb-1">{{ __('Name of contractor') }}</p>
                        <p>
                            <a href="#" class="main-color text-decoration-none">{{ $executor->translation?->contractor_name }}</a>
                        </p>
                    </div>
                    <div class="col-md-3 mb-2">
                        <p class="fs-18 fw-600 mb-1">{{ __('Name of executor') }}</p>
                        <p>
                            <a href="#" class="main-color text-decoration-none">{{ $executor->translation?->executor_name }}</a>
                        </p>
                    </div>
                    <div class="col-md-3 mb-2">
                        <p class="fs-18 fw-600 mb-1">{{ __('custom.eik') }}</p>
                        <p>
                            <a href="#" class="main-color text-decoration-none">{{ $executor->eik }}</a>
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
                        <p>{!! $executor->translation?->contract_subject !!}</p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <p class="fs-18 fw-600 mb-1">{{ __('custom.services_description') }}</p>
                        <p>{!! $executor->translation?->services_description !!}</p>
                    </div>

                    <div class="col-md-8">
                        <p class="mb-0">
                            <strong>Информация за поръчката:</strong>
                            <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0 text-end">
                            <a href="#" title="ТЕСТ">
                                <i class="fas fa-arrow-right read-more text-end"></i><span class="d-none">Линк</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="ajax-pagination" class="row">
        <div class="card-footer mt-2">
            @if($executors->count() > 0 && $executors instanceof Illuminate\Pagination\LengthAwarePaginator)
                {{ $executors->appends(request()->query())->links() }}
            @endif
        </div>
    </div>

</div>