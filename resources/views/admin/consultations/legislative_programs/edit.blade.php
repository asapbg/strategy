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
                                                        <div class="@if($col->id == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)  @else col-md-6 @endif">
                                                            <input type="hidden" value="{{ $col->id }}" name="new_val_col[]">
                                                            @if($col->id == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
                                                                <div class="col-12 d-flex flex-row px-0">
                                                                    <div class="input-group">
                                                                        <select class="form-control form-control-sm select2 @error($errorNewField) is-invalid @enderror" name="{{ $newFieldName }}" id="institutions_{{ $i }}">
                                                                            <option value="" @if('' == old($errorNewField, '')) selected @endif>---</option>
                                                                            @if(isset($institutions) && sizeof($institutions))
                                                                                @foreach($institutions as $option)
                                                                                    <option value="{{ $option['value'] }}" @if($option['value'] == old($errorNewField, '')) selected @endif>{{ $option['name'] }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                                            data-title="{{ trans_choice('custom.institutions',2) }}"
                                                                            data-url="{{ route('modal.institutions').'?select=1&multiple=0&admin=1&dom=institutions_'.$i }}">
                                                                        <i class="fas fa-list"></i>
                                                                    </button>
                                                                </div>
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                <input type="text" class="form-control form-control-sm datepicker-month @error($errorNewField) is-invalid @enderror" value="{{ old($errorNewField, '') }}" name="{{ $newFieldName }}" id="nd{{ $i }}" data-start="{{ $months[0] }}" data-end="{{ $months[sizeof($months) - 1] }}">
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                <select name="{{ $newFieldName }}" class="form-control form-control-sm  @error($errorNewField) is-invalid @enderror" >
                                                                    <option value="" @if(old($errorNewField, '') == '') selected @endif></option>
                                                                    <option value="1" @if(old($errorNewField, '') == 1) selected @endif>Да</option>
                                                                    <option value="0" @if(old($errorNewField, '') == 0) selected @endif>Не</option>
                                                                </select>
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                <input type="text" class="form-control form-control-sm @error($errorNewField) is-invalid @enderror" name="{{ $newFieldName }}" value="{{ old($errorNewField, '') }}">
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                <textarea class="form-control form-control-sm summernote @error($errorNewField) is-invalid @enderror" name="{{ $newFieldName }}">{{ old($errorNewField, '') }}</textarea>
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
                                                                    {{ trans_choice('custom.months', 1) }} {{ $month }} - #{{ $rIndex }} {{ $rowColumns[0]['value'] }}
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
                                                                                <div class="@if($col['dsc_id'] == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)  @else col-md-6 @endif">
                                                                                    <input type="hidden" name="{{ 'col['.$i.']['.$k.']' }}" value="{{ $col['id'] }}">
                                                                                    @if($col['dsc_id'] == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
                                                                                        <div class="col-12 d-flex flex-row px-0">
                                                                                            <div class="input-group">
                                                                                                <select class="form-control form-control-sm select2 select2-hidden-accessible @error($errorField) is-invalid @enderror" name="{{ $fieldName }}" id="institutions_{{ $i.'.'.$k }}">
                                                                                                    <option value="" @if('' == old($errorField, '')) selected @endif>---</option>
                                                                                                    @if(isset($institutions) && sizeof($institutions))
                                                                                                        @foreach($institutions as $option)
                                                                                                            <option value="{{ $option['value'] }}" @if($option['value'] == old((int)$errorField, (int)$col['value'])) selected @endif>{{ $option['name'] }}</option>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </select>
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                                                                    data-title="{{ trans_choice('custom.institutions',2) }}"
                                                                                                    data-url="{{ route('modal.institutions').'?select=1&multiple=0&admin=1&dom=institutions_'.$i.'.'.$k }}">
                                                                                                <i class="fas fa-list"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                                        <input type="text" class="form-control form-control-sm datepicker-month @error($errorField) is-invalid @enderror" value="{{ old($errorField, $col['value']) }}" name="{{ $fieldName }}" id="{{ 'di'.$i.$k }}" data-start="{{ $months[0] }}" data-end="{{ $months[sizeof($months) - 1] }}">
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                        <select name="{{ $fieldName }}" class="form-control form-control-sm @error($errorField) is-invalid @enderror" >
                                                                                            <option value="" @if(old($errorField, (int)$col['value']) == '') selected @endif></option>
                                                                                            <option value="1" @if(old($errorField, (int)$col['value']) == 1) selected @endif>Да</option>
                                                                                            <option value="0" @if(old($errorField, (int)$col['value']) == 0) selected @endif>Не</option>
                                                                                        </select>
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                                        <input type="text" class="form-control form-control-sm @error($errorField) is-invalid @enderror" name="{{ $fieldName }}" value="{{ old($errorField, $col['value']) }}">
                                                                                    @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                                        <textarea class="form-control form-control-sm summernote @error($errorField) is-invalid @enderror" name="{{ $fieldName }}">{{ old($errorField, $col['value']) }}</textarea>
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
                                                                <div class="row">
                                                                    @foreach(config('available_languages') as $lang)
                                                                        @php($fieldNameLang = 'file_assessment_'.$row->row_num.'_'.str_replace('.', '_', $row->month).'_'.$lang['code'])
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-12 control-label" for="{{ $fieldNameLang }}">{{ trans_choice('custom.impact_assessment', 1) }} ({{ strtoupper($lang['code']) }})</label>
                                                                                <div class="col-12">
                                                                                    <input type="file" class="form-control form-control-sm @error($fieldNameLang) is-invalid @enderror" value="" name="{{ $fieldNameLang }}">
                                                                                    @error($fieldNameLang)
                                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                                    @enderror
                                                                                    @include('admin.partial.attached_documents_with_actions', ['attFile' => $assessmentsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']] ?? null, 'delete' => isset($assessmentsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']]) ? route('admin.consultations.legislative_programs.delete.file', ['program' => $item, 'file' => $assessmentsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']]]) : ''])
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="row">
                                                                    @foreach(config('available_languages') as $lang)
                                                                        @php($fieldNameLang = 'file_opinion_'.$row->row_num.'_'.str_replace('.', '_', $row->month).'_'.$lang['code'])
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-12 control-label" for="{{ $fieldNameLang }}">Становище ({{ strtoupper($lang['code']) }})</label>
                                                                                <div class="col-12">
                                                                                    <input type="file" class="form-control form-control-sm @error($fieldNameLang) is-invalid @enderror" value="" name="{{ $fieldNameLang }}">
                                                                                    @error($fieldNameLang)
                                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                @include('admin.partial.attached_documents_with_actions', ['attFile' => $opinionsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']] ?? null, 'delete' => isset($opinionsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']]) ? route('admin.consultations.legislative_programs.delete.file', ['program' => $item, 'file' => $opinionsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']]]) : ''])
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
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
