@php($fieldProperties = isset($translatableFields) && sizeof($translatableFields) ? $translatableFields[$field] : [])
@if(sizeof($fieldProperties))
    @php($language = app()->getLocale())
    @php($fieldName = $field.'_'.$language)
    <div class="form-group">
        <label class="col-sm-12 control-label" for="{{ $fieldName }}">{{ __('validation.attributes.'.$field) }} @if(isset($required) && $required)<span class="required">*</span>@endif</label>
        <div class="col-12">
            @switch($fieldProperties['type'])
                @case('textarea')
                    <textarea id="{{ $fieldName }}" name="{{ $fieldName }}"
                                class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror">{{ old($fieldName, ($item->id ? $item->translateOrNew($language)->{$field} : '')) }}</textarea>
                @break
                @case('ckeditor')
                    <textarea id="{{ $fieldName }}" name="{{ $fieldName }}"
                                class="ckeditor @error($fieldName){{ 'is-invalid' }}@enderror">{{ old($fieldName, ($item->id ? $item->translateOrNew($language)->{$field} : '')) }}</textarea>
                @break
                @default
                    <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}"
                            class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror"
                            value="{{ old($fieldName, ($item->id ? $item->translateOrNew($language)->{$field} : '')) }}">
            @endswitch
            @error($fieldName)
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif
