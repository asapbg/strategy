@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive">
                    @includeIf('admin.strategic_documents.documents.file-form')
                </div>
            </div>
        </div>
    </section>
@endsection
