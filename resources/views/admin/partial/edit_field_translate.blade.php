@php($fieldProperties = isset($translatableFields) && sizeof($translatableFields) ? $translatableFields[$field] : [])

@if(sizeof($fieldProperties))
    @foreach(config('available_languages') as $language)
        @php($fieldName = $field.'_'.$language['code'])
        @php($value = $value ?? null)
        <div class="col-md-{{ $col ?? 6 }} col-12">
            <div class="form-group">
                <label class="col-sm-12 control-label" for="{{ $fieldName }}">@if(isset($tooltip) && !empty($tooltip)) <i class="fas fa-info-circle text-info mr-1" title="{{ $tooltip }}"></i>@endif{{ __('validation.attributes.'.$fieldName) }} @if(isset($required) && $required)<span class="required">*</span>@endif</label>
                <div class="col-12">
                    @switch($fieldProperties['type'])
                        @case('textarea')
                            <textarea id="{{ $fieldName }}" name="{{ $fieldName }}"
                                      class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror">{{ old($fieldName, ($item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : '')) }}</textarea>
                            {{--                            <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}"--}}
                            {{--                                   class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror"--}}
                            {{--                                   value="{{ old($fieldName, ($item->id ? $item->translate($language['code'])->{$field} : '')) }}">--}}
                            @break
                        @case('summernote')
                            <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" class="form-control form-control-sm summernote @error($fieldName){{ 'is-invalid' }}@enderror">{{ old($fieldName, ($item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : ($default_val ?? '' ) )) }}</textarea>
                            @break
                        @default
                            <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                   class="form-control form-control-sm @error($fieldName){{ 'is-invalid' }}@enderror"
                                   value="{{ $value ?? old($fieldName, (isset($item) && $item && $item->id ? ($item->translate($language['code']) ? $item->translate($language['code'])->{$field} : '') : '')) }}">
                    @endswitch
                    @error($fieldName)
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    @endforeach
@endif
