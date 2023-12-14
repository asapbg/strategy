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
                                       title="{{ $initiative->operationalProgram?->value }}">
                                        <h3 class="strip-header-words">
                                            {{ $initiative->operationalProgram?->value }}
                                        </h3>
                                    </a>
                                </div>

                                <div class="consult-item-header-edit">
                                    @if(
                                        auth()->check() &&
                                        auth()->user()->id === $initiative->author_id
                                        && $initiative->getStatus($initiative->status)->value === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value
                                    )
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

                                        <a href="{{ route('legislative_initiatives.edit', $initiative) }}">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                               role="button" title="{{ __('custom.edit') }}">
                                            </i>
                                        </a>
                                    @endif
                                </div>
                            </div>
{{--                            <a href="#" title="{{ $initiative->operationalProgram?->institution }}"--}}
{{--                               class="text-decoration-none text-capitalize mb-3">--}}
{{--                                {{ $initiative->operationalProgram?->institution }}--}}
{{--                            </a>--}}
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

<div id="initiatives_pagination" class="ajax_pagination row mb-4" data-id="initiatives">
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
<div class="row mb-4 d-none">
    <div class="col-md-12">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-img-holder">
                    <i class="fa-solid fa-hospital light-blue"></i>
                </div>
                <div class="consult-body">
                    <div href="#" class="consul-item">
                        <div class="consult-item-header d-flex justify-content-between">
                            <div class="consult-item-header-link">
                                <a href="#" class="text-decoration-none"
                                   title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                    <h3 class="strip-header-words">Промяна в нормативната уредба
                                        на търговията на дребно с лекарствени продукти</h3>
                                </a>
                            </div>
                            <div class="consult-item-header-edit">
                                <a href="#">
                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                       role="button" title="Изтриване"></i>
                                </a>
                                <a href="#">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                       role="button" title="Редакция">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <a href="#" title=" Партньорство за открито управление"
                           class="text-decoration-none mb-3">
                            Здравеопазване
                        </a>
                        <div class="meta-consul mt-2">
                            <span>Подкрепено: <span class="voted-li">585 пъти</span></span>
                            <a href="#"
                               title="Проект на заповед, която се издава от директора на Агенция,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците">
                                <i class="fas fa-arrow-right read-more"><span
                                        class="d-none">Линк</span></i>
                            </a>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
