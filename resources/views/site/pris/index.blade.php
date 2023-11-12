@extends('layouts.site')

@section('content')
    <div class="container mt-2 px-0">
        @foreach($items as $item)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
    {{--                            <div class="consult-img-holder">--}}
    {{--                                <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">--}}
    {{--                            </div>--}}
                            <div class="consult-body">
                                <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" class="consul-item">
                                    <h3>{{ $consultation->title }}</h3>
    {{--                                    <p><i class="fas fa-sitemap me-1 dark-blue" title="Сфера на действие"></i>Стратегическо планиране</p>--}}
    {{--                                    <div class="anotation text-secondary mb-2">--}}
    {{--                                        *Публикацията е обновена през месец юни 2023 г.--}}
    {{--                                    </div>--}}
                                    <div class="meta-consul">
                                        <span class="text-secondary"><i class="far fa-calendar text-secondary" title="{{ __('custom.period') }}"></i> {{ displayDate($consultation->open_from) }} - {{ displayDate($consultation->open_to) }}</span>
                                    </div>
                                    <div class="meta-consul">
                                        <span>{{ __('custom.status') }}: <span class="inactive-ks">{{ $consultation->inPeriod }}</span></span>
                                        <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}"><i class="fas fa-arrow-right read-more text-end"></i></a>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
