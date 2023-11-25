@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    @can('update', $item)
                        <a class="btn btn-sm btn-primary main-color mt-2" target="_blank" href="{{ route('admin.consultations.public_consultations.edit', $item) }}">
                            <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</a>
                    @endcan
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
