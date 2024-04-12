@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <div class="col-12 py-5 right-side-content">
            @if(isset($item))
                <h2 class="mb-5">{{ $item->name }}</h2>
                <div class="mb-3">
                    {!! $item->content !!}
                </div>
                @php
                    $files = $item->files()
                        ->orderByRaw("array_position(array[".\App\Models\File::ORDER_BY_CONTENT_TYPE."], content_type)")
                        ->get();
                @endphp
                @if($files->count() > 0)
                    <hr>
                    <div class="mb-3 row">
                        <h5>{{ trans_choice('custom.files', 2) }}</h5>
                        @foreach($files as $f)
                            @if(in_array($f->content_type, \App\Models\File::IMG_CONTENT_TYPE))
                                {!! fileThumbnail($f) !!}
                            @else
                                <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
                                    {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
