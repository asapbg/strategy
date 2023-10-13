@extends('layouts.admin')

@section('content')
    @php($locales = config('available_languages'))
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">{{ __('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::keyByValue($item->type)) }}</div>
                <div class="card-body">
                    @if($item->id)
                        <form class="row mb-4" action="{{ route('admin.dynamic_structures.add_column') }}" method="post">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
                            <input type="hidden" name="row_id" value="0">
                            @foreach($locales as $loc)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="label_{{ $loc['code']  }}">{{ __('custom.name').' ('.mb_strtoupper($loc['code']).')' }} <span class="required">*</span> </label>
                                        <div>
                                            <input name="label_{{ $loc['code']  }}" value="{{ old('label_'.$loc['code'], '') }}" class="form-control form-control-sm @error('label_'.$loc['code']) is-invalid @enderror" type="text" autocomplete="off">
                                        </div>
                                        @error('label_'.$loc['code'])
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="type">{{ __('custom.type') }} <span class="required">*</span> </label>
                                    <select class="form-control form-control-sm" name="type">
                                        <option value=""></option>
                                        <option value="text">{{ __('custom.text') }}</option>
                                        <option value="number">{{ __('custom.number') }}</option>
                                    </select>
                                    @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if(\App\Enums\DynamicStructureTypesEnum::hasGroupField($item->type))
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="in_group">{{ trans_choice('custom.groups', 1) }} <span class="required">*</span> </label>
                                        <input type="number" value="{{ old('in_group', 0) }}" name="in_group" class="form-control form-control-sm">
                                        @error('in_group')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-2">
                                <label></label>
                                <div class="form-group">
                                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            @php($colSpan = \App\Enums\DynamicStructureTypesEnum::hasGroupField($item->type) ? 3 : 2)
                            <tr>
                                <th class="text-center" colspan="{{ sizeof($locales) + $colSpan }}">{{ trans_choice('custom.columns', 2) }}</th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('custom.type') }}</th>
                                @if(\App\Enums\DynamicStructureTypesEnum::hasGroupField($item->type))
                                    <th>{{ trans_choice('custom.groups', 1) }}</th>
                                @endif
                                @foreach($locales as $loc)
                                    <th>{{ __('custom.name').' ('.mb_strtoupper($loc['code']).')' }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if($item->columns->count())
                                @foreach($item->columns as $col)
                                    <tr>
                                        <td>{{ $col->id }}</td>
                                        <td>{{ $col->type }}</td>
                                        @if(\App\Enums\DynamicStructureTypesEnum::hasGroupField($item->type))
                                            <td>{{ $col['in_group'] ? __('custom.dynamic_structures.type.'.\App\Enums\DynamicStructureTypesEnum::keyByValue($item->type).'.'.$col['in_group']) : '' }}</td>
                                        @endif
                                        @foreach($locales as $loc)
                                            <td>{{ $col->translate($loc['code'])->label }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
