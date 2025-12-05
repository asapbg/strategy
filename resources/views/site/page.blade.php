@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <div class="col-12 pt-5 pb-2">
            @if(isset($item))
                <h2 class="mb-2">{{ $item->name }}</h2>
                @if(!empty($item->content))
                    <div class="mb-3">
                        {!! $item->content !!}
                    </div>
                @endif
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
    @if($item && $item->is_system && $item->system_name == \App\Models\Page::VIDEO_INSTRUCTIONS)
        <div class="row">
            @foreach(config('instructions.videos') as $key => $section)
                <div class="col-md-6 p-3">
                    <div class="accordion" id="accordionExample_{{ $key }}">
                        @foreach($section as $name)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_{{ $name }}">
                                    <button class="accordion-button py-2 @if(!$loop->first){{ 'collapsed' }}@endif" type="button" data-toggle="collapse" data-target="#collapse_{{ $name }}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse_{{ $name }}">
                                        {{ __('custom.instructions.'.$name) }}</button>
                                </h2>
                                <div id="collapse_{{ $name }}" class="accordion-collapse collapse @if($loop->first){{ 'show' }}@endif" aria-labelledby="heading_{{ $name }}" data-parent="#accordionExample_{{ $key }}" style="">
                                    <div class="accordion-body">
                                        {!! __('custom.instructions.'.$name.'.content') !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
