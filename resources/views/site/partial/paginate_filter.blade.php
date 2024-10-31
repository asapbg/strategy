@if(isset($filter) && count($filter) && isset($filter['paginate']))
    @php($field = $filter['paginate'])
    <div class="col-md-6 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">{{ $field['label'] }}:</label>
        @php($fRequest = $rf ?? ($requestFilter ?? request()->all()))
        <select class="form-select w-auto @if(isset($field['class'])){{$field['class'] }}@endif" name="paginate" id="list-paginate" @if(isset($ajaxContainer)) data-container="{{ $ajaxContainer }}" @endif>
            @foreach($field['options'] as $option)
                @php($fRequest['paginate'] = $option['value'])
                <option value="{{ $option['value'] }}" data-url="{{ url()->current(). '?' . http_build_query($fRequest) }}" @if(old('paginate', request()->input('paginate') ?? config('app.default_paginate')) == $option['value']) selected @endif>{{ $option['name'] }}</option>
            @endforeach
        </select>
    </div>
@endif
