@extends('layouts.site', ['fullwidth' => true])

{{--@section('pageTitle', __('custom.open_government_partnership'))--}}

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')

    <div class="col-lg-10 py-5 right-side-content">
        @include('site.ogp.list.filter')
        @include('site.ogp.search_btn_actions')
        @include('site.ogp.list.sort')
        <div class="row mb-2">
            <div class="col-12 mt-2">
                <div class="info-consul text-start">
                    <p class="fw-600">
                        Общо 98 резултата
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            @for($i = 1; $i<=10; $i++)
                @include('site.ogp.list.row_item')
            @endfor
        </div>

        <div class="row mb-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination m-0">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">57</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>
@endsection
