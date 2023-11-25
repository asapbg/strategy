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
                            <a class="nav-link" id="chairmen-tab" data-toggle="pill" href="#chairmen" role="tab"
                               aria-controls="chairmen"
                               aria-selected="false">{{ trans_choice('custom.chairmen', 2) }}</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="general" role="tabpanel"
                             aria-labelledby="general-tab">
                            @include('admin.advisory-boards.tabs.general')
                        </div>

                        <div class="tab-pane fade" id="chairmen" role="tabpanel" aria-labelledby="chairmen-tab">
                            @include('admin.advisory-boards.tabs.chairmen')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @includeIf('admin.advisory-boards.create-chairman-form', ['resource' => $title_singular])
@endsection
