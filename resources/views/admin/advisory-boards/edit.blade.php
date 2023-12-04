@extends('layouts.admin')

@section('content')

    @php
        $can_foreach_translations = isset($item->translations) && $item->translations->count() > 0;
    @endphp

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
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
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="general" role="tabpanel"
                             aria-labelledby="general-tab">
                            @include('admin.advisory-boards.tabs.general')
                        </div>

                        <div class="tab-pane fade" id="members" role="tabpanel" aria-labelledby="members-tab">
                            @include('admin.advisory-boards.tabs.members')
                        </div>

                        <div class="tab-pane fade" id="secretary-of-council" role="tabpanel"
                             aria-labelledby="secretary-of-council-tab">
                            @include('admin.advisory-boards.tabs.secretary-of-council')
                        </div>

                        <div class="tab-pane fade" id="secretariat" role="tabpanel" aria-labelledby="secretariat-tab">
                            @include('admin.advisory-boards.tabs.secretariat')
                        </div>

                        <div class="tab-pane fade" id="functions" role="tabpanel" aria-labelledby="functions-tab">
                            @include('admin.advisory-boards.tabs.functions')
                        </div>

                        <div class="tab-pane fade" id="regulatory" role="tabpanel" aria-labelledby="regulatory">
                            @include('admin.advisory-boards.tabs.regulatory-framework')
                        </div>

                        <div class="tab-pane fade" id="decisions" role="tabpanel" aria-labelledby="decisions">
                            @include('admin.advisory-boards.tabs.meetings-decisions')
                        </div>

                        <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom">
                            @include('admin.advisory-boards.tabs.custom')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete, Restore -->
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.meetings', 1), 'modal_id' => 'modal-delete-meeting'])
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.secretary', 1), 'modal_id' => 'modal-delete-secretary-council'])
    @includeIf('modals.delete-resource', ['resource' => __('custom.file'), 'modal_id' => 'modal-delete-file'])
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.section', 1), 'modal_id' => 'modal-delete-section'])
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.member', 1)])
    @includeIf('modals.restore-resource', ['resource' => trans_choice('custom.meetings', 1), 'modal_id' => 'modal-restore-meeting'])
    @includeIf('modals.restore-resource', ['resource' => trans_choice('custom.secretary', 1), 'modal_id' => 'modal-restore-secretary-council'])
    @includeIf('modals.restore-resource', ['resource' => trans_choice('custom.section', 1), 'modal_id' => 'modal-restore-section'])
    @includeIf('modals.restore-resource', ['resource' => __('custom.file'), 'modal_id' => 'modal-restore-file'])
    @includeIf('modals.restore-resource', ['resource' => trans_choice('custom.member', 1)])

    <!-- Modals -->
    @includeIf('admin.advisory-boards.modals.create-section-form')
    @includeIf('admin.advisory-boards.modals.edit-section')
    @includeIf('admin.advisory-boards.modals.create-meeting-form')
    @includeIf('admin.advisory-boards.modals.edit-meeting')
    @includeIf('admin.advisory-boards.modals.create-chairman-form', ['resource' => $title_singular])
    @includeIf('admin.advisory-boards.modals.edit-member-form', ['resource' => $title_singular])
    @includeIf('admin.advisory-boards.modals.add-custom-file')
    @includeIf('admin.advisory-boards.modals.add-regulatory-framework-file')
    @includeIf('admin.advisory-boards.modals.add-secretariat-file')
    @includeIf('admin.advisory-boards.modals.add-function-file')
    @includeIf('admin.advisory-boards.modals.add-meeting-file')
    @includeIf('admin.advisory-boards.modals.edit-file')
    @includeIf('admin.advisory-boards.modals.create-secretary-council-form')
    @includeIf('admin.advisory-boards.modals.edit-secretary-council-form')

    <!-- Scripts -->
    @includeIf('admin.advisory-boards.scripts')
@endsection
