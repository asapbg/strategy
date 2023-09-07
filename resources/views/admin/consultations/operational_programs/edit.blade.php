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
                                       value="{{ old('from_date', $item->id ? date('m-Y', strtotime($item->from_date)) : '') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="to_date">{{ __('validation.attributes.to_date') }} <span class="required">*</span></label>
                                <input type="text" id="to_date" name="to_date"
                                       class="datepicker-month form-control form-control-sm @error('to_date'){{ 'is-invalid' }}@enderror"
                                       value="{{ old('to_date', $item->id ? date('m-Y', strtotime($item->to_date)) : '') }}">
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <table class="table table-sm table-striped sm-text">
                                <thead>
                                <tr>
                                    <th colspan="2">{{ trans_choice('custom.documents', 2) }}</th>
                                    <th>Текущ файл</th>
                                    <th colspan="2"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Оценка на въздействието</td>
                                    <td>
                                        @if($item->assessment)
                                            <a href="{{ route('admin.download.file', ['file' => $item->assessment]) }}" target="_blank">
                                                <i class="fas fa-file-download text-info" title="{{ __('custom.download') }}"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-minus text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="file" name="assessment" class="custom-file-input @error('assessment'){{ 'is-invalid' }}@enderror">
                                            <label class="custom-file-label" for="assessment" data-browse="{{ __('custom.select_file') }}">{{ __('custom.no_file_chosen') }}</label>
                                            @error('assessment')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Становище</td>
                                    <td>
                                        @if($item->assessmentOpinion)
                                            <a href="{{ route('admin.download.file', ['file' => $item->assessmentOpinion]) }}" target="_blank">
                                                <i class="fas fa-file-download text-info" title="{{ __('custom.download') }}"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-minus text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="file" name="opinion" class="custom-file-input @error('opinion'){{ 'is-invalid' }}@enderror">
                                            <label class="custom-file-label" for="opinion" data-browse="{{ __('custom.select_file') }}">{{ __('custom.no_file_chosen') }}</label>
                                            @error('opinion')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <table class="table table-sm table-responsive sm-text table-bordered table-hover">
                                <thead>
                                @php($fullColspan = 1)
                                @if(isset($columns) && $columns)
                                    <tr>
                                        @can('update', $item)
                                            <th>{{ __('custom.actions') }}</th>
                                            @php($fullColspan +=1)
                                        @endif
                                            <th>#</th>
                                        @foreach($columns as $col)
                                            <th>{{ $col->label }}</th>
                                            @php($fullColspan +=1)
                                        @endforeach
                                        @can('update', $item)
                                            <th>{{ __('custom.actions') }}</th>
                                            @php($fullColspan +=1)
                                        @endif
                                    </tr>
                                @endif
                                </thead>
                                <tbody>
                                @if($item->id && isset($columns) && $columns)
                                    @can('update', $item)
                                        <tr>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-success" name="new_row" value="1">{{ __('custom.add') }}</button>
                                            </td>
                                            <td style="min-width: 100px;">
                                                <select class="form-control form-control-sm @error('month') is-invalid @enderror" name="month">
                                                    <option value="">{{ trans_choice('custom.months', 1) }}</option>
                                                    @if(isset($months) && sizeof($months))
                                                        @foreach($months as $m)
                                                            <option value="{{ $m }}">{{ $m }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('month')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                                {{--                                                <button type="submit" class="btn btn-sm btn-success">{{ __('custom.add') }}</button>--}}
                                            </td>
                                            @foreach($columns as $i => $col)
                                                <td>
                                                    <input type="hidden" value="{{ $col->id }}" name="new_val_col[]">
                                                    <input type="{{ $col['type'] }}" value="{{ old('new_val.'.$i, '') }}" name="new_val[]" class="@error('new_val.'.$i) is-invalid @enderror">
                                                    @error('new_val.'.$i)
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endforeach
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-success" name="new_row" value="1">{{ __('custom.add') }}</button>
                                            </td>
                                        </tr>
                                    @endcan
                                @endif
                                @if(isset($months) && sizeof($months))
                                    @foreach($months as $month)
                                        @php($rIndex = 1)
                                        @php($hasData = 0)
                                        <tr class="bg-light">
                                            <td class="pt-4" style="border-bottom:1px solid #333;border-top:1px solid #333;" colspan="{{ $fullColspan }}">{{ trans_choice('custom.months', 1) }} <strong>{{ $month }}</strong></td class="text-center">
                                        </tr>
                                        @if(isset($data) && $data)
                                            @foreach($data as $i => $row)
                                                @if($row->month == $month)
                                                    @php($hasData = 1)
                                                    <tr>
                                                        @php($rowColumns = json_decode($row->columns, true))
                                                        @can('update', $item)
                                                            <td>
                                                                <a href="{{ route('admin.consultations.operational_programs.remove_row', ['item' => $item, 'row' => $row->row_num]) }}"><i class="fas fa-trash text-danger"></i></a>
                                                            </td>
                                                        @endif
                                                        @if(!is_null($rowColumns))
                                                            <td>{{ $rIndex }}</td>
                                                            @foreach($rowColumns as $k => $col)
                                                                <td>
                                                                    <input type="hidden" name="col[]" value="{{ $col['id'] }}">
                                                                    <input type="{{ $col['type'] }}" class="@error('val.'.$i.'.'.$k) is-invalid @enderror" value="{{ old('val.'.$i.'.'.$k, $col['value']) }}" name="val[]" >
                                                                    @error('val.'.$i.'.'.$k)
                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                        @can('update', $item)
                                                            <td>
                                                                <a href="{{ route('admin.consultations.operational_programs.remove_row', ['item' => $item, 'row' => $row->row_num]) }}"><i class="fas fa-trash text-danger"></i></a>
                                                            </td>
                                                        @endif
                                                        @php($rIndex += 1)
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(!$hasData)
                                            <tr><td colspan="{{ $fullColspan }}">---</td></tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <button id="save" type="submit" class="btn btn-success" name="save" value="1">{{ __('custom.save') }}</button>
                            <a href="{{ route('admin.consultations.operational_programs.index') }}"
                               class="btn btn-primary">{{ __('custom.cancel') }}</a>
                        </div>
                    </form>

{{--                    @include('admin.partial.attached_documents')--}}
                </div>
            </div>
        </div>
    </section>
@endsection
