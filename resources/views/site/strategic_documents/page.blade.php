@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.strategic_documents.side_menu')

        <!-- Right side -->
        <div class="col-lg-10 py-2 right-side-content">
            <h2 class="mb-4">
                {{ $page->name }}
            </h2>
            @if(isset($page))
                <div class="mb-3">
                    {!! $page->content !!}
                </div>
                @if($page->files->count() > 0)
                    <hr>
                    <div class="mb-3">
                        <h5>{{ trans_choice('custom.files', 2) }}</h5>
                        @foreach($page->files as $f)
{{--                            <a class="d-block mb-2" href="{{ route('admin.download.file', ['file' => $f->id]) }}">--}}
{{--                                {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}--}}
{{--                            </a>--}}
                            <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
                                {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
