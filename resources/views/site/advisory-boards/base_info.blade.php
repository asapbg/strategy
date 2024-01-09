@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_home')

        <!-- Right side -->
        <div class="col-lg-10 py-5 right-side-content">
            @if(isset($content))
                <div class="mb-3">
                    {!! $content !!}
                </div>
            @endif
        </div>
    </div>
@endsection
