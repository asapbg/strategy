@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <div class="col-12 py-5 right-side-content">
            @if(isset($item))
                <h2 class="mb-5">{{ $item->name }}</h2>
                <div class="mb-3">
                    {!! $item->content !!}
                </div>
                @if($item->files->count() > 0)
                    <hr>
                    <div class="mb-3">
                        <h5>{{ trans_choice('custom.files', 2) }}</h5>
                        @foreach($item->files as $f)
                            <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
                                {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>


{{--    <section class="content container">--}}
{{--        <div class="page-title mb-md-3 mb-2 px-5">--}}
{{--            <h3 class="b-1 text-center">{{ $item->name }}</h3>--}}
{{--        </div>--}}
{{--        <div class="card card-light mb-4">--}}
{{--            <div class="card-body">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12">--}}
{{--                        {!! $item->content !!}--}}
{{--                    </div>--}}
{{--                    @if(isset($pages) && $pages->count())--}}
{{--                        @foreach($pages as $page)--}}
{{--                            <div class="mb-3">--}}
{{--                                <a class="d-inline-block a-fs mb-1" href="{{ route('page', ['section_slug' => $page->section->slug, 'slug' => $page->slug]) }}">{{ $page->name }}</a>--}}
{{--                                @if(!empty($page->short_content))--}}
{{--                                    <p class="p-fs">{{ $page->short_content }}</p>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                        {{ $pages->appends(request()->query())->links() }}--}}
{{--                        <hr>--}}
{{--                    @endif--}}
{{--                    @if($item->files->count())--}}
{{--                        @foreach($item->files as $f)--}}
{{--                            <div class="row">--}}
{{--                                <a class="w-100 mb-2" target="_blank" href="{{ route('download.page.file', ['file' => $f->id]) }}">--}}
{{--                                    {!! fileIcon($f->content_type) !!} {{ $f->description }}--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
@endsection
