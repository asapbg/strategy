<div class="input-group">
    <div class="mb-3 d-flex flex-column w-100">
        <label for="per-page" class="form-label">{{ __('custom.filter_pagination') }}</label>
        <select class="form-select" name="{{ isset($name) ? $name : "paginate" }}" aria-label="Default select example">
            @foreach($per_page_array as $per_page)
                <option value="{{ $per_page }}">{{ $per_page }}</option>
            @endforeach
        </select>
    </div>
</div>
