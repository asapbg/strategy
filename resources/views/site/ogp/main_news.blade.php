@extends('layouts.site', ['fullwidth' => true])

@section('content')

<div class="row">
    @include('site.legislative_initiatives.side_menu')

    <div class="col-lg-10 right-side-content pb-5 pt-1 " id="listContainer">
        @include('site.ogp.main_news_list')
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
