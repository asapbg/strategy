@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        @if($item->id)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="adm_level">{{ trans_choice('custom.consultation_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="consultation-category-select" name="consultation_category_id" class="form-control form-control-sm select2 @error('institution_level'){{ 'is-invalid' }}@enderror">
                                <option value="0" data-id="0">---</option>
                                    @if(isset($consultationCategories) && $consultationCategories->count())
                                        @foreach($consultationCategories as $row)
                                            <option value="{{ $row->id }}"
                                                @if(old('consultation_category_id', ($item->id ? $item->consultation_category_id : 0)) == $row->id) selected @endif
                                                data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('institution_level')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="adm_level">{{ trans_choice('custom.act_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="act-type-select" name="act_type_id" class="form-control form-control-sm select2 @error('institution_level'){{ 'is-invalid' }}@enderror">
                                    @if(isset($actTypes) && $actTypes->count())
                                        @foreach($actTypes as $row)
                                            <option value="{{ $row->id }}"
                                                data-consultation-category="{{ $row->consultation_category_id }}"
                                                @if(old('act_type_id', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif
                                                data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('institution_level')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$('#consultation-category-select').on('select2:select', onCategoryChanged);
$(document).ready(function () {
    $('#consultation-category-select').trigger('select2:select');
});

function onCategoryChanged(e) {
    var selected = $('#consultation-category-select > option:selected');
    if (e.params) {
        $('#act-type-select').val(null).trigger('change');
    }
    $('#act-type-select > option').each(function (i, el) {
        var category = $(el).data('consultation-category');
        var isEqual = category == selected.attr('data-id');
        $(el).attr('disabled', !isEqual);
    });
}
</script>
@endpush

@push('styles')
<style>
.select2-container .select2-results__option[aria-disabled=true] {
    display: none;
}
</style>
@endpush