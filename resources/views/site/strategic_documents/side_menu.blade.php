<div class="col-lg-2 side-menu pt-2 mt-1" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ __('custom.home') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2">
                                <a href="{{ route('strategy-documents.index') }}" class="@if(request()->route()->getName() == 'strategy-documents.index' || request()->route()->getName() == 'strategy-document.view') active-item-left text-white p-1 @else link-dark @endif text-decoration-none"
                                   >{{ __('custom.all_strategic_documents') }}</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('strategy-documents.tree') }}" class="@if(request()->route()->getName() == 'strategy-documents.tree') active-item-left text-white p-1 @else link-dark @endif text-decoration-none"
                                   >{{ __('custom.all_strategic_documents_tree_view') }}</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('strategy-documents.reports') }}" class="@if(request()->route()->getName() == 'strategy-documents.reports') active-item-left text-white p-1 @else link-dark @endif text-decoration-none"
                                   >{{ __('site.strategic_document.all_documents_report') }}</a>
                            </li>

                            <li class="mb-2">
                                <a href="{{ route('strategy-document.info') }}" class="@if(str_contains(url()->current(), 'strategy-documents/'.\App\Models\Page::STRATEGIC_DOCUMENT_INFO)) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('site.base_info') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('strategy-document.documents') }}" class="@if(str_contains(url()->current(), 'strategy-documents/'.\App\Models\Page::STRATEGIC_DOCUMENT_DOCUMENTS)) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.documents', 2) }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('strategy-document.contacts') }}" class="@if(str_contains(url()->current(),'contacts')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('custom.contact_info') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <hr class="custom-hr">
    </div>
</div>
