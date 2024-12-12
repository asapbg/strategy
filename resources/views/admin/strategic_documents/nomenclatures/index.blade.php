@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">

                        <div class="col-sm-4">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.strategic_document_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.strategic_document_type', 2) }}
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a class="btn btn-info btn-block nomenclature-btn" href="{{ route('admin.nomenclature.strategic_act_type') }}">
                                <i class="fas fa-file"></i>
                                {{ trans_choice('custom.nomenclature.strategic_act_type', 2) }}
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a class="btn btn-info btn-block nomenclature-btn"
                               href="{{ route('admin.nomenclature.authority_accepting_strategic') }}">
                                <i class="fas fa-university"></i>
                                {{ trans_choice('custom.nomenclature.authority_accepting_strategic', 2) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
