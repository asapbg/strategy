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
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.institution_level') }}">
                                <i class="fas fa-university"></i>
                                {{ trans_choice('custom.nomenclature.institution_level', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.strategic_documents.institutions.index') }}">
                                <i class="fas fa-university"></i>
                                {{ trans_choice('custom.institutions', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.act_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.act_type', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.legal_act_type') }}">
                                <i class="fas fa-folder-open"></i>
                                {{ trans_choice('custom.nomenclature.legal_act_type', 2) }}
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.strategic_document_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.strategic_document_type', 2) }}
                            </a>
                        </div>
{{--                        <div class="col-sm-3">--}}
{{--                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.consultation_type') }}">--}}
{{--                                <i class="fas fa-folder-open"></i>--}}
{{--                                {{ trans_choice('custom.nomenclature.consultation_type', 2) }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.program_project') }}">
                                <i class="fas fa-folder-open"></i>
                                {{ trans_choice('custom.nomenclature.program_project', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.link_category') }}">
                                <i class="fas fa-folder-open"></i>
                                {{ trans_choice('custom.nomenclature.link_category', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.publication_category') }}">
                                <i class="fas fa-folder"></i>
                                {{ trans_choice('custom.nomenclature.publication_category', 2) }}
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                                href="{{ route('admin.nomenclature.regulatory_act_type') }}">
                                <i class="fas fa-folder"></i>
                                {{ trans_choice('custom.regulatory_act_types', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.regulatory_act') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.regulatory_acts', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.advisory_act_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.advisory_act_type', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.strategic_act_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.strategic_act_type', 2) }}
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                                href="{{ route('admin.nomenclature.advisory_chairman_type') }}">
                                <i class="fas fa-users"></i>
                                {{ trans_choice('custom.nomenclature.advisory_chairman_type', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.authority_advisory_board') }}">
                                <i class="fas fa-university"></i>
                                {{ trans_choice('custom.nomenclature.authority_advisory_board', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.authority_accepting_strategic') }}">
                                <i class="fas fa-university"></i>
                                {{ trans_choice('custom.nomenclature.authority_accepting_strategic', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.field_of_actions.index') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('validation.attributes.field_of_action', 2) }}
                            </a>
                        </div>
{{--                        <div class="col-sm-3">--}}
{{--                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.consultation_level') }}">--}}
{{--                                <i class="fas fa-folder-open"></i>--}}
{{--                                {{ trans_choice('custom.nomenclature.consultation_level', 2) }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.tag') }}">
                                <i class="fas fa-tags"></i>
                                {{ trans_choice('custom.nomenclature.tags', 2) }}
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.consultation_document_type') }}">
                                <i class="fas fa-tags"></i>
                                {{ trans_choice('custom.nomenclature.consultation_document_type', 2) }}
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.law') }}">
                                <i class="fas fa-gavel"></i>
                                {{ trans_choice('custom.nomenclature.laws', 2) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    </div>
@endsection
