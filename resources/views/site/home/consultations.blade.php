@foreach($consultations as $consultation)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-img-holder">
                        <i class="{{ $consultation->fieldOfAction?->icon_class }} gr-color"></i>
                    </div>
                    <div class="consult-body">
                        <div href="{{ route('public_consultation.view', $consultation->id) }}" class="consul-item">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="{{ route('public_consultation.view', $consultation->id) }}" class="text-decoration-none"
                                       title="{{ $consultation->translation?->title }}">
                                        <h3 class="strip-header-words">
                                            {!! $consultation->translation?->title !!}
                                        </h3>
                                    </a>
                                </div>

                                <div class="consult-item-header-edit">
                                    @can('delete', $consultation)
                                        <a href="javascript:;"
                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $consultation->id }}"
                                           data-resource-name="{{ $consultation->title }}"
                                           data-resource-delete-url="{{ route('admin.consultations.public_consultations.delete', $consultation) }}"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                        </a>
                                    @endcan
                                    @can('update', $consultation)
                                        <a href="{{ route('admin.consultations.public_consultations.edit', $consultation) }}" target="_blank">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                <span class="d-none">{{ __('custom.edit') }}</span>
                                            </i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            @if ($consultation->fieldOfAction)
                                <a href="{{ route('public_consultation.index').'?fieldOfAction='.$consultation->fieldOfAction->id }}"  target="_blank"
                                   title="{{ $consultation->fieldOfAction->translation?->name }}" class="text-decoration-none mb-3"
                                >
                                    {{ $consultation->fieldOfAction->translation?->name }}
                                </a>
                            @else
                                ---
                            @endif

                            <div class="meta-consul mt-2">
                                <span class="text-secondary">
                                    {{ displayDate($consultation->open_from) }} г. - {{ displayDate($consultation->open_to) }} г.
                                    | {{ $consultation->comments->count() }} <i class="far fa-comment text-secondary"></i>
                                </span>

                                <a href="{{ route('public_consultation.view', $consultation->id) }}" title="{{ $consultation->translation?->title }}">
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

<div id="consultations_pagination" class="ajax_pagination row mb-4" data-id="consultations" @if(!($initiatives->total() > \App\Models\Consultations\PublicConsultation::HOME_PAGINATE)) style="margin-top: 75px;" @endif>
    @desktop
    @if($consultations->count() > 0 && $consultations instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $consultations->onEachSide(2)->appends(request()->query())->links() }}
    @endif
    @elsedesktop
    @if($consultations->count() > 0 && $consultations instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $consultations->onEachSide(0)->appends(request()->query())->links() }}
    @endif
    @enddesktop
</div>
