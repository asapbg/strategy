@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="ct-general-tab" href="{{ route('admin.ogp.plan.develop.edit', $plan) }}" role="tab" aria-controls="ct-general" aria-selected="false">Основна информация</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="schedule-tab" href="{{ route('admin.ogp.plan.develop.schedule', $plan) }}" role="tab" aria-controls="schedule-tab" aria-selected="true">
                                {{ __('custom.develop_plan_calendar') }}
                            </a>
                        </li>
                        @foreach($areas as $rows)
                            <li class="nav-item">
                                <a class="nav-link" id="area-tab-{{ $rows->id }}-tab" href="{{ route('admin.ogp.plan.develop.edit', $plan).'#area-tab-'.$rows->id }}" role="tab" aria-controls="area-tab-{{ $rows->id }}" aria-selected="false">
                                    {{ $rows->area->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="schedule-tab" role="tabpanel" aria-labelledby="schedule-tab-tab">
                            @php($disabled = $disabled ?? false)
                            <div class="row">
                                @if(isset($canCreateSchedule) && $canCreateSchedule)
                                    <h4 class="custom-left-border">Ново събитие:</h4>
                                    <form method="post" action="{{ route('admin.ogp.plan.develop.schedule.store') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="0">
                                        <input type="hidden" name="plan" value="{{ $plan->id }}">
                                        @foreach(\App\Models\OgpPlanSchedule::translationFieldsProperties() as $field => $properties)
                                            <div class="row">
                                                @include('admin.partial.edit_field_translate', ['item' => null, 'field' => $field, 'required' => $properties['required_all_lang'], 'translatableFields' => \App\Models\OgpPlanSchedule::translationFieldsProperties()])
                                            </div>
                                        @endforeach
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label" for="start_date">{{ __('custom.start') }} <span class="required">*</span></label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text" name="start_date" class="form-control form-control-sm datepicker @error('start_date'){{ 'is-invalid' }}@enderror" value="{{ old('start_date', '') }}" autocomplete="off">
                                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                        </div>
                                                        @error('start_date')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label" for="end_date">{{ __('custom.end') }} </label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text" name="end_date" class="form-control form-control-sm datepicker @error('end_date'){{ 'is-invalid' }}@enderror" value="{{ old('end_date', '')}}" autocomplete="off">
                                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                        </div>
                                                        @error('end_date')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button id="add" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                                        </div>
                                    </form>
                                @endif
                                @if(isset($items) && $items->count())
                                    <div class="col-12 mt-5">
                                        <h4 class="custom-left-border">Събития:</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('custom.title') }}</th>
                                                    <th>{{ __('custom.start') }}</th>
                                                    <th>{{ __('custom.end') }}</th>
                                                    <th>{{ __('custom.description') }}</th>
                                                    @if($canDeleteSchedule || $canEditSchedule)
                                                        <th>{{ __('custom.actions') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $row)
                                                    <tr>
                                                        @if(isset($canEditSchedule))
                                                            <td colspan="4">
                                                                <form id="schedule-{{ $row->id }}">
                                                                    <div class="col-12 text-danger main-error"></div>
                                                                    <div class="col-12 bg-success main-success mb-2"></div>
                                                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                                                    <input type="hidden" name="plan" value="{{ $plan->id }}">
                                                                    @foreach(\App\Models\OgpPlanSchedule::translationFieldsProperties() as $field => $properties)
                                                                        <div class="row">
                                                                            @include('admin.partial.edit_field_translate', ['item' => $row, 'field' => $field, 'required' => $properties['required_all_lang'], 'translatableFields' => \App\Models\OgpPlanSchedule::translationFieldsProperties()])
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="row mb-4">
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-12 control-label" for="start_date">{{ __('custom.start') }} <span class="required">*</span></label>
                                                                                <div class="col-12">
                                                                                    <div class="input-group">
                                                                                        <input type="text" name="start_date" class="form-control form-control-sm datepicker @error('start_date'){{ 'is-invalid' }}@enderror" value="{{ old('start_date', $row->start_date) }}" autocomplete="off">
                                                                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                                                    </div>
                                                                                    @error('start_date')
                                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                                    @enderror
                                                                                    <div class="ajax-error text-danger mt-1 error_start_date"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-12 control-label" for="end_date">{{ __('custom.end') }} </label>
                                                                                <div class="col-12">
                                                                                    <div class="input-group">
                                                                                        <input type="text" name="end_date" class="form-control form-control-sm datepicker @error('end_date'){{ 'is-invalid' }}@enderror" value="{{ old('end_date', $row->end_date)}}" autocomplete="off">
                                                                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                                                                                    </div>
                                                                                    @error('end_date')
                                                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                                                    @enderror
                                                                                    <div class="ajax-error text-danger mt-1 error_end_date"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </td>
                                                            @if($canDeleteSchedule || $canEditSchedule)
                                                                <td>
                                                                    @if($canEditSchedule)
                                                                        <button type="button" data-schedule="{{ $row->id }}" class="btn btn-sm btn-success save-schedule" title="Запиши">
                                                                            <i class="fas fa-save"></i>
                                                                        </button>
                                                                    @endif
                                                                    @if($canDeleteSchedule)
                                                                        <a href="javascript:;"
                                                                           class="btn btn-sm btn-danger mr-1 js-toggle-delete-resource-modal hidden"
                                                                           data-target="#modal-delete-resource"
                                                                           data-resource-id="{{ $row->id }}"
                                                                           data-resource-name="{{ "$row->name" }}"
                                                                           data-resource-delete-url="{{ route('admin.ogp.plan.develop.schedule.delete', $row->id) }}"
                                                                           data-toggle="tooltip"
                                                                           title="{{__('custom.deletion')}}">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @else
                                                            <td>{{ $row->name }}</td>
                                                            <td>{{ $row->startDate }}</td>
                                                            <td>{{ $row->endDate }}</td>
                                                            <td>{!! $row->description !!}</td>
                                                            @if($canDeleteSchedule)
                                                                <td>
                                                                    <a href="javascript:;"
                                                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                                                       data-target="#modal-delete-resource"
                                                                       data-resource-id="{{ $row->id }}"
                                                                       data-resource-name="{{ "$row->name" }}"
                                                                       data-resource-delete-url="{{ route('admin.ogp.plan.develop.schedule.delete', $row->id) }}"
                                                                       data-toggle="tooltip"
                                                                       title="{{__('custom.deletion')}}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if($canDeleteSchedule)
                @includeIf('modals.delete-resource', ['resource' => $title_singular])
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('.save-schedule').on('click', function (){
                if( canAjax ) {
                    canAjax = false;
                    let btn = $(this);
                    let lForm = $('#schedule-' + btn.data('schedule'));
                    let lMainError = $(lForm).find('.main-error')[0];
                    let lMainSuccess = $(lForm).find('.main-success')[0];
                    $('.main-success').html('');
                    $('.main-error').html('');
                    $('.ajax-error').html('');

                    let formData = $(lForm).serialize();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '<?php echo route("admin.ogp.plan.develop.schedule.store"); ?>',
                        data: formData,
                        success: function (result) {
                            if(typeof result.errors != 'undefined'){
                                let errors = Object.entries(result.errors);
                                for (let i = 0; i < errors.length; i++) {
                                    const search_class = '.error_' + errors[i][0];
                                    let errDiv = $(lForm).find(search_class);
                                    $(errDiv[0]).html(errors[i][1][0]);
                                }
                                canAjax = true;
                            } else if(typeof result.main_error != 'undefined'){
                                $(lMainError).html(result.main_error);
                                canAjax = true;
                            } else{
                                $(lMainSuccess).html(result.success_message);
                                canAjax = true;
                            }

                        },
                        error: function (result) {
                            canAjax = true;
                        }
                    });
                }


            });
        });
    </script>
@endpush
