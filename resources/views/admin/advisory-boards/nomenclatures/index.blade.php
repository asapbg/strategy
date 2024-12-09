@extends('layouts.admin')

@section('title')
    {{ __('custom.nomenclature') }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('custom.nomenclatures', 2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin">Начало</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans_choice('custom.nomenclatures', 2) }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn align-content-center"
                               href="{{ route('admin.advisory-boards.nomenclature.field-of-actions.index') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('validation.attributes.field_of_action', 2) }}
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn align-content-center"
                               href="{{ route('admin.advisory-boards.nomenclature.authority-advisory-board') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.authority_advisory_board', 2) }}
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn align-content-center"
                               href="{{ route('admin.advisory-boards.nomenclature.advisory-act-type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.advisory_act_type', 2) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
