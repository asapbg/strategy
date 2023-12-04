@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="decisions-tab" data-toggle="pill" href="#decisions" role="tab"
                               aria-controls="decisions"
                               aria-selected="false">{{ trans_choice('custom.meetings_and_decisions', 2) }}</a>
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
                        <div class="tab-pane fade" id="decisions" role="tabpanel" aria-labelledby="decisions">
                        </div>

                        <div class="tab-pane fade" id="functions" role="tabpanel" aria-labelledby="functions-tab">
                            @include('admin.partial.archive_list', ['items' => $programs])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
