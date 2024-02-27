@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.legislative_initiatives.side_menu')

    <div class="col-lg-10 py-5 right-side-content" id="listContainer">
        @include('site.ogp.plans_list')

{{--        @include('site.ogp.develop_new_action_plan.filter')--}}
{{--        @include('site.ogp.search_btn_actions')--}}
{{--        @include('site.ogp.develop_new_action_plan.sort')--}}
{{--        <div class="row justify-content-end my-3">--}}
{{--            <div class="col-md-4"></div>--}}
{{--            <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">--}}
{{--                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой резултати:</label>--}}
{{--                <select class="form-select w-auto" id="paginationResults">--}}
{{--                    <option value="5">5</option>--}}
{{--                    <option value="20">20</option>--}}
{{--                    <option value="30">30</option>--}}
{{--                    <option value="40">40</option>--}}
{{--                    <option value="50">50</option>--}}
{{--                    <option value="100">100</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            @foreach($items as $item)--}}
{{--                @include('site.ogp.develop_new_action_plan.row_item', compact('item', 'route_view_name'))--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            {{ $items->links() }}--}}
{{--        </div>--}}
    </div>
</div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            ajaxList('#listContainer');
        });
    </script>
@endpush
