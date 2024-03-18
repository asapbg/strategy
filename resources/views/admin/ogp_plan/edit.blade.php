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
                    @if($evaluationEdit)
                        <li class="nav-item">
                            <a class="nav-link" id="report-tab" data-toggle="pill" href="#report" role="tab" aria-controls="report" aria-selected="false">
                                {{ __('custom.report_evaluation') }}
                            </a>
                        </li>
                    @endif
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
                        @include('admin.ogp_plan.tab.main_info', ['disabled' => !$mainInfoEdit])
                    </div>
                    @if($evaluationEdit)
                        <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                            <div class="row mb-5">
                                <h4 class="custom-left-border">Основна информация:</h4>
                                <form method="post" action="{{ route('admin.ogp.plan.report.store') }}">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $item->id }}">
                                    <div class="row">
                                        @include('admin.partial.edit_field_translate', ['field' => 'report_title', 'required' => true, 'translatableFields' => \App\Models\OgpPlan::translationFieldsProperties()])
                                    </div>
                                    <div class="row">
                                        @include('admin.partial.edit_field_translate', ['field' => 'report_content', 'required' => false, 'translatableFields' => \App\Models\OgpPlan::translationFieldsProperties()])
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label" for="report_published_at">{{ __('custom.published_at') }}</label>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <input type="text" id="report_published_at" name="report_published_at" class="form-control form-control-sm datepicker @error('report_published_at'){{ 'is-invalid' }}@enderror" value="{{ old('report_published_at', displayDate($item->report_evaluation_published_at) ?? '') }}" autocomplete="off">
                                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                    </div>
                                                    @error('report_published_at')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="add" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <h4 class="custom-left-border">Файлове:</h4>
                                <form class="row" action="{{ route('admin.upload.file.languages', ['object_id' => $item && $item->id ? $item->id : 0, 'object_type' => \App\Models\File::CODE_OBJ_OGP, 'doc_type' => \App\Enums\DocTypesEnum::OGP_REPORT_EVALUATION->value]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="formats" value="ALLOWED_FILE_OGP_EVALUATION">
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-md-6 mb-3">
                                            <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}<span class="required">*</span> </label>
                                            <input value="{{ old('description_'.$lang['code'], '') }}" class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror" id="description_{{ $lang['code'] }}" type="text" name="description_{{ $lang['code'] }}">
                                            @error('description_'.$lang['code'])
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                    @foreach(config('available_languages') as $lang)
                                        <div class="col-md-6 mb-3">
                                            <label for="file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}<span class="required">*</span> </label>
                                            <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
                                            @error('file_'.$lang['code'])
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                    <div class="col-md-4">
                                        <br>
                                        <button id="save" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                                    </div>
                                </form>
                                @if($item->reportEvaluation->count())
                                    <table class="table table-sm table-hover table-bordered mt-5">
                                        <tbody>
                                        <tr>
                                            <th>{{ __('custom.name') }}</th>
                                            <th></th>
                                        </tr>
                                        @foreach(config('available_languages') as $lang)
                                            @foreach($item->reportEvaluation as $f)
                                                @php($code = $lang['code'])
                                                @if($code == $f->locale)
                                                    <tr>
                                                        <td>{{ $f->{'description_'.$code} }} ({{ strtoupper($code) }})</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-download me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id, 'disk' => 'public_uploads']) }}">
                                                                <i class="fas fa-trash me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endif
                    @foreach($areas as $rows)
                        <div class="tab-pane fade" id="area-tab-{{ $rows->id }}" role="tabpanel" aria-labelledby="area-tab-{{ $rows->id }}-tab">
                            <div class="row mb-5">
                                <form action="{{ route('admin.ogp.plan.order_area', $rows) }}" method="post">
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
                                            @can('deleteArea', $item)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $rows->id }}"
                                                   data-resource-name="{{ $rows->area->name }}"
                                                   data-resource-delete-url="{{route('admin.ogp.plan.delete_area',$rows->id)}}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.deletion')}}">
                                                    <i class="fa fa-trash"></i> Изтриване на областта
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <h5>{{ __('ogp.action_plan_measures') }}</h5>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <a href="{{ route('admin.ogp.plan.arrangement.edit', $rows->id) }}" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i> {{ __('ogp.add_new_arrangement') }}
                                    </a>
                                </div>
                            </div>

                            <div class="accordion" id="accordionExample">
                            @foreach($rows->arrangements()->orderBy('created_at', 'desc')->get() as $arrangement)
                                @include('admin.ogp_plan.arrangement_row', ['item' => $arrangement, 'iteration' => $loop->iteration, 'evaluationEdit' => $evaluationEdit, 'disableEdit' => !$mainInfoEdit])
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.ogp_areas', 1)])
    @includeIf('modals.delete-resource', ['modal_id' =>'arrangement_delete_modal', 'resource' => trans_choice('ogp.arrangements', 1)])
</section>
@endsection
