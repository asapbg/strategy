@extends('layouts.site', ['fullwidth' => true])

@section('content')

<div class="row">
    @include('site.advisory-boards.side_menu_home')

    <div class="col-lg-10 right-side-content py-5" >
        @include('site.advisory-boards.partial.news_view')
    </div>

    @includeIf('modals.delete-resource', ['resource' => $title_singular])
</div>
@endsection
