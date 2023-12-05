@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">

        @include('site.pris.side_menu')
        <div class="col-lg-10 right-side-content py-5" id="listContainer">
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
