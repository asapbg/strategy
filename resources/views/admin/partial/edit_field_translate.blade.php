@php($fieldProperties = isset($translatableFields) && sizeof($translatableFields) ? $translatableFields[$field] : [])
@php($disabled = $disabled ?? false)
@if(sizeof($fieldProperties))
    @foreach(config('available_languages') as $language)
        @php($mainLang = $language['code'] == config('app.default_lang'))
        @php($fieldName = $field.'_'.$language['code'])
{{--        Ugly way to fix sections with same name field and display validation errors--}}
        @php($oldFieldValueName = isset($old_val_is_null) && $old_val_is_null ? 'null' : $fieldName)
        @php($value = $value ?? null)
        <div class="col-md-{{ $col ?? 6 }} col-12">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="{{ $fieldName }}">@if(isset($tooltip) && !empty($tooltip)) <i class="fas fa-info-circle text-info mr-1" title="{{ $tooltip }}"></i>@endif{{ __('validation.attributes.'.( isset($custom_lang_key) && !empty($custom_lang_key) ? $custom_lang_key.'_'.$language['code'] : $fieldName)) }}
                    @if(isset($required) && $required && ((isset($fieldProperties) && isset($fieldProperties['required_all_lang']) && $fieldProperties['required_all_lang']) || $mainLang))<span class="required">*</span>@endif
                </label>
                <div class="col-12">
                    @switch($fieldProperties['type'])
                        @case('textarea')
                            <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" @if($disabled) readonly disabled @endif
                                      class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror">{{ old($oldFieldValueName, ($item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : '')) }}</textarea>
                            {{--                            <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}"--}}
                            {{--                                   class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror"--}}
                            {{--                                   value="{{ old($fieldName, ($item->id ? $item->translate($language['code'])->{$field} : '')) }}">--}}
                            @break
                        @case('summernote')
                            @if($disabled)
                                {!! old($oldFieldValueName, ($item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : ($default_val ?? '' ) )) !!}
                            @else
                                <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" @if($disabled) readonly disabled @endif class="form-control form-control-sm summernote @error($fieldName){{ 'is-invalid' }}@enderror">{!! old($oldFieldValueName, ($item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : ($default_val ?? '' ) )) !!}</textarea>

                            @endif
                            @break
                        @default
                            <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}" @if($disabled) readonly disabled @endif
                                   class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror"
                                   value="{{ $value ?? old($oldFieldValueName, (isset($item) && $item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : '')) }}">
                    @endswitch
                    @error($fieldName)
                        @if($oldFieldValueName != 'null')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                    <div class="ajax-error text-danger mt-1 error_{{ $fieldName }}"></div>
                </div>
            </div>
        </div>
    @endforeach
@endif
