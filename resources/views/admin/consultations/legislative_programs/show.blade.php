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
                                <label class="control-label mr-2 fw-bold" for="from_date">{{ __('validation.attributes.from_date') }}: </label>{{ date('m-Y', strtotime($item->from_date)) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mr-2 fw-bold" for="to_date">{{ __('validation.attributes.to_date') }}: </label>{{ date('m-Y', strtotime($item->to_date)) }}
                            </div>
                        </div>

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
                                                            </h3>
                                                        </div>
                                                        <div class="row">
                                                            @php($rowColumns = array_combine(array_column($rowColumns, 'ord'), $rowColumns))
                                                            @php(ksort($rowColumns))
                                                            @foreach($rowColumns as $k => $col)
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <span class="fw-bold">{{ $col['label'] }}:</span>
                                                                        @if($col['dsc_id'] == \App\Http\Controllers\Admin\Consultations\LegislativeProgramController::DYNAMIC_STRUCTURE_COLUMN_INSTITUTION_ID)
                                                                            {{ $institutions[(int)$col['value']] ?? '---' }}
                                                                        @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                            {{ $col['value'] ? 'Да' : 'Не' }}
                                                                        @else
                                                                            {!! $col['value'] !!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            @foreach(config('available_languages') as $lang)
                                                                <div class="col-12">
                                                                    @include('admin.partial.attached_documents_with_actions', ['attFile' => $assessmentsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']] ?? null])
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            @foreach(config('available_languages') as $lang)
                                                                <div class="col-12">
                                                                    @include('admin.partial.attached_documents_with_actions', ['attFile' => $opinionsFiles[$row->row_num.'_'.$row->month.'_'.$lang['code']] ?? null])
                                                                </div>
                                                            @endforeach
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
                            <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                               class="btn btn-primary">{{ __('custom.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
