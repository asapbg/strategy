@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 pb-5 pt-1 right-side-content"  id="listContainer">
            @include('site.polls.list')
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
