@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_home')

        <!-- Right side -->
        <div class="col-lg-10 py-2 right-side-content">
            @if(isset($page))
                <div class="mb-3">
                    {!! $page->content !!}
                </div>
                @if($page->files->count() > 0)
                    <hr>
                    <div class="mb-3">
                        <h5>{{ trans_choice('custom.files', 2) }}</h5>
                        @foreach($page->files as $f)
                            @php
                                $file_name = fileIcon($f->content_type)." ".$f->{'description_'.$f->locale};
                            @endphp
                            @include('site.partial.file_preview_or_download', ['file' => $f, 'file_name' => $file_name])
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
