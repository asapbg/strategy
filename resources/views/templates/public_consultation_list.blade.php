@extends('layouts.site')
<style>
    .public-page {
        padding: 0px 0px !important;
    }
</style>
@section('pageTitle', 'Публични консултации')


@section('content')

{{--    <section>--}}
{{--        <div class="container-fluid p-0">--}}
{{--            <div class="row breadcrumbs py-1">--}}
{{--                <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">--}}
{{--                    <ol class="breadcrumb mb-0">--}}
{{--                        <li class="breadcrumb-item"><a href="#">Начало</a></li>--}}
{{--                        <li class="breadcrumb-item"><a href="#">Обществени консултации</a></li>--}}
{{--                    </ol>--}}
{{--                    </ol>--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--    </section>--}}



    <div class="container-fluid mt-2 px-0">
        <div class="row">

            <section class="public-page">
                <div class=" container " id="app">



                    <div class="container mt-2 px-0">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">



                                        <div class="consult-body">
                                            <a href="{{ route('public_consultation.view', ['id' => 1]) }}" class="consul-item">
                                                <h3>Консултация 2</h3>




                                                <div class="meta-consul">
                                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Период"></i> 12.11.2023 - 23.12.2023</span>
                                                </div>
                                                <div class="meta-consul">
                                                    <span>Статус: <span class="inactive-ks">Активна</span></span>
                                                    <a href="{{ route('public_consultation.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">



                                        <div class="consult-body">
                                            <a href="{{ route('public_consultation.view', ['id' => 1]) }}" class="consul-item">
                                                <h3>Консултация 3</h3>




                                                <div class="meta-consul">
                                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Период"></i> 12.11.2023 - 26.01.2024</span>
                                                </div>
                                                <div class="meta-consul">
                                                    <span>Статус: <span class="inactive-ks">Активна</span></span>
                                                    <a href="{{ route('public_consultation.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">



                                        <div class="consult-body">
                                            <a href="{{ route('public_consultation.view', ['id' => 1]) }}" class="consul-item">
                                                <h3>dsvdvs</h3>




                                                <div class="meta-consul">
                                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Период"></i> 10.11.2023 - 24.11.2023</span>
                                                </div>
                                                <div class="meta-consul">
                                                    <span>Статус: <span class="inactive-ks">Активна</span></span>
                                                    <a href="{{ route('public_consultation.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">



                                        <div class="consult-body">
                                            <a href="{{ route('public_consultation.view', ['id' => 1]) }}" class="consul-item">
                                                <h3>drgdfg</h3>




                                                <div class="meta-consul">
                                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Период"></i> 17.11.2023 - 24.12.2023</span>
                                                </div>
                                                <div class="meta-consul">
                                                    <span>Статус: <span class="inactive-ks">Активна</span></span>
                                                    <a href="{{ route('public_consultation.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">



                                        <div class="consult-body">
                                            <a href="{{ route('public_consultation.view', ['id' => 1]) }}" class="consul-item">
                                                <h3>sdfgdgd</h3>




                                                <div class="meta-consul">
                                                    <span class="text-secondary"><i class="far fa-calendar text-secondary" title="Период"></i> 18.11.2023 - 31.12.2023</span>
                                                </div>
                                                <div class="meta-consul">
                                                    <span>Статус: <span class="inactive-ks">Активна</span></span>
                                                    <a href="{{ route('public_consultation.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>





        </div>
    </div>


    </body>
@endsection
