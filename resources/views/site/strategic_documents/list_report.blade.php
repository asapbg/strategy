
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'subscribe' => false, 'export_excel' => true, 'export_pdf' => true])
    @include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'info' => __('site.sort_info_strategic_documents')])

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
        <div class="row table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('custom.title') }}</th>
                        <th>{{ __('site.strategic_document.level') }}</th>
                        <th>{{ trans_choice('custom.field_of_actions', 1) }}</th>
                        <th>{{ trans_choice('custom.authority_accepting_strategic', 1) }}</th>
                        <th>{{ __('custom.validity') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $document)
                    <tr>
                        <td>
                            <a href="{{ route('strategy-document.view', ['id' => $document->id]) }}"
                               class="text-decoration-none" title="{{ $document->title }}">
                                {{ $document->title }}
                            </a>
                        </td>
                        <td>
                            {{ $document->strategic_document_level_id ? __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($document->strategic_document_level_id)) : '---' }}
                        </td>
                        <td>
                            {{ $document->policyArea ? $document->policyArea->name : '---' }}
                        </td>
                        <td>
                            {{ $document->acceptActInstitution ? $document->acceptActInstitution->name : '---' }}
                        </td>
                        <td class="text-nowrap">
                            @if($document->document_date_accepted && $document->document_date_expiring)
                                {{ displayDate($document->document_date_accepted) .' - '. displayDate($document->document_date_expiring) }}
                            @elseif($document->document_date_accepted || $document->document_date_expiring)
                                @if($document->document_date_accepted)
                                    {{ __('custom.from') .' '.displayDate($document->document_date_accepted) }}
                                @else
                                    {{ __('custom.to') .' '.displayDate($document->document_date_expiring) }}
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
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
                    console.log(level);
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
