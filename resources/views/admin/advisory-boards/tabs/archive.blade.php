@php
    $archive ??= collect();
@endphp

<form method="GET">
    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <div class="mb-3 d-flex flex-column w-100">
                    <label for="status"
                           class="form-label">{{ trans_choice('validation.attributes.category', 1) }}</label>
                    <select id="status" class="institution form-select select2" name="archive_category" multiple>
                        @php $selected = request()->get('archive_category', '') == 1 ? 'selected' : '' @endphp
                        <option value="1" {{ $selected }}>{{ __('custom.meetings_and_decisions') }}</option>

                        @php $selected = request()->get('archive_category', '') == 2 ? 'selected' : '' @endphp
                        <option value="2" {{ $selected }}>{{ __('custom.function') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-search"></i> {{ __('custom.search') }}
            </button>
        </div>
    </div>
</form>

@include('admin.partial.archive_list', ['items' => $archive, 'current_tab' => 'archive'])
