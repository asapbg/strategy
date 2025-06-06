@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header fw-bold">
                    @if(!$item->id)
                        {{ __('custom.new_f').' '.__('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->name) }}
                    @else
                        {{ __('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->name).' ('.$item->period.')' }}
                    @endif
                </div>
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item->id]))
                    <form class="row" action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
                        @csrf
                        @if($item->id)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="from_date">{{ __('validation.attributes.from_date') }} <span class="required">*</span></label>
                                <input type="text" id="from_date" name="from_date"
                                    class="datepicker-month form-control form-control-sm @error('from_date'){{ 'is-invalid' }}@enderror"
                                    value="{{ old('from_date', $item->id ? date('m.Y', strtotime($item->from_date)) : '') }}">
                                    @error('from_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="to_date">{{ __('validation.attributes.to_date') }} <span class="required">*</span></label>
                                <input type="text" id="to_date" name="to_date"
                                    class="datepicker-month form-control form-control-sm @error('to_date'){{ 'is-invalid' }}@enderror"
                                    value="{{ old('to_date', $item->id ? date('m.Y', strtotime($item->to_date)) : '') }}">
                                    @error('to_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>

{{--                        START Files section--}}
                        @if($item->id)
                            <div class="card card-secondary p-0 mt-4">
                                <div class="card-body row">
                                    <h3 class="border-bottom border-4 border-primary col-12">Файлове</h3>

                                    <input type="hidden" name="formats" value="ALLOWED_FILE_LP_OO">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-md-6 mb-3">
                                            <label for="a_description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}<span class="required">*</span> </label>
                                            <input value="{{ old('a_description_'.$lang['code'], '') }}" class="form-control form-control-sm @error('a_description_'.$lang['code']) is-invalid @enderror" id="a_description_{{ $lang['code'] }}" type="text" name="a_description_{{ $lang['code'] }}">
                                            @error('a_description_'.$lang['code'])
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-md-6 mb-3">
                                            <label for="a_file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}<span class="required">*</span> </label>
                                            <input class="form-control form-control-sm @error('a_file_'.$lang['code']) is-invalid @enderror" id="a_file_{{ $lang['code'] }}" type="file" name="a_file_{{ $lang['code'] }}">
                                            @error('a_file_'.$lang['code'])
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                    <div class="col-12">
                                        <button type="submit" name="save_files" class="btn btn-success" value="1">{{ __('custom.save') }}</button>
                                        <button type="submit" name="stay_in_files" class="btn btn-success" value="1">{{ __('custom.save_and_stay') }}</button>
                                    </div>

                                    @if($item->files)
                                        <div class="col-12">
                                            <table class="table table-sm table-hover table-bordered mt-4">
                                                <tbody>
                                                <tr>
                                                    <th>Име</th>
                                                    <th>Действие</th>
                                                </tr>
                                                @foreach($item->files as $f)
                                                    <tr>
                                                        <td>
                                                            {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                                            - {{ __('custom.'.$f->locale) }}
                                                            | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary preview-file-modal" data-file="{{ $f->id }}"
                                                                    data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}"
                                                            >
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-download me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-trash me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        {{-- END Files section--}}

                        @if($item->id && isset($columns) && $columns)
                            @can('update', $item)
                                <div class="card card-secondary p-0 mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <h3 class="border-bottom border-4 border-primary">Нов запис</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label" for="month">
                                                        {{ trans_choice('custom.months', 1) }}
                                                    </label>
                                                    <div class="col-12">
                                                        <input id="month" type="text" class="form-control form-control-sm datepicker-month @error('month') is-invalid @enderror" value="{{ old('month', '') }}" name="month" data-start="{{ $months[0] }}" data-end="{{ $months[sizeof($months) - 1] }}">
                                                        @error('month')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($columns as $i => $col)
                                                @php($newFieldName = 'new_val['.$i.']')
                                                @php($errorNewField = 'new_val.'.$i)
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="col-sm-12 control-label" for="{{ $newFieldName }}">
                                                            {{ $col->label }}
                                                        </label>
                                                        <div class="@if($col->id == config('lp_op_programs.lp_ds_col_institution_id'))  @else col-md-6 @endif">
                                                            <input type="hidden" value="{{ $col->id }}" name="new_val_col[]">
                                                            @if($col->id == config('lp_op_programs.lp_ds_col_institution_id'))
                                                                <div class="col-12 d-flex flex-row px-0">
                                                                    <div class="input-group">
                                                                        <select class="form-control form-control-sm select2 @error($errorNewField) is-invalid @enderror" multiple="multiple" name="{{ $newFieldName.'[]' }}" id="institutions_{{ $i }}">
                                                                            <option value="" >---</option>
                                                                            @if(isset($institutions) && sizeof($institutions))
                                                                                @foreach($institutions as $option)
                                                                                    <option value="{{ $option['value'] }}" @if(in_array($option['value'], old($errorNewField, []))) selected @endif>{{ $option['name'] }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                                            data-title="{{ trans_choice('custom.institutions',2) }}"
                                                                            data-url="{{ route('modal.institutions').'?select=1&multiple=1&admin=1&dom=institutions_'.$i }}">
                                                                        <i class="fas fa-list"></i>
                                                                    </button>
                                                                </div>
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                <input type="text" class="form-control form-control-sm datepicker-month @error($errorNewField) is-invalid @enderror" value="{{ old($errorNewField, '') }}" name="{{ $newFieldName }}" id="nd{{ $i }}" data-end="{{ $months[sizeof($months) - 1] }}">
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                <select name="{{ $newFieldName }}" class="form-control form-control-sm  @error($errorNewField) is-invalid @enderror" >
                                                                    <option value="" @if(old($errorNewField, '') == '') selected @endif></option>
                                                                    <option value="1" @if(old($errorNewField, '') == 1) selected @endif>Да</option>
                                                                    <option value="0" @if(old($errorNewField, '') == 0) selected @endif>Не</option>
                                                                </select>
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                <input type="text" class="form-control form-control-sm @error($errorNewField) is-invalid @enderror" name="{{ $newFieldName }}" value="{{ old($errorNewField, '') }}">
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                <textarea class="form-control form-control-sm summernote @error($errorNewField) is-invalid @enderror" name="{{ $newFieldName }}">{!! old($errorNewField, '') !!}</textarea>
                                                            @else
                                                                <input type="{{ $col['type'] }}" class="form-control form-control-sm @error($errorNewField) is-invalid @enderror" value="{{ old($errorNewField, '') }}" name="{{ $newFieldName }}">
                                                            @endif
                                                            @error($errorNewField)
                                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success" name="new_row" value="1">{{ __('custom.add') }}</button>
                                    </div>
                                </div>
                            @endcan
                        @endif

                        @if(isset($months) && sizeof($months) && isset($data) && $data)
                            <div class="accordion" id="accordionExample">
                                @foreach($months as $month)
                                    @php($rIndex = 1)
                                    @php($hasData = 0)
                                    @if(isset($data) && $data)
                                        @foreach($data as $i => $row)
                                            @if($row->month == $month)
                                                @php($hasData = 1)
                                                @php($rowColumns = json_decode($row->columns, true))
                                                @if(!is_null($rowColumns))
                                                    @php($accordionIndex = $rIndex.$i)
                                                    <div class="card card-primary card-outline">
                                                        <div class="card-header" id="heading{{ $accordionIndex }}">
                                                            <h2 class="mb-0 d-flex flex-row justify-content-between">
                                                                @php(usort($rowColumns, function ($a, $b) { return strcmp($a['ord'], $b['ord']); }))
                                                                <button class="btn btn-link btn-block text-left fw-bold @if(!$loop->first) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $accordionIndex }}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $accordionIndex }}">
                                                                    {{ trans_choice('custom.months', 1) }} {{ $month }} - #{{ $rIndex }} {{ html_entity_decode($rowColumns[0]['value']) }}
                                                                </button>
                                                                @can('update', $item)
                                                                    <a class="btn btn-danger ml-3" href="{{ route('admin.consultations.legislative_programs.remove_row', ['item' => $item, 'row' => $row->row_num]) }}">{{ __('custom.remove') }}</a>
                                                                @endcan
                                                            </h2>
                                                        </div>
                                                        <div id="collapse{{ $accordionIndex }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $accordionIndex }}" data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @php($rowColumns = array_combine(array_column($rowColumns, 'ord'), $rowColumns))
                                                                    @php(ksort($rowColumns))
                                                                    @foreach($rowColumns as $k => $col)
                                                                        @php($fieldName = 'val['.$i.']['.$k.']')
                                                                        @php($errorField = 'val.'.$i.'.'.$k)
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-12 control-label" for="{{ $fieldName }}">
                                                                                    {{ $col['label'] }}
                                                                                </label>
                                                                                <div class="@if($col['dsc_id'] == config('lp_op_programs.lp_ds_col_institution_id'))  @else col-md-6 @endif">
                                                                                    <input type="hidden" name="{{ 'col['.$i.']['.$k.']' }}" value="{{ $col['id'] }}">
                                                                                    @if($col['dsc_id'] == config('lp_op_programs.lp_ds_col_institution_id'))
                                                                                        <div class="col-12 d-flex flex-row px-0">
                                                                                            <div class="input-group">
                                                                                                <select class="form-control form-control-sm select2 select2-hidden-accessible @error($errorField) is-invalid @enderror" multiple="multiple" name="{{ $fieldName.'[]' }}" id="institutions_{{ $i.'.'.$k }}">
                                                                                                    <option value="">---</option>
                                                                                                    @if(isset($institutions) && sizeof($institutions))
                                                                                                        @foreach($institutions as $option)
                                                                                                            <option value="{{ $option['value'] }}" @if(in_array($option['value'], old($errorField, $col['institution_ids'] ?? []))) selected @endif>{{ $option['name'] }}</option>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </select>
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                                                                    data-title="{{ trans_choice('custom.institutions',2) }}"
                                                                                                    data-url="{{ route('modal.institutions').'?select=1&multiple=1&admin=1&dom=institutions_'.$i.'.'.$k }}">
                                                                                                <i class="fas fa-list"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                                        <input type="text" class="form-control form-control-sm datepicker-month @error($errorField) is-invalid @enderror" value="{{ old($errorField, $col['value']) }}" name="{{ $fieldName }}" id="{{ 'di'.$i.$k }}" data-end="{{ $months[sizeof($months) - 1] }}">
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                        <select name="{{ $fieldName }}" class="form-control form-control-sm @error($errorField) is-invalid @enderror" >
                                                                                            <option value="" @if(old($errorField, (int)$col['value']) == '') selected @endif></option>
                                                                                            <option value="1" @if(old($errorField, (int)$col['value']) == 1) selected @endif>Да</option>
                                                                                            <option value="0" @if(old($errorField, (int)$col['value']) == 0) selected @endif>Не</option>
                                                                                        </select>
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                                        <input type="text" class="form-control form-control-sm @error($errorField) is-invalid @enderror" name="{{ $fieldName }}" value="{{ old($errorField, html_entity_decode($col['value'])) }}">
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                                        <textarea class="form-control form-control-sm summernote @error($errorField) is-invalid @enderror" name="{{ $fieldName }}">{!! old($errorField, $col['value']) !!}</textarea>
                                                                                    @else
                                                                                        <input type="{{ $col['type'] }}" class="form-control form-control-sm @error($errorField) is-invalid @enderror" value="{{ old($errorField, $col['value']) }}" name="{{ $fieldName }}">
                                                                                    @endif
                                                                                    @error($errorField)
                                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <div class="row mt-3 included-file-form">
                                                                    <h3 class="border-bottom border-4 border-primary col-12 mb-2">Файлове</h3>
                                                                    <div class="row">
                                                                        <div class="col-12 text-danger main-error"></div>
                                                                        <div class="col-12 bg-success main-success mb-2"></div>
                                                                    </div>
                                                                    <div class="row sd-form-files" data-extension="{{ implode(',', \App\Models\File::ALLOWED_FILE_LP_OO) }}" data-url="{{ route('admin.upload.file.lp_op', ['object_id' => $item->id, 'object_type' => \App\Models\File::CODE_OBJ_LEGISLATIVE_PROGRAM, 'row_num' => $row->row_num, 'row_month' => $row->month]) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="formats" value="ALLOWED_FILE_LP_OO">
                                                                        @php($defaultLang = config('app.default_lang'))
                                                                        @foreach(config('available_languages') as $lang)
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
                                                                                </label>
                                                                                <input value="{{ old('description_'.$lang['code'], '') }}" class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror" id="description_{{ $lang['code'] }}" type="text" name="description_{{ $lang['code'] }}">
                                                                                @error('description_'.$lang['code'])
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                                <div class="ajax-error text-danger mt-1 error_{{ 'description_'.$lang['code'] }}"></div>
                                                                            </div>
                                                                        @endforeach
                                                                        @foreach(config('available_languages') as $lang)
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}
                                                                                </label>
                                                                                <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
                                                                                @error('file_'.$lang['code'])
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                                <div class="ajax-error text-danger mt-1 error_{{ 'file_'.$lang['code'] }}"></div>
                                                                            </div>
                                                                        @endforeach
                                                                        <div class="col-md-4">
                                                                            <br>
                                                                            <button type="button" class="btn btn-success included-file-form-submit">{{ __('custom.add') }}</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <table class="table table-sm table-hover table-bordered mt-4">
                                                                            <tbody>
                                                                            <tr>
                                                                                <th>Име</th>
                                                                                <th>Действие</th>
                                                                            </tr>

                                                                            @foreach(config('available_languages') as $lang)
                                                                                @php($langFilesKey = $row->row_num.'_'.$row->month.'_'.$lang['code'])
                                                                                @if(isset($rowFiles[$langFilesKey]) && sizeof($rowFiles[$langFilesKey]))
                                                                                    @foreach($rowFiles[$langFilesKey] as $f)
                                                                                        <tr>
                                                                                            <td>
                                                                                                {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                                                                                - {{ __('custom.'.$f->locale) }}
                                                                                                | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }}
                                                                                            </td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-sm btn-primary preview-file-modal" data-file="{{ $f->id }}"
                                                                                                        data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}"
                                                                                                >
                                                                                                    <i class="fas fa-eye"></i>
                                                                                                </button>
                                                                                                <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                                                                                    <i class="fas fa-download me-1" role="button"
                                                                                                       data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                                                                                </a>
                                                                                                <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id]) }}">
                                                                                                    <i class="fas fa-trash me-1" role="button"
                                                                                                       data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @php($rIndex += 1)
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="col-12">
                            @if($item->id)
                                @can('update', $item)
                                    <button id="save" type="submit" class="btn btn-success" name="save" value="1">{{ __('custom.save') }}</button>
                                @endcan
                            @else
                                <button id="save" type="submit" class="btn btn-success" name="save" value="1">{{ __('custom.save') }}</button>
                            @endif
                            <a href="{{ route('admin.consultations.legislative_programs.index') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
