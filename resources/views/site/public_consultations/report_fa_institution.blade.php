@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.public_consultations.side_menu')

    <div class="col-lg-10 right-side-content py-5" id="listContainer">
        @include('site.public_consultations.list_report_fa_institution')
    </div>
@endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                ajaxList('#listContainer');
            });
        </script>
@endpush