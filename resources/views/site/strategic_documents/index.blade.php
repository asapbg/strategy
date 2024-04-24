@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row">
    @include('site.strategic_documents.side_menu')

    <div class="col-lg-10 right-side-content pb-5 pt-1 " id="listContainer">
        @include('site.strategic_documents.list')
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
