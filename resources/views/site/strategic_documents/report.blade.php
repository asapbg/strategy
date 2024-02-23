@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.strategic_documents.side_menu')

    <div class="col-lg-10 right-side-content py-5" id="listContainer">
        @include('site.strategic_documents.list_report')
    </div>
@endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                ajaxList('#listContainer');
            });
        </script>
@endpush
