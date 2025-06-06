@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header fw-bold">
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

                        @if($item->files)
                            <div class="col-12">
                                <table class="table table-sm table-hover table-bordered mt-4">
                                    <thead>
                                    <tr>
                                        <th colspan="2"><strong>Файлове</strong></th>
                                    </tr>
                                    </thead>
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
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(isset($months) && sizeof($months) && isset($data) && $data)
                            <div class="accordion mt-4" id="accordionExample">
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
                                                            <h2 class="mb-0">
                                                                @php(usort($rowColumns, function ($a, $b) { return strcmp($a['ord'], $b['ord']); }))
                                                                <button class="btn btn-link btn-block text-left @if(!$loop->first) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $accordionIndex }}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $accordionIndex }}">
                                                                    {{ trans_choice('custom.months', 1) }} {{ $month }} - #{{ $rIndex }}  {{ html_entity_decode($rowColumns[0]['value']) }}
                                                                </button>
                                                            </h2>
                                                        </div>
                                                        <div id="collapse{{ $accordionIndex }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $accordionIndex }}" data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @php($rowColumns = array_combine(array_column($rowColumns, 'ord'), $rowColumns))
                                                                    @php(ksort($rowColumns))
                                                                    @foreach($rowColumns as $k => $col)
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <span class="fw-bold">{{ $col['label'] }}:</span>
                                                                                @if($col['dsc_id'] == config('lp_op_programs.lp_ds_col_institution_id'))
                                                                                    @if(!empty($row->name_institutions))
                                                                                        @php($nameInstitutions = json_decode($row->name_institutions))
                                                                                        @if(sizeof($nameInstitutions))
                                                                                            <p>
                                                                                                @foreach($nameInstitutions as $name)
                                                                                                    @if(!$loop->first){{ ',' }}<br>@endif{{ $name }}
                                                                                                @endforeach
                                                                                            </p>
                                                                                        @else
                                                                                            {{ '---' }}
                                                                                        @endif
                                                                                    @else
                                                                                        {{ '---' }}
                                                                                    @endif
                                                                                @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::BOOLEAN->value)
                                                                                    {{ $col['value'] ? 'Да' : 'Не' }}
                                                                                @elseif($col['type'] == \App\Enums\DynamicStructureColumnTypesEnum::TEXTAREA->value)
                                                                                    {!! html_entity_decode($col['value']) !!}
                                                                                @else
                                                                                    {{ html_entity_decode($col['value']) }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <div class="row mt-3 included-file-form">
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
                            <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                               class="btn btn-primary">{{ __('custom.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
