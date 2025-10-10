
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-2">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'subscribe' => false, 'export_excel' => true, 'export_word' => true])
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
                        <th>{{ trans_choice('custom.nomenclature.strategic_document_type', 1) }}</th>
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
                            {{ $document->documentType ? $document->documentType->name : '---' }}
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
                            {{
                                $document->document_date_accepted && !str_contains($document->document_date_accepted, '9999')
                                    ? \Carbon\Carbon::parse($document->document_date_accepted)->format('d-m-Y')
                                    : \Carbon\Carbon::parse($document->created_at)->format('d-m-Y')
                            }}
                            -
                            {{
                                ($document->document_date_expiring && !str_contains($document->document_date_expiring, '9999'))
                                    ? \Carbon\Carbon::parse($document->document_date_expiring)->format('d-m-Y')
                                    : __('custom.unlimited')
                            }}
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
                categoriesControl();
            });
        </script>
    @endpush
