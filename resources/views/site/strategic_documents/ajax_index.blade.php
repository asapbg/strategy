@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Стратегически документи - вътрешна страница')

@section('content')
    <div class="row pb-5">
        @if(isset($pageTopContent) && !empty($pageTopContent->value))
            <div class="col-12 my-5">
                {!! $pageTopContent->value !!}
            </div>
        @endif
            <div class="col-md-12">
                <ul class=" tab nav nav-tabs mb-3" id="myTab">
                    <li class="nav-item pb-0">
                        <a href="#table-view" class="nav-link tablinks active" data-toggle="tab">{{ trans_choice('custom.table_view', 1) }}</a>
                    </li>
                    <li class="nav-item pb-0">
                        <a href="#tree-view" class="nav-link tablinks" data-toggle="tab">{{ trans_choice('custom.tree_view', 1) }}</a>
                    </li>
                </ul>
                @include('site.strategic_documents.search')
                @include('site.strategic_documents.search-script')
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="table-view">
                    </div>
                </div>
            </div>

        <div class="col-md-12">
            <!--
            <ul class=" tab nav nav-tabs mb-3" id="myTab">
                <li class="nav-item pb-0">
                    <a href="#table-view" class="nav-link tablinks active" data-toggle="tab">{{ trans_choice('custom.table_view', 1) }}</a>
                </li>
                <li class="nav-item pb-0">
                    <a href="#tree-view" class="nav-link tablinks" data-toggle="tab">{{ trans_choice('custom.tree_view', 1) }}</a>
                </li>
            </ul>
            -->
            <div id="overlay">
                <div id="spinner-container" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="tab-content">

                <div class="tab-pane fade show active" id="table-view">
                    <div class="row" id="pagination-container">
                    </div>
                    <!--
                    <div class="row">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination m-0">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                                <li class="page-item"><a class="page-link" href="#">25</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    -->
                </div>
                <div class="tab-pane fade" id="tree-view">
                    <div class="easy-tree">

                    </div>
                </div>

                <div class="tab-pane fade" id="tree-view">
                    <div class="easy-tree">
                        <ul>
                            <li class="parent_li">
                                <span>
                                    <span class="glyphicon"></span>
                                    <a href="#" class="main-color fs-18 fw-600" data-toggle="collapse"
                                       href="#multiCollapseExample1" role="button" aria-expanded="true"
                                       aria-controls="multiCollapseExample1">
                                        <i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>
                                        {{ trans_choice('custom.national', 1) }}
                                    </a>
                                </span>
                                <ul>
                                    @foreach ($categoriesData['national'] as $key => $documents)
                                        <li class="parent_li">
                                            <span>
                                                <a href="#" class="main-color fs-18" data-toggle="collapse"
                                                   data-target="#{{ $key }}">
                                                    <i class="fa-solid fa-arrow-right-to-bracket me-1 main-color"
                                                       title="Национални"></i>
                                                    {{ trans_choice('custom.central_level', 1) }}
                                                </a>
                                            </span>
                                            <ul class="collapse show" id="{{ $key }}">
                                                @if(isset($documents))
                                                    @foreach ($documents as $document)
                                                        <li class="active-node parent_li">
                                                            <span>
                                                                <a href="{{ route( 'strategy-document.view' , [$document->id]) }}">
                                                                     {{ $document->title }} {{ $document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('Y') : '' }} - {{ $document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('Y') : 'Безсрочен' }}
                                                                </a>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="parent_li">
                                <span>
                                    <span class="glyphicon"></span>
                                    <a href="#" class="main-color fs-18 fw-600" data-toggle="collapse"
                                       href="#multiCollapseExample1" role="button" aria-expanded="true"
                                       aria-controls="multiCollapseExample1">
                                        <i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>
                                        {{ trans_choice('custom.regional', 1) }}
                                    </a>
                                </span>
                                <ul>
                                    @foreach ($categoriesData['regional'] as $key => $documents)
                                        @php
                                            $categoryName = $key == 'district-level' ?  trans_choice('custom.area_level', 1) : trans_choice('custom.distrinct_level', 1);
                                        @endphp
                                        <li class="parent_li">
                                            <span>
                                                <a href="#" class="main-color fs-18" data-toggle="collapse"
                                                   data-target="#{{ $key }}">
                                                    <i class="fa-solid fa-arrow-right-to-bracket me-1 main-color"
                                                       title="{{ $documents[0]->title }}"></i>
                                                    {{ $categoryName }}
                                                </a>
                                            </span>
                                            <ul class="collapse show" id="{{ $key }}">
                                                @if(isset($documents))
                                                    @foreach ($documents as $document)
                                                        <li class="active-node parent_li">
                                                            <span>
                                                                <a href="{{ route( 'strategy-document.view' , [$document->id]) }}">
                                                                     {{ $document->title }} {{ $document->document_date_accepted ? \Carbon\Carbon::parse($document->document_date_accepted)->format('Y') : '' }} - {{ $document->document_date_expiring ? \Carbon\Carbon::parse($document->document_date_expiring)->format('Y') : 'Безсрочен' }}
                                                                </a>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.delete-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.strategic_documents', 1),
        'file_change_warning_txt'   => __('custom.are_you_sure_to_delete') . ' ' . Str::lower(trans_choice('custom.strategic_documents', 1)) . '?',
    ])
@endsection
@push('styles')
    <style>
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            pointer-events: none;
        }
        #overlay .spinner {
            pointer-events: none;
        }
    </style>
@endpush

