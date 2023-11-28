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
                            <a class="nav-link" id="functions-tab" data-toggle="pill" href="#functions" role="tab"
                               aria-controls="functions"
                               aria-selected="false">{{ trans_choice('custom.function', 2) }}</a>
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

                        <div class="tab-pane fade" id="functions" role="tabpanel" aria-labelledby="functions-tab">
                            @include('admin.advisory-boards.tabs.functions')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @includeIf('modals.delete-resource', ['resource' => __('custom.file'), 'modal_id' => 'modal-delete-file'])
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.member', 1)])
    @includeIf('modals.restore-resource', ['resource' => __('custom.file'), 'modal_id' => 'modal-restore-file'])
    @includeIf('modals.restore-resource', ['resource' => trans_choice('custom.member', 1)])
    @includeIf('admin.advisory-boards.modals.create-chairman-form', ['resource' => $title_singular])
    @includeIf('admin.advisory-boards.modals.edit-chairman-form', ['resource' => $title_singular])
    @includeIf('admin.advisory-boards.modals.add-function-file')
    @includeIf('admin.advisory-boards.modals.edit-function-file')
    @includeIf('admin.advisory-boards.scripts')
@endsection
