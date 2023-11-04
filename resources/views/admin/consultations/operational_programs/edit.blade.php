@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    @if(!$item->id)
                        {{ __('custom.new_f').' '.__('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->name) }}
                    @else
                        {{ __('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->name).' ('.$item->period.')' }}
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
{{--                        <div class="col-12 my-3">--}}
{{--                            <table class="table table-sm table-striped sm-text">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th colspan="2">{{ trans_choice('custom.documents', 2) }}</th>--}}
{{--                                    <th>Текущ файл</th>--}}
{{--                                    <th colspan="2"></th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>1</td>--}}
{{--                                    <td>Оценка на въздействието</td>--}}
{{--                                    <td>--}}
{{--                                        @if($item->assessment)--}}
{{--                                            <a href="{{ route('admin.download.file', ['file' => $item->assessment]) }}" target="_blank">--}}
{{--                                                <i class="fas fa-file-download text-info" title="{{ __('custom.download') }}"></i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <i class="fas fa-minus text-danger"></i>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" name="assessment" class="custom-file-input @error('assessment'){{ 'is-invalid' }}@enderror">--}}
{{--                                            <label class="custom-file-label" for="assessment" data-browse="{{ __('custom.select_file') }}">{{ __('custom.no_file_chosen') }}</label>--}}
{{--                                            @error('assessment')--}}
{{--                                            <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>2</td>--}}
{{--                                    <td>Становище</td>--}}
{{--                                    <td>--}}
{{--                                        @if($item->assessmentOpinion)--}}
{{--                                            <a href="{{ route('admin.download.file', ['file' => $item->assessmentOpinion]) }}" target="_blank">--}}
{{--                                                <i class="fas fa-file-download text-info" title="{{ __('custom.download') }}"></i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <i class="fas fa-minus text-danger"></i>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" name="opinion" class="custom-file-input @error('opinion'){{ 'is-invalid' }}@enderror">--}}
{{--                                            <label class="custom-file-label" for="opinion" data-browse="{{ __('custom.select_file') }}">{{ __('custom.no_file_chosen') }}</label>--}}
{{--                                            @error('opinion')--}}
{{--                                                <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
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
                                                        <select id="type" name="month"  class="form-control form-control-sm @error('month'){{ 'is-invalid' }}@enderror">
                                                            <option value="">---</option>
                                                            @if(isset($months) && sizeof($months))
                                                                @foreach($months as $m)
                                                                    <option value="{{ $m }}" @if(old('month', '') == $m) selected @endif>{{ $m }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @error('month')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($columns as $i => $col)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-sm-12 control-label" for="{{ 'new_val.'.$i }}">
                                                            {{ $col->label }}
                                                        </label>
                                                        <div class="col-12">
                                                            <input type="hidden" value="{{ $col->id }}" name="new_val_col[]">
                                                            @if($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                <input type="text" class="form-control form-control-sm datepicker @error('new_val.'.$i) is-invalid @enderror" value="{{ old('new_val.'.$i, '') }}" name="new_val[]">
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                <select name="new_val[]" class="form-control form-control-sm  @error('new_val.'.$i) is-invalid @enderror" >
                                                                    <option value="" @if(old('new_val.'.$i, '') == '') selected @endif></option>
                                                                    <option value="1" @if(old('new_val.'.$i, '') == 1) selected @endif>Да</option>
                                                                    <option value="0" @if(old('new_val.'.$i, '') == 0) selected @endif>Не</option>
                                                                </select>
                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                <textarea class="form-control form-control-sm @error('new_val.'.$i) is-invalid @enderror" name="new_val[]">{{ old('new_val.'.$i, '') }}</textarea>
                                                            @else
                                                                <input type="{{ $col['type'] }}" class="form-control form-control-sm @error('new_val.'.$i) is-invalid @enderror" @if(old('new_val.'.$i, '') == 0) checked @endif value="{{ old('new_val.'.$i, '') }}" name="new_val[]">
                                                            @endif
                                                            @error('new_val.'.$i)
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
                            @foreach($months as $month)
                                @php($rIndex = 1)
                                @php($hasData = 0)
                                @if(isset($data) && $data)
                                    @foreach($data as $i => $row)
                                        @if($row->month == $month)
                                            @php($hasData = 1)
                                            @php($rowColumns = json_decode($row->columns, true))
                                            @if(!is_null($rowColumns))
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <h3 class="border-bottom border-4 border-primary pb-2">
                                                                {{ trans_choice('custom.months', 1) }} {{ $month }} - #{{ $rIndex }}
                                                                @can('update', $item)
                                                                    <a class="btn btn-danger ml-3" href="{{ route('admin.consultations.operational_programs.remove_row', ['item' => $item, 'row' => $row->row_num]) }}">{{ __('custom.remove') }}</a>
                                                                @endcan
                                                            </h3>
                                                        </div>
                                                        <div class="row">
                                                            @php($rowColumns = array_combine(array_column($rowColumns, 'ord'), $rowColumns))
                                                            @php(ksort($rowColumns))
                                                            @foreach($rowColumns as $k => $col)
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-12 control-label" for="val[]">
                                                                            {{ $col['label'] }}
                                                                        </label>
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="col[]" value="{{ $col['id'] }}">
                                                                            @if($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::DATE->value)
                                                                                <input type="text" class="form-control form-control-sm datepicker @error('val.'.$i.'.'.$k) is-invalid @enderror" value="{{ old('val.'.$i.'.'.$k, $col['value']) }}" name="val[]">
                                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                <select name="val[]" class="form-control form-control-sm  @error('val.'.$i.'.'.$k) is-invalid @enderror" >
                                                                                    <option value="" @if(old('val.'.$i.'.'.$k, (int)$col['value']) == '') selected @endif></option>
                                                                                    <option value="1" @if(old('val.'.$i.'.'.$k, (int)$col['value']) == 1) selected @endif>Да</option>
                                                                                    <option value="0" @if(old('val.'.$i.'.'.$k, (int)$col['value']) == 0) selected @endif>Не</option>
                                                                                </select>
                                                                            @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXT->value)
                                                                                <textarea class="form-control form-control-sm @error('val.'.$i.'.'.$k) is-invalid @enderror" name="val[]">{{ old('val.'.$i.'.'.$k, $col['value']) }}</textarea>
                                                                            @else
                                                                                <input type="{{ $col['type'] }}" class="form-control form-control-sm @error('val.'.$i.'.'.$k) is-invalid @enderror" value="{{ old('val.'.$i.'.'.$k, $col['value']) }}" name="val[]">
                                                                            @endif
                                                                            @error('val.'.$i.'.'.$k)
                                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            @php($assessmentField = 'file_assessment_'.$row->row_num.'_'.str_replace('.', '_', $row->month))
                                                            @php($opinionField = 'file_opinion_'.$row->row_num.'_'.str_replace('.', '_', $row->month))
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-sm-12 control-label" for="{{ $assessmentField }}">Оценка на въздействието</label>
                                                                    <div class="col-12">
                                                                        <input type="file" class="form-control form-control-sm @error($assessmentField) is-invalid @enderror" value="" name="{{ $assessmentField }}">
                                                                        @error($assessmentField)
                                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    @include('admin.partial.attached_documents_with_actions', ['attFile' => $assessmentsFiles[$row->row_num.'_'.$row->month] ?? null, 'delete' => true])
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-sm-12 control-label" for="{{ $opinionField }}">Становище</label>
                                                                    <div class="col-12">
                                                                        <input type="file" class="form-control form-control-sm @error($opinionField) is-invalid @enderror" value="" name="{{ $opinionField }}">
                                                                        @error($opinionField)
                                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    @include('admin.partial.attached_documents_with_actions', ['attFile' => $opinionsFiles[$row->row_num.'_'.$row->month] ?? null, 'delete' => true])
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
                        @endif
                        <div class="col-12">
                            @if($item->id)
                                @can('update', $item)
                                    <button id="save" type="submit" class="btn btn-success" name="save" value="1">{{ __('custom.save') }}</button>
                                @endcan
                            @else
                                <button id="save" type="submit" class="btn btn-success" name="save" value="1">{{ __('custom.save') }}</button>
                            @endif
                            <a href="{{ route('admin.consultations.operational_programs.index') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
