@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab"
                           aria-controls="general" aria-selected="true">{{ __('custom.general_info') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="members-tab" data-toggle="pill" href="#members" role="tab"
                           aria-controls="members"
                           aria-selected="false">{{ trans_choice('custom.member', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="functions-tab" data-toggle="pill" href="#functions" role="tab"
                           aria-controls="functions"
                           aria-selected="false">{{ trans_choice('custom.function', 2) }}</a>
                    </li>
                </ul>

                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="general" role="tabpanel"
                             aria-labelledby="general-tab">
                            <div class="row align-items-center justify-content-between mb-4">
                                <div class="col-md-3">
                                    <label class="control-label mr-2 fw-bold"
                                           for="from_date">{{ __('validation.attributes.from_date') }}
                                        : </label>{{ date('m-Y', strtotime($item->created_at)) }}
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('admin.advisory-boards.edit', $item) }}"
                                       class="btn btn-warning">{{ __('custom.editing') }}</a>
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
                                            <span
                                                class="fw-bold">{{ trans_choice('custom.field_of_actions', 1) }}:</span>
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
                                            <span
                                                class="fw-bold">{{ __('validation.attributes.act_of_creation') }}:</span>
                                                {{ $item->advisoryActType->name . ($item->advisory_act_specific_name ? ', ' : '') }}
                                                @if($item->advisory_act_specific_name)
                                                    {{ $item->advisory_act_specific_name }}
                                                @endif
                                            </div>

                                            <div class="col-12">
                                            <span
                                                class="fw-bold">{{ __('validation.attributes.meetings_per_year') }}:</span>
                                                {{ $item->meetings_per_year }}
                                            </div>

                                            <div class="col-12">
                                                <span
                                                    class="fw-bold">{{ __('validation.attributes.report_at') }}:</span>
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

                        <div class="tab-pane fade" id="members" role="tabpanel" aria-labelledby="members-tab">
                            <div class="row align-items-center justify-content-between mb-4">
                                <div class="col-md-3">
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#members' }}"
                                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-sm table-hover table-bordered" width="100%"
                                           cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>{{ __('custom.first_name') }}</th>
                                            <th>{{ __('custom.type') }}</th>
                                            <th>{{ __('forms.job') }}</th>
                                            <th>{{ trans_choice('custom.representatives_from', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($members) && $members->count() > 0)
                                            @foreach($members as $member)
                                                <tr>
                                                    <td>{{ $member->id }}</td>
                                                    <td>{{ $member->name }}</td>
                                                    <td>{{ trans_choice('custom.' . Str::lower(\App\Enums\AdvisoryTypeEnum::tryFrom($member->advisory_type_id)->name), 1) }}</td>
                                                    <td>{{ $member->advisoryChairmanType?->name }}</td>
                                                    <td>{{ $member->consultationLevel?->name }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="functions" role="tabpanel" aria-labelledby="functions-tab">
                            <div class="row align-items-center justify-content-between mb-4">
                                <div class="col-md-3">
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#functions' }}"
                                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    @if(isset($functions) && sizeof($functions))
                                        @foreach($functions as $function)
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label>{{ __('custom.description') }} ({{ Str::upper($function['locale']) }})</label>
                                                    {!! $function->description !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
