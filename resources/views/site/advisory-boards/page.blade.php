@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_home')

        <!-- Right side -->
        <div class="col-lg-10 py-5 right-side-content">
            @if(isset($page))
                <div class="mb-3">
                    {!! $page->content !!}
                </div>
                @if($page->files->count() > 0)
                    <hr>
                    <div class="mb-3">
                        <h5>{{ trans_choice('custom.files', 2) }}</h5>
                        @foreach($page->files as $f)
                            <a class="d-block mb-2" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
