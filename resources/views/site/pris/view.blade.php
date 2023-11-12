@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    <button class="btn btn-sm btn-primary main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>Редактиране на консултация</button>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid mt-2 px-0">
        <div class="row">
            @include('site.public_consultations.content')
            @include('site.public_consultations.timeline')
        </div>
    </div>
@endsection
