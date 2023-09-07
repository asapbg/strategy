@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    {{ __('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->name).' ('.$item->period.')' }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mr-2" for="from_date">{{ __('validation.attributes.from_date') }}: </label>{{ date('m-Y', strtotime($item->from_date)) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mr-2" for="to_date">{{ __('validation.attributes.to_date') }}: </label>{{ date('m-Y', strtotime($item->to_date)) }}
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <table class="table table-sm sm-text">
                                <thead>
                                    <tr>
                                        <th colspan="2">{{ trans_choice('custom.documents', 2) }}</th>
                                        <th>{{ __('custom.file') }}</th>
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
                                            <th>#</th>
                                            @foreach($columns as $col)
                                                <th>{{ $col->label }}</th>
                                                @php($fullColspan +=1)
                                            @endforeach
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                @if(isset($months) && sizeof($months))
                                    @foreach($months as $month)
                                        @php($rIndex = 1)
                                        @php($hasData = 0)
                                        <tr class="bg-light">
                                            <td class="text-center" style="border-bottom:1px solid #333;border-top:1px solid #333;" colspan="{{ $fullColspan }}"><strong>{{ trans_choice('custom.months', 1) }} {{ $month }}</strong></td>
                                        </tr>
                                        @if(isset($data) && $data)
                                            @foreach($data as $i => $row)
                                                @if($row->month == $month)
                                                    @php($hasData = 1)
                                                    <tr>
                                                        @php($rowColumns = json_decode($row->columns, true))
                                                        @if(!is_null($rowColumns))
                                                            <td>{{ $rIndex }}</td>
                                                            @foreach($rowColumns as $k => $col)
                                                                <td>{{ $col['value'] }}</td>
                                                            @endforeach
                                                        @endif
                                                        @php($rIndex += 1)
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(!$hasData)
                                            <tr><td class="text-center" colspan="{{ $fullColspan }}">---</td></tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                               class="btn btn-primary">{{ __('custom.back') }}</a>
                        </div>
                    </div>
{{--                    @include('admin.partial.attached_documents')--}}
                </div>
            </div>
        </div>
    </section>
@endsection
