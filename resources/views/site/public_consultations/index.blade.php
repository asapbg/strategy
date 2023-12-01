@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div id="listContainer">
        @include('site.public_consultations.list')
    </div>

@includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            ajaxList('#listContainer');
        });
    </script>
@endpush
