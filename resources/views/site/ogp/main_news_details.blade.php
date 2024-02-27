@extends('layouts.site', ['fullwidth' => true])

@section('content')

<div class="row">
    @include('site.legislative_initiatives.side_menu')

    <div class="col-lg-10 right-side-content py-5" >
        <h2 class="mb-5">{{ $publication->title }}</h2>
        @include('site.ogp.partial.news_view')
    </div>

    @includeIf('modals.delete-resource', ['resource' => $title_singular])
</div>
@endsection
