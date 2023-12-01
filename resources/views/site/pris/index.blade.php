@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">

        @include('site.pris.side_menu')
        <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5" id="listContainer">
            @include('site.pris.list')
        </div>

        @includeIf('modals.delete-resource', ['resource' => $title_singular])
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            ajaxList('#listContainer');
        });
    </script>
@endpush
