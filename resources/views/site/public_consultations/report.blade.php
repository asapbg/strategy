@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.public_consultations.side_menu')

    <div class="col-lg-10 right-side-content pb-5 pt-1 " id="listContainer">
        @include('site.public_consultations.list_report')
    </div>
@endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                ajaxList('#listContainer');
            });
        </script>
@endpush
