@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Страница 404')

@section('content')

<div class="row py-5">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <h2 class="mb-3 text-center fs-3">Изглежда тази страница не съществува!</h2>
        <h3 class="mb-5 fs-18 fw-normal text-center">Опитайте да потърсите информацията, която търсите чрез търсачката или посетете <a href="/">Началната страница!</a></h3>
       
        <div class="d-flex justify-content-center search-not-found-wrapper">
            <input type="text" name="not-found-search" id="not-found-search" placeholder="Търсене в портала...">
            <button class="btn btn-primary w-auto ms-2"><i class="fas fa-search me-1"></i>Търсене</button>

        </div>
    </div>
    <div class="col-md-2">
    </div>
</div>

@endsection
