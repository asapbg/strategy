<div class="row justify-content-end my-3">
    <div class="col-md-4">
    </div>
    <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
        <label for="results-per-page" class="form-label fw-bold mb-0 me-3">{{ __('custom.filter_pagination') }}:</label>
        <select class="form-select w-auto" id="results-per-page" name="{{ isset($name) ? $name : "paginate" }}">
            @foreach($per_page_array as $per_page)
                <option value="{{ $per_page }}" @if($paginate == $per_page) selected @endif>
                    {{ $per_page }}
                </option>
            @endforeach
        </select>
    </div>
</div>
