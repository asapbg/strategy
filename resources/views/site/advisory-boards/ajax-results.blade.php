<div class="row">
    <div class="col-md-12">
        @if(isset($advisory_boards) && $advisory_boards->count() > 0)
            @foreach($advisory_boards as $board)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-body">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('advisory-boards.view', ['item' => $board]) }}"
                                               class="text-decoration-none"
                                               title="{{ $board->name }}">
                                                <h3>{{ $board->name }}</h3>
                                            </a>
                                        </div>

                                        @if($board->active && auth()->user())
                                            @can('update', $board)
                                                <div class="consult-item-header-edit">
                                                    <a href="{{ route('admin.advisory-boards.index') . '?keywords=' . $board->id . '&status=1' }}">
                                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                           role="button" title="{{ __('custom.delete') }}"></i>
                                                    </a>
                                                    <a href="{{ route('admin.advisory-boards.edit', ['item' => $board]) }}"
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
                                                @php $class = $board->active ? 'active-ks' : 'inactive-ks' @endphp
                                                <span
                                                    class="{{ $class }}">{{ $board->active ? __('custom.active') : __('custom.inactive_m') }}</span>
                                            </span>

                                        <a href="{{ route('advisory-boards.view', ['item' => $board]) }}">
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
    </div>

    <div id="ajax-pagination" class="row">
        <div class="card-footer mt-2">
            @desktop
            @if($advisory_boards->count() > 0 && $advisory_boards instanceof Illuminate\Pagination\LengthAwarePaginator)
                {{ $advisory_boards->appends(request()->query())->links() }}
            @endif
            @elsedesktop
            @if($advisory_boards->count() > 0 && $advisory_boards instanceof Illuminate\Pagination\LengthAwarePaginator)
                {{ $advisory_boards->onEachSide(0)->appends(request()->query())->links() }}
            @endif
            @enddesktop
        </div>
    </div>
</div>
