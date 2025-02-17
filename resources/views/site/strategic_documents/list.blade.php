
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-2">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\StrategicDocument::class))
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.strategic_documents.edit', ['item' => 0])])
    @include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'info' => __('site.sort_info_strategic_documents')])
    <input type="hidden" id="subscribe_model" value="App\Models\StrategicDocument">
    <input type="hidden" id="subscribe_route_name" value="{{ request()->route()->getName() }}">
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

    @if($items->count())
        @foreach($items as $document)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-img-holder">
                                <i class="fa-solid fa-circle-nodes dark-blue"></i>
                            </div>
                            <div class="consult-body">
                                <div href="#" class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('strategy-document.view', ['id' => $document->id]) }}"
                                               class="text-decoration-none" title="{{ $document->title }}">
                                                <h3 class="mb-2">{{ $document->title }}</h3>
                                            </a>
                                        </div>
                                        <div class="consult-item-header-edit">
                                            @can('delete', $document)
                                                <a href="javascript:;"
                                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $document->id }}"
                                                   data-resource-name="{{ $document->title }}"
                                                   data-resource-delete-url="{{ route( $deleteRouteName , [$document->id]) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                </a>
                                            @endcan
                                            @can('update', $document)
                                                <a href="{{ route( $editRouteName , [$document->id]) }}" target="_blank">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                    </i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
{{--                                    {{ $document->category }}--}}
                                    @if($document->policyArea)
                                        @php($searchFieldPolicy = $document->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value ? 'fieldOfActions' : ($document->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::AREA->value ? 'areas' : 'municipalities'))

                                        <a href="{{ route('strategy-documents.index').'?'.$searchFieldPolicy.'[]='.$document->policyArea->id }}"
                                           title="{{ $document->policyArea->name }}" class="text-decoration-none mb-2 d-block">
                                            <i class="text-primary {{ $document->policyArea->icon_class }} me-1" title="{{ $document->policyArea->name }}"></i>
                                            {{ $document->policyArea->name }}
                                        </a>
                                    @endif
                                    @if($document->documentType)
                                        @can('view',  $document->documentType)
                                            <a href="{{ route('admin.nomenclature.strategic_document_type.edit', [$document->documentType?->id]) }}"
                                               class="main-color text-decoration-none mb-2 d-block">
                                                 <span class="obj-icon-info me-2">
                                                <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $document->documentType->name }} </span>
                                            </a>
                                        @else
                                            <a href="#"
                                               class="main-color text-decoration-none fs-18">
                                                 <span class="obj-icon-info me-2">
                                                <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>{{ $document->documentType->name }} </span>
                                            </a>
                                        @endcan
                                    @endif

                                    <div class="meta-consul mt-2">
                                                <span class="text-secondary d-flex flex-row align-items-baseline lh-normal">
                                                    <i class="far fa-calendar text-secondary me-1" title="{{ __('custom.period') }}"></i>
                                                    <!--
                                                    {{ $document->document_date ? $document->document_date . ' г.' : __('custom.no_terms') }}
                                                    -->
                                                    {{ $document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('d-m-Y') : '' }}
                                                    -
                                                    {{ $document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('d-m-Y') : __('custom.unlimited') }}

                                                </span>
                                        <a href="{{ route( 'strategy-document.view' , [$document->id]) }}"
                                           title="{{ $document->title }}">
                                            <i class="fas fa-arrow-right read-more mt-2"></i>
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

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                let centralLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>';
                let areaLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::AREA->value; ?>';
                let municipalityLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value; ?>';

                let fieldOfActions = $('#fieldOfActions');
                let areas = $('#areas');
                let municipalities = $('#municipalities');

                function categoriesControl(){
                    let level = $('#level');
                    let levelVals = level.val();
                    // console.log(level.val(), centralLevel, levelVals.indexOf(centralLevel) != -1 || !levelVals.length);
                    if(levelVals.indexOf(centralLevel) != -1 || !levelVals.length){
                        fieldOfActions.parent().parent().parent().removeClass('d-none');
                    } else{
                        fieldOfActions.parent().parent().parent().addClass('d-none');
                        fieldOfActions.val('');
                    }
                    // console.log(level.val(), areaLevel, levelVals.indexOf(areaLevel) != -1 || !levelVals.length);
                    if(levelVals.indexOf(areaLevel) != -1 ||!levelVals.length){
                        areas.parent().parent().parent().removeClass('d-none');
                    } else{
                        areas.parent().parent().parent().addClass('d-none');
                        areas.val('');
                    }
                    // console.log(level.val(), municipalityLevel, levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length);
                    if(levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length){
                        municipalities.parent().parent().parent().removeClass('d-none');
                    } else{
                        municipalities.parent().parent().parent().addClass('d-none');
                        municipalities.val('');
                    }
                }

                $(document).on('change', '#level', function (){
                    categoriesControl();
                });
                categoriesControl();
            });
        </script>
    @endpush
