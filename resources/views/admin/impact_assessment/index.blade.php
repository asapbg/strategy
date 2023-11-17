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
                            <th>{{__('custom.updated_at')}}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ __("forms.$item->form") }}
                                        @if(rand(0,1))
                                            <i class="d-block">
                                                @if(rand(0,1))
                                                    Законодателна програма 01 януари - 30 юни от 2017 г.
                                                @else
                                                    Оперативна програма 06 юли - 31 декември от 2019 г.
                                                @endif
                                            </i>
                                            <a href="" class="d-block text-primary"><i>Наименование на запис от законодателна/оперативна програма</i></a>
                                        @endif
                                    </td>
                                    <td>{{ $item->user ? $item->user->fullName() : '---' }}</td>
                                    <td>{{ displayDate($item->updated_at) }}</td>
                                    <td class="text-center">
                                        @if(\App\Http\Controllers\ImpactAssessmentController::getSteps($item->form) > $item->dataParsed['step'] && $item->user->id == auth()->user()->id)
                                            <a href="{{ route('impact_assessment.form', ['form' => $item->form, 'inputId' => $item->id, 'step' => $item->dataParsed['step']]) }}"
                                                target="_blank"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('impact_assessment.show', ['form' => $item->form, 'inputId' => $item->id]) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-warning"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.view') }}">
                                                <i class="fa fa-eye"></i>
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


