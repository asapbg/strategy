@if(isset($attFile) && $attFile)
    @if(!empty($attFile->description))
        <div class="d-inline-block mr-2">
            <a target="_blank" href="{{ route('admin.download.file', $attFile) }}" title="{{ __('custom.download') }}">
                {!! fileIcon($attFile->content_type) !!} {{ !empty($attFile->description) ? $attFile->description : $attFile->filename }}
            </a>
        </div>
        <div class="d-inline-block">
            @if(isset($delete) && $delete)
                <a target="_blank" href="" title="{{ __('custom.delete') }}"><i class="fas fa-trash ml-1 text-danger"></i></a>
            @endif
        </div>
    @endif
@endif
