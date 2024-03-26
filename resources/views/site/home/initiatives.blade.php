<div id="initiatives-total-data" data-total="{{ $initiatives->total() }}" class="d-none"></div>
@foreach($initiatives as $initiative)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-img-holder">
                        <i class="fa-solid fa-hospital light-blue"></i>
                    </div>
                    <div class="consult-body">
                        <div href="{{ route('legislative_initiatives.view', $initiative->id) }}" class="consul-item">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="{{ route('legislative_initiatives.view', $initiative->id) }}" class="text-decoration-none"
                                       title="{{ $initiative->law?->name }}">
                                        <h3 class="strip-header-words">
                                            {{ $initiative->law?->name }}
                                        </h3>
                                    </a>
                                </div>

                                <div class="consult-item-header-edit">
                                    @can('delete', $initiative)
                                        <form class="d-none"
                                              method="POST"
                                              action="{{ route('legislative_initiatives.delete', $initiative) }}"
                                              name="DELETE_ITEM_{{ $initiative->id }}"
                                        >
                                            @csrf
                                        </form>

                                        <a href="#" class="open-delete-modal">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                               role="button" title="{{ __('custom.deletion') }}"></i>
                                        </a>

{{--                                        <a href="{{ route('legislative_initiatives.edit', $initiative) }}">--}}
{{--                                            <i class="fas fa-pen-to-square float-end main-color fs-4"--}}
{{--                                               role="button" title="{{ __('custom.edit') }}">--}}
{{--                                            </i>--}}
{{--                                        </a>--}}
                                    @endcan
                                </div>
                            </div>
                            <div class="meta-consul">
                                <span>Коментирано: <span class="voted-li">{{ $initiative->comments->count() }} пъти</span></span>
                            </div>
                            <div class="meta-consul mt-2">
                                <span>Подкрепено: <span class="voted-li">{{ $initiative->likes->count() }} пъти</span></span>
                                <a href="{{ route('legislative_initiatives.view', $initiative->id) }}" title="">
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
<div id="initiatives_pagination" class="ajax_pagination row mb-4" data-id="initiatives" @if($initiatives->total() <= \App\Models\LegislativeInitiative::HOME_PAGINATE) style="margin-top: 75px;" @endif>
    @desktop
        @if($initiatives->count() > 0 && $initiatives instanceof Illuminate\Pagination\LengthAwarePaginator)
            {{ $initiatives->onEachSide(2)->appends(request()->query())->links() }}
        @endif
    @elsedesktop
        @if($initiatives->count() > 0 && $initiatives instanceof Illuminate\Pagination\LengthAwarePaginator)
            {{ $initiatives->onEachSide(0)->appends(request()->query())->links() }}
        @endif
    @enddesktop
</div>
