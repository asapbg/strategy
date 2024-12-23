<div id="sd-total-data" data-total="{{ $strategicDocuments->total() }}" class="d-none"></div>
@foreach($strategicDocuments as $sd)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-img-holder">
                        <i class="{{ $sd->policyArea ? $sd->policyArea->icon_class : 'fas fa-certificate' }} light-blue"></i>
                    </div>
                    <div class="consult-body">
                        <div href="{{ route('strategy-document.view', $sd->id) }}" class="consul-item">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="{{ route('strategy-document.view', $sd->id) }}" class="text-decoration-none"
                                       title="{{ $sd->translation?->title }}">
                                        <h3 class="strip-header-words">
                                            {!! $sd->translation?->title !!}
                                        </h3>
                                    </a>
                                </div>

                                <div class="consult-item-header-edit">
                                    @can('delete', $sd)
                                        <a href="javascript:;"
                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"
                                           data-target="#modal-delete-resource"
                                           data-title_singular="{{ trans_choice('custom.strategic_documents', 1) }}"
                                           data-resource-id="{{ $sd->id }}"
                                           data-resource-name="{{ $sd->title }}"
                                           data-resource-delete-url="{{ route('admin.strategic_documents.delete', $sd) }}"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                        </a>
                                    @endcan
                                    @can('update', $sd)
                                        <a href="{{ route('admin.strategic_documents.edit', $sd) }}" target="_blank">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                <span class="d-none">{{ __('custom.edit') }}</span>
                                            </i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            @if ($sd->policyArea)
                                @if($sd->policyArea->parentid == \App\Models\FieldOfAction::CATEGORY_NATIONAL)
                                    @php($searchField = 'fieldOfActions')
                                @elseif($sd->policyArea->parentid == \App\Models\FieldOfAction::CATEGORY_MUNICIPAL)
                                    @php($searchField = 'municipalities')
                                @else
                                    @php($searchField = 'areas')
                                @endif
                                <a href="{{ route('strategy-documents.index').'?'.$searchField.'[]='.$sd->policyArea->id }}"  target="_blank"
                                   title="{{ $sd->policyArea->translation?->name }}" class="text-decoration-none mb-3"
                                >
                                    {{ $sd->policyArea->translation?->name }}
                                </a>
                            @else
                                ---
                            @endif

                            <div class="meta-consul mt-2">
                                <span class="text-secondary">
                                    {{
                                        $sd->document_date_accepted || $sd->document_date_expiring
                                            ? ($sd->document_date_accepted ? displayDate($sd->document_date_accepted) . ' г.' : __('custom.date_expring_indefinite')) . ' - ' . ($sd->document_date_expiring ? displayDate($sd->document_date_expiring) . ' г.' : __('custom.date_expring_indefinite'))
                                            : __('custom.date_expring_indefinite')
                                    }}
                                </span>

                                <a href="{{ route('strategy-document.view', $sd->id) }}" title="{{ $sd->translation?->title }}">
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
<div id="sd_pagination" class="ajax_pagination row mb-4" data-id="sd" @if($strategicDocuments->total() <= \App\Models\StrategicDocument::HOME_PAGINATE) style="margin-top: 75px;" @endif>
    @desktop
        @if($strategicDocuments->count() > 0 && $strategicDocuments instanceof Illuminate\Pagination\LengthAwarePaginator)
            {{ $strategicDocuments->onEachSide(2)->appends(request()->query())->links() }}
        @endif
    @elsedesktop
        @if($strategicDocuments->count() > 0 && $strategicDocuments instanceof Illuminate\Pagination\LengthAwarePaginator)
            {{ $strategicDocuments->onEachSide(0)->appends(request()->query())->links() }}
        @endif
    @enddesktop
</div>
