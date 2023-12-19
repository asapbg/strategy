@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <a href="{{ route('impact_assessment.forms') }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.impact_assessments', 1) }}
                        </a>
                    </div>
                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.type') }}</th>
                            <th>{{ __('validation.attributes.user') }}</th>
                            <th>{{__('custom.status')}}</th>
                            <th>{{__('custom.updated_at')}}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                @php($json = $item->dataParsed)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ __("forms.$item->form") }}
                                        @if(isset($json['institution']) && !empty($json['institution']))
                                            <i class="d-block">
                                                {{ $json['institution'] }}
                                            </i>
                                        @endif
                                        @if(isset($json['regulatory_act']) && !empty($json['regulatory_act']))
                                            <i class="d-block text-primary">
                                                {{ $json['regulatory_act'] }}
                                            </i>
                                        @endif
                                    </td>
                                    <td>{{ $item->user ? $item->user->fullName() : '---' }}</td>
                                    <td>@if(!(isset($json['submit']) && (int)$json['submit'])){{ __('custom.draft') }}@else{{ __('custom.completed_f') }}@endif</td>
                                    <td>{{ displayDate($item->updated_at) }}</td>
                                    <td class="text-center">
                                        @if(\App\Http\Controllers\ImpactAssessmentController::getSteps($item->form) > $json['step'] && $item->user->id == auth()->user()->id)
                                            <a href="{{ route('impact_assessment.form', ['form' => $item->form, 'inputId' => $item->id, 'step' => $json['step']]) }}"
                                                target="_blank"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('impact_assessment.show', ['form' => $item->form, 'inputId' => $item->id]) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.view') }}">
                                                <i class="fa fa-search"></i>
                                            </a>
                                            <a href="{{ route('impact_assessment.pdf', ['form' => $item->form, 'inputId' => $item->id]) }}"
                                               class="btn btn-sm btn-success"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.download') }}">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                    </td>
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


