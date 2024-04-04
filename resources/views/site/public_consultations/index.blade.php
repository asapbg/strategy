@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.public_consultations.side_menu')

        <div class="col-lg-10 right-side-content py-5" id="listContainer">
            @include('site.public_consultations.list')
        </div>
        @endsection
@section('content')

@includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            ajaxList('#listContainer');
        });
    </script>
@endpush
