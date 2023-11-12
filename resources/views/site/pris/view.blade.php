@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    <button class="btn btn-sm btn-primary main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</button>
                </div>
            </div>
        </div>
    </section>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                @include('site.pris.side_menu')

            <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">


                <div class="col-md-12">
                    <h2 class="mb-2">Описание на документа</h2>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-thumbtack main-color me-1"></i>Категория
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#"><span class="pris-tag">{{ $item->actType->name }}</span></a>
                    </div>
                </div>
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-hashtag main-color me-1"></i>Номер
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <span>
                            {{ $item->actType->doc_num }}
                        </span>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-brands fa-keycdn main-color me-1"></i>Уникален номер
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">#{{ $item->actType->doc_num }}/{{ displayDate($item->doc_date) }}</a>

                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-calendar-check main-color me-1"></i>Дата на издаване
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ displayDate($item->doc_date) }}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-calendar-days main-color me-1"></i>Дата на публикуване
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ displayDate($item->published_at) }}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class=" fa-solid fa-arrow-up-right-from-square main-color me-1"></i>Заглавие/Относно
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {!! $item->about !!}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-university main-color me-1"></i>Институция
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">{{ $item->institution->name }} </a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-right-to-bracket main-color me-1"></i>Вносител
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">{{ $item->importer }} </a>
                    </div>
                </div>


                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-file-lines main-color me-1"></i>Протокол
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ $item->protocol }}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-newspaper main-color me-1"></i>ДВ брой
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none"> {{ $item->newspaper_number }}</a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-gavel main-color me-1"></i>Правно основание
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {!! $item->legal_reason !!}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-tags main-color me-1"></i>Термини
                    </div>
                    <div class="col-md-9 pris-left-column">
                        @if($item->tags->count())
                            @foreach($item->tags as $tag)
                                <a href="#"><span class="pris-tag">{{ $tag->label }}</span></a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-arrow-right-arrow-left main-color me-1"></i>Свързани документи
                    </div>

                    <div class="col-md-9 pris-left-column">
                        @if($item->changedDocs->count())
                            @foreach($item->changedDocs as $doc)
                                <a href="{{ route('pris.view', ['id' => $doc->id]) }}" target="_blank"
                                   class="text-decoration-none main-color d-block">
                                    {{ $doc->actType->name.' '.__('custom.number_symbol').' '.$doc->actType->doc_num.' '.__('custom.of').' '.$doc->institution->name.' от '.$doc->docYear.' '.__('site.year_short') }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-list-ol main-color me-1"></i>Версия
                    </div>
                    <div class="col-md-9 pris-left-column">
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">12.01.2023г. от потребител Георги Иванов</a>
                        </p>
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">15.02.2023г. от потребител Георги Иванов</a>
                        </p>
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">17.03.2023г. от потребител Георги Иванов</a>
                        </p>

                    </div>
                </div>


                <div class="row mb-0 mt-5">
                    <div class="mb-2">
                        <h2 class="mb-1">Прикачени файлове</h2>
                    </div>

                </div>
                <div class="row p-3">
                    <div class="custom-card py-4 px-3 mb-5">
                        <div class="col-md-12">
                            <h4 class="mb-2">Информация</h4>
                            <p>
                                С проекта на наредба се предлагат:

                                I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива
                                (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към
                                Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към
                                научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407).

                                Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в
                                националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни
                                товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема
                                делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията
                                на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително
                                използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания
                                текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в
                                Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да
                                се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи:
                                „в приложение II раздел II.1 се заменя със следното:

                                „II.1. RID
                            </p>

                            <p>
                                <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е
                                    уместно „RID договаряща държава" се заменя с „държава членка“.</strong>
                            </p>

                            <p>
                                Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се
                                транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в
                                края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в
                                параграф единствен от проекта на наредба.

                                Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или
                                косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за
                                прилагането на новата уредба.

                                На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с
                                доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на
                                транспорта, информационните технологии и съобщенията и на Портала за обществени консултации
                                на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с
                                проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени
                                предложения или становища в 14-дневен срок от публикуването им.
                            </p>


                            <p>
                                <strong>
                                    Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е
                                    взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за
                                    нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект
                                    на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда
                                    изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който
                                    регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г.,
                                    която вече е в сила за Република България.
                                </strong>
                            </p>

                            <p>
                                В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския
                                съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007
                                г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010
                                г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм.,
                                бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и
                                доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр.
                                36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр.
                                39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за
                                съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в
                                рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на
                                работната група.
                            </p>


                            <div class="row">
                                <div class="col-md-6">
                                    <a href="#" class="btn btn-primary">Изтегляне</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-end">
                                        <span class="text-end">
                                            <strong>Дата на създаване:</strong> 15.05.2023г.
                                        </span><Br>
                                        <span class="text-end">
                                            <strong>Дата на публикуване:</strong> 20.05.2023г.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
