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

                    @include('components.delete-modal', [
                        'cancel_btn_text'           => __('custom.cancel'),
                        'continue_btn_text'         => __('custom.continue'),
                        'title_text'                => __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.strategic_documents', 1),
                        'file_change_warning_txt'   => __('custom.are_you_sure_to_delete') . ' ' . Str::lower(trans_choice('custom.strategic_documents', 1)) . '?',
                    ])
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
            <!--
            <div id="overlay">
                <div id="spinner-container" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            -->
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
            </div>
        </div>
    </div>

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

