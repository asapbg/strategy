@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', trans_choice('custom.advisory_boards', 2))

@section('content')

<div class="row">
    @include('site.advisory-boards.side_menu_home')
    <div class="col-lg-10 right-side-content py-2" id="listContainer">
        @include('site.advisory-boards.list')
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
