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

        <ul class=" tab nav nav-tabs mb-3" id="myTab">

            <li class="nav-item pb-0">
                <a href="#table-view" class="nav-link tablinks active"
                   data-toggle="tab">{{ trans_choice('custom.table_view', 1) }}</a>
            </li>
            <li class="nav-item pb-0">
                <a href="#tree-view" class="nav-link tablinks"
                   data-toggle="tab">{{ trans_choice('custom.tree_view', 1) }}</a>
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

        <div class="tab-content">

            <div class="tab-pane fade show active" id="table-view">
                <div class="row" id="pagination-container">
                </div>
            </div>
            <div class="tab-pane fade" id="tree-view">
                <div class="easy-tree">

                </div>
            </div>
        </div>
    </div>
    </div>

    @php
        $cancel_btn_text = $cancel_btn_text ?? '';
        $continue_btn_text = $continue_btn_text ?? '';
        $title_text = $title_text ?? '';
        $file_change_warning_txt = $file_change_warning_txt ?? '';
    @endphp
@endsection
@push('scripts')

    <script type="text/javascript">
        $(document).on('click', '.open-delete-modal', function () {
            const form = $(this).parent().find('form').attr('name');
            let cancelBtnTxt = @json($cancel_btn_text);
            let continueTxt = @json($continue_btn_text);
            let titleTxt =  @json($title_text);
            let fileChangeWarningTxt = @json($file_change_warning_txt);

            new MyModal({
                title: titleTxt,
                footer: '<button class="btn btn-sm btn-success ms-3" onclick="' + form + '.submit()">' + continueTxt + '</button>' +
                    '<button class="btn btn-sm btn-secondary closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                body: '<div class="alert alert-danger">' + fileChangeWarningTxt + '</div>',
            });
        });
    </script>

@endpush
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

