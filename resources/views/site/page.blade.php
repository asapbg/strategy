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
@endsection
