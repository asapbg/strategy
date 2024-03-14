@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">Основна информация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="schedule-tab" href="{{ route('admin.ogp.plan.develop.schedule', $item) }}" role="tab" aria-controls="schedule-tab" aria-selected="false">
                            {{ __('custom.develop_plan_calendar') }}
                        </a>
                    </li>
                    @foreach($areas as $rows)
                    <li class="nav-item">
                        <a class="nav-link" id="area-tab-{{ $rows->id }}-tab" data-toggle="pill" href="#area-tab-{{ $rows->id }}" role="tab" aria-controls="area-tab-{{ $rows->id }}" aria-selected="false">
                            {{ $rows->area->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabsContent">
                    <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                        @include('admin.ogp_develop_plan.tab.main_info')
                    </div>
                    @foreach($areas as $rows)
                    <div class="tab-pane fade" id="area-tab-{{ $rows->id }}" role="tabpanel" aria-labelledby="area-tab-{{ $rows->id }}-tab">
{{--                        <div class="row mb-4">--}}
{{--                            <div class="col-12">--}}
{{--                                <a href="{{ route('admin.ogp.plan.develop.arrangement.edit', $rows->id) }}" class="btn btn-success">--}}
{{--                                    <i class="fas fa-plus me-2"></i> {{ __('ogp.add_new_arrangement') }}--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="row mb-5">
                            <form action="{{ route('admin.ogp.plan.develop.order_area', $rows) }}" method="post">
                                @csrf
                                <input type="hidden" name="ord" value="{{ $rows->ord }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-md-4 control-label" for="from_date">{{ __('custom.order') }}: <span class="required">*</span></label>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input type="number" name="ord" class="form-control form-control-sm @error('ord'){{ 'is-invalid' }}@enderror" aria-describedby="basic-addon2" value="{{ old('ord', $rows->ord) }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-sm btn-success" type="submit"><i class="fas fa-save mr-2"></i> {{ __('custom.save') }}</button>
                                                    </div>
                                                </div>
                                                @error('ord')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        @can('deleteDevelopArea', $item)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $rows->id }}"
                                               data-resource-name="{{ $rows->area->name }}"
                                               data-resource-delete-url="{{route('admin.ogp.plan.develop.delete_area',$rows->id)}}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.deletion')}}">
                                                <i class="fa fa-trash"></i> Изтриване на областта
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </form>
                        </div>


                        <h5>{{ trans_choice('ogp.proposals', 2) }}</h5>
                        <hr>

                        <div class="accordion" id="accordionExample">
                        @foreach($rows->offers()->orderBy('created_at', 'desc')->get() as $offer)
                            @include('admin.ogp_develop_plan.offer_row', ['item' => $offer, 'iteration' => $loop->iteration, 'onlyView' => true])
                        @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
        @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.ogp_areas', 1)])
    </div>
</section>
@endsection
