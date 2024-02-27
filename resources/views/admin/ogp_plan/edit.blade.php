@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">Основна информация</a>
                    </li>
                    @foreach($areas as $rows)
                    <li class="nav-item">
                        <a class="nav-link" id="area-tab-{{ $rows->id }}-tab" data-toggle="pill" href="#area-tab-{{ $rows->id }}" role="tab" aria-controls="area-tab-{{ $rows->id }}" aria-selected="false">
                            {{ $rows->area->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabsContent">
                    <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                        @include('admin.ogp_plan.tab.main_info')
                    </div>
                    @foreach($areas as $rows)
                    <div class="tab-pane fade" id="area-tab-{{ $rows->id }}" role="tabpanel" aria-labelledby="area-tab-{{ $rows->id }}-tab">
                        <div class="row mb-4">
                            <div class="col-12">
                                <a href="{{ route('admin.ogp.plan.arrangement.edit', $rows->id) }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> {{ __('ogp.add_new_arrangement') }}
                                </a>
                            </div>
                        </div>
                        <h5>{{ __('ogp.action_plan_measures') }}</h5>
                        <hr>

                        <div class="accordion" id="accordionExample">
                        @foreach($rows->arrangements()->orderBy('created_at', 'desc')->get() as $arrangement)
                            @include('admin.ogp_plan.arrangement_row', ['item' => $arrangement, 'iteration' => $loop->iteration])
                        @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
