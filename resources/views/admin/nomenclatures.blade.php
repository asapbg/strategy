@extends('layouts.admin')

@section('title')
    {{__('custom.nomenclature')}}
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
                <div class="row">
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.institution_level') }}">
                            <p>{{ trans_choice('custom.nomenclature.institution_level', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.consultation_category') }}">
                            <p>{{ trans_choice('custom.nomenclature.consultation_category', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.act_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.act_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.legal_act_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.legal_act_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.strategic_document_level') }}">
                            <p>{{ trans_choice('custom.nomenclature.strategic_document_level', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.strategic_document_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.strategic_document_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.authority_accepting_strategic') }}">
                            <p>{{ trans_choice('custom.nomenclature.authority_accepting_strategic', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.authority_advisory_board') }}">
                            <p>{{ trans_choice('custom.nomenclature.authority_advisory_board', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.advisory_act_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.advisory_act_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.strategic_act_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.strategic_act_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.advisory_chairman_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.advisory_chairman_type', 2) }}</p>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('admin.nomenclature.document_type') }}">
                            <p>{{ trans_choice('custom.nomenclature.document_type', 2) }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
