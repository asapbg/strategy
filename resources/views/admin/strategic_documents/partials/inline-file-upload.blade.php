<div class="row" id="file-row-{{ $index }}">
    @foreach(config('available_languages') as $lang)
        <div class="col-md-2 mb-3">
            <div class="form-group">
                <label for="description_{{ $lang['code'] }}" class="form-label col-sm-12">
                    {{ __('validation.attributes.description_'.$lang['code']) }}
                    @if($lang['code'] == 'bg')
                        <span class="required">*</span>
                    @endif
                </label>
                <div class="col-12">
                    <input class="form-control form-control-sm col-sm-12 @error('description_'.$lang['code']) is-invalid @enderror"
                           type="text" name="files[{{ $index }}][description_{{ $lang['code'] }}]">
                </div>
                @error('file_'.$lang['code'])
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endforeach

    @foreach(config('available_languages') as $lang)
        <div class="col-md-2 mb-3">
            <div class="form-group">
                <label for="file_{{ $lang['code'] }}" class="form-label col-sm-12">{{ __('validation.attributes.file_'.$lang['code']) }}
                    @if($lang['code'] == 'bg')
                        <span class="required">*</span>
                    @endif
                </label>
                <div class="col-12">
                    <input class="form-control form-control-sm col-sm-12 @error('file_'.$lang['code']) is-invalid @enderror"
                           type="file" name="files[{{ $index }}][file_{{ $lang['code'] }}]">
                </div>
                @error('file_'.$lang['code'])
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endforeach

    <div class="col-md-2">
        <label class="form-label col-sm-12">
            &nbsp;
        </label>
        <div class="col-12">
            <button class="btn btn-danger js-delete-row" type="button" onclick="deleteRow('{{ $index }}')">
                <i class="fas fa-trash"></i> {{ __('custom.delete') }}
            </button>
        </div>
    </div>
</div>
