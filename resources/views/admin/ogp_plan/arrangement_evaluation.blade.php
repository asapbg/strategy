@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header fw-bold">
                    {{ trans_choice('ogp.arrangements', 1) }}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ogp.plan.arrangement.edit.evaluation_store', $ogpPlanArea->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}" />
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="add-suggestion">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="custom-left-border">Оценка на мярката:</h4>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'evaluation', 'required' => true])
                                        </div>
                                        <div class="row mb-4">
                                            @include('admin.partial.edit_field_translate', ['field' => 'evaluation_status', 'required' => false])
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="col-md-12">
                                                <button class="btn btn-success" title="{{ __('custom.save') }}">
                                                    <i class="fas fa-save me-2"></i> {{ __('custom.save') }}
                                                </button>
                                                <a href="{{ url()->previous() }}#area-tab-{{ $item->ogp_plan_area_id }}" class="btn btn-danger mr-1" title="{{ __('custom.cancel') }}">
                                                    <i class="fas fa-times me-2"></i> {{ __('custom.cancel') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


