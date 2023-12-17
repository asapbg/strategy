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
                        <a class="nav-link" id="secretary-of-council-tab" data-toggle="pill"
                           href="#secretary-of-council" role="tab"
                           aria-controls="secretary-of-council"
                           aria-selected="false">{{ trans_choice('custom.secretary_of_council', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="secretariat-tab" data-toggle="pill" href="#secretariat" role="tab"
                           aria-controls="secretariat"
                           aria-selected="false">{{ trans_choice('custom.secretariat', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="functions-tab" data-toggle="pill" href="#functions" role="tab"
                           aria-controls="functions"
                           aria-selected="false">{{ trans_choice('custom.function', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="regulatory-tab" data-toggle="pill" href="#regulatory" role="tab"
                           aria-controls="regulatory"
                           aria-selected="false">{{ trans_choice('custom.regulatory_framework', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="decisions-tab" data-toggle="pill" href="#decisions" role="tab"
                           aria-controls="decisions"
                           aria-selected="false">{{ trans_choice('custom.meetings_and_decisions', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tab" data-toggle="pill" href="#custom" role="tab"
                           aria-controls="custom"
                           aria-selected="false">{{ trans_choice('custom.custom_sections', 2) }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="archive-tab" data-toggle="pill" href="#archive" role="tab"
                           aria-controls="archive"
                           aria-selected="false">{{ __('custom.archive') }}</a>
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
                                       class="btn btn-info">{{ __('custom.editing') }}</a>
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
                                                {{ $item->policyArea?->name }}
                                            </div>

                                            <div class="col-12">
                                                <span
                                                    class="fw-bold">{{ __('custom.presence_npo_representative') }}:</span>
                                                {{ $item->has_npo_presence ? __('custom.yes') : __('custom.no') }}
                                            </div>

                                            <div class="col-12">
                                                <span
                                                    class="fw-bold">{{ __('validation.attributes.authority_id') }}:</span>
                                                {{ $item->authority?->name . ($item->advisory_specific_name ? ', ' : '' ) }}
                                            </div>

                                            <div class="col-12">
                                            <span
                                                class="fw-bold">{{ __('validation.attributes.act_of_creation') }}:</span>
                                                {{ $item->advisoryActType?->name . ($item->advisory_act_specific_name ? ', ' : '') }}
                                            </div>

                                            <div class="col-12">
                                                <span
                                                    class="fw-bold">{{ trans_choice('validation.attributes.advisory_chairman_type_id', 1) }}:</span>
                                                {{ $item->advisoryChairmanType?->name }}
                                            </div>

                                            <div class="col-12">
                                                <span
                                                    class="fw-bold">{{ __('validation.attributes.vice_chairman') }}:</span>
                                                {{ $item->hasViceChairman ? __('custom.yes') : __('custom.no') }}
                                            </div>

                                            <div class="col-12">
                                            <span
                                                class="fw-bold">{{ __('validation.attributes.meetings_per_year') }}:</span>
                                                {{ $item->meetings_per_year }}
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
                                       class="btn btn-info">{{ __('custom.editing') }}</a>
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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($item->members) && $item->members->count() > 0)
                                            @foreach($item->members as $member)
                                                <tr>
                                                    <td>{{ $member->id }}</td>
                                                    <td>{{ $member->member_name }}</td>
                                                    <td>{{ trans_choice('custom.' . Str::lower(\App\Enums\AdvisoryTypeEnum::tryFrom($member->advisory_type_id)->name), 1) }}</td>
                                                    <td>{{ $member->advisoryChairmanType?->name }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="secretariat" role="tabpanel" aria-labelledby="secretariat-tab">
                            @include('admin.advisory-boards.tabs.secretariat', ['view_mode' => true])
                        </div>

                        <div class="tab-pane fade" id="functions" role="tabpanel" aria-labelledby="functions-tab">
                            <div class="row align-items-center justify-content-between mb-4">
                                <div class="col-auto">
                                    <h3>{{ trans_choice('custom.section', 1) }}</h3>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#functions' }}"
                                       class="btn btn-info">{{ __('custom.editing') }}</a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    @if(isset($functions) && sizeof($functions))
                                        @foreach($functions as $function)
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label>{{ __('custom.description') }}
                                                        ({{ Str::upper($function['locale']) }})</label>
                                                    {!! $function->description !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <hr/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-auto">
                                    <h3>{{ trans_choice('custom.files', 2) }}</h3>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>{{ __('custom.name') }}</th>
                                            <th>{{ __('custom.description') }}</th>
                                            <th>{{ __('validation.attributes.created_at') }}</th>
                                            <th>{{ __('custom.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($files) && $files->count() > 0)
                                            @foreach($files as $file)
                                                <tr>
                                                    <td>{{ $file->id }}</td>
                                                    <td>{{ $file->custom_name ?? $file->filename }}</td>
                                                    <td>{{ $file->description }}</td>
                                                    <td>{{ $file->created_at }}</td>
                                                    <td>
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                @can('view', $item)
                                                                    <div class="row">
                                                                        <div class="col-auto">
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-outline-info preview-file-modal mr-2"
                                                                                    data-file="{{ $file->id }}"
                                                                                    data-url="{{ route('admin.preview.file.modal', ['id' => $file->id]) }}">
                                                                                {!! fileIcon($file->content_type) !!}
                                                                                {{ __('custom.preview') }}
                                                                            </button>
                                                                        </div>

                                                                        <div class="col-auto">
                                                                            <a class="btn btn-sm btn-info mr-2"
                                                                               href="{{ route('admin.download.file', $file) }}"
                                                                               target="_blank"
                                                                               title="{{ __('custom.download') }}">
                                                                                <i class="fa fa-download"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="regulatory" role="tabpanel" aria-labelledby="regulatory">
                            @include('admin.advisory-boards.tabs.regulatory-framework', ['view_mode' => true])
                        </div>

                        <div class="tab-pane fade" id="decisions" role="tabpanel" aria-labelledby="decisions">
                            @include('admin.advisory-boards.tabs.meetings-decisions', ['view_mode' => true])
                        </div>

                        <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom">
                            @include('admin.advisory-boards.tabs.custom', ['view_mode' => true])
                        </div>

                        <div class="tab-pane fade" id="archive" role="tabpanel" aria-labelledby="archive">
                            @include('admin.advisory-boards.tabs.archive')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
