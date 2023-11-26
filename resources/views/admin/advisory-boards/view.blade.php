@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    {{ trans_choice('custom.advisory_boards', 1) }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mr-2 fw-bold"
                                       for="from_date">{{ __('validation.attributes.from_date') }}
                                    : </label>{{ date('m-Y', strtotime($item->created_at)) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row gap-3">
                                    <div class="col-12">
                                        <h3 class="border-bottom border-4 border-primary pb-2">
                                            {{ $item->name }}
                                        </h3>
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ trans_choice('custom.field_of_actions', 1) }}:</span>
                                        {{ $item->policyArea->name }}
                                    </div>

                                    <div class="col-12">
                                        <span
                                            class="fw-bold">{{ __('validation.attributes.council_attached_to') }}:</span>
                                        {{ $item->advisoryChairmanType->name . ($item->advisory_specific_name ? ', ' : '' ) }}
                                        @if($item->advisory_specific_name)
                                            {{ $item->advisory_specific_name }}
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('validation.attributes.act_of_creation') }}:</span>
                                        {{ $item->advisoryActType->name . ($item->advisory_act_specific_name ? ', ' : '') }}
                                        @if($item->advisory_act_specific_name)
                                            {{ $item->advisory_act_specific_name }}
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('validation.attributes.meetings_per_year') }}:</span>
                                        {{ $item->meetings_per_year }}
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('validation.attributes.report_at') }}:</span>
                                        {{ $item->reportInstitution->name . ($item->report_institution_specific_name ? ', ' : '') }}
                                        @if($item->report_institution_specific_name)
                                            {{ $item->report_institution_specific_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.advisory-boards.index') }}"
                               class="btn btn-primary">{{ __('custom.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
