@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <h3 class="mb-4">{{ __('custom.regulatory_framework_act_and_riles') }}</h3>

        @includeIf('admin.advisory-boards.partials.establishments')

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_establishment_files', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show-deleted-establishment-files"
                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'regulatory')">
                                <label class="custom-control-label"
                                       for="show-deleted-establishment-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    @if($item->establishment)
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modal-add-establishment-file">
                            <i class="fa fa-plus mr-3"></i>
                            {{ __('custom.add') . ' ' . __('custom.file') }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#regulatory' }}"
                       class="btn btn-info">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $item->establishment?->files, 'item' => $item])
            </div>
        </div>

        @includeIf('admin.advisory-boards.partials.organization-rules')

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_organization_rules_files', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show-deleted-organization-rules-files"
                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'regulatory')">
                                <label class="custom-control-label"
                                       for="show-deleted-organization-rules-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    @if($item->organizationRule)
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#modal-add-organization-rule-file">
                            <i class="fa fa-plus mr-3"></i>
                            {{ __('custom.add') . ' ' . __('custom.file') }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#regulatory' }}"
                       class="btn btn-info">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $item->organizationRule?->files, 'item' => $item])
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <hr/>
            </div>
        </div>
    </div>
</div>
