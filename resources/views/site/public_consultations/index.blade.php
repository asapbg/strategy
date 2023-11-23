@extends('layouts.site', ['fullwidth' => true])

@section('content')
<div class="row filter-results">
    <h2 class="mb-4">
        Търсене
    </h2>

    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="consultation-text" class="form-label">Търсене в Заглавие/Съдържание</label>
                <input type="text" class="form-control" id="consultation-text">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="consultation-number" class="form-label">Номер на консултация</label>
                <input type="text" class="form-control" id="consultation-number">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Брой резултати:</label>
                <select class="form-select">
                    <option value="1">10</option>
                    <option value="1">20</option>
                    <option value="1">30</option>
                    <option value="1">40</option>
                    <option value="1">50</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 action-btn-wrapper">
    <div class="col-md-3 col-sm-12">
        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
    </div>
    <div class="col-md-9 text-end col-sm-12">
        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
            Абониране</button>
        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
        <button class="btn btn-success text-success"><i
                class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
    </div>
</div>

<div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
    <div class="col-md-2">
        <p class="mb-0 cursor-pointer ">
            <i class="fa-solid fa-sort me-2"></i>Номер
        </p>
    </div>
    <div class="col-md-3 ">
        <p class="mb-0 cursor-pointer">
            <i class="fa-solid fa-sort me-2"></i>Tип консултация
        </p>
    </div>
    <div class="col-md-3 ">
        <p class="mb-0 cursor-pointer">
            <i class="fa-solid fa-sort me-2"></i>Област на политика
        </p>
    </div>
    <div class="col-md-2">
        <p class="mb-0 cursor-pointer">
            <i class="fa-solid fa-sort me-2"></i>Заглавие
        </p>
    </div>
    <div class="col-md-2">
        <p class="mb-0 cursor-pointer ">
            <i class="fa-solid fa-sort me-2"></i>Дата
        </p>
    </div>
</div>

<div class="row mb-2">
    <div class="col-12 mt-2">
        <div class="info-consul text-start">
            <p class="fw-600">
                Общо 98 резултата
            </p>
        </div>
    </div>
</div>

<div class="row">
@foreach($pk as $consultation)
    <div class="col-md-12 mb-4">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                {{--                            <div class="consult-img-holder">--}}
                {{--                                <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">--}}
                {{--                            </div>--}}
                <div class="consult-body">

                <div class="consul-item">
                    <div class="consult-item-header d-flex justify-content-between">
                        <div class="consult-item-header-link">
                            <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" class="text-decoration-none" title="{{ $consultation->title }}">
                                <h3 class="mb-2">{{ $consultation->title }}</h3>
                            </a>
                        </div>
                        <div class="consult-item-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"><span class="d-none">Delete</span></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                    <span class="d-none">Edit</span>
                                </i>
                            </a>
                        </div>
                    </div>
                    <div class="meta-consul mb-2">
                        <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                title="{{ __('custom.period') }}"></i> {{ displayDate($consultation->open_from) }} -
                            {{ displayDate($consultation->open_to) }}</span>
                    </div>
                    <div class="meta-consul">
                        <span><strong>{{ __('custom.status') }}:</strong>
                            <span class="{{ $consultation->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $consultation->inPeriod }}</span>
                        </span>
                        <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" title="{{ $consultation->title }}"><i
                                class="fas fa-arrow-right read-more text-end"></i><span class="d-none">{{ $consultation->title }}</span>
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection
