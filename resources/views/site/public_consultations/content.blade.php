<div class="col-lg-10">
    <div class="mt-5">

        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="mb-3">Информация</h2>
            </div>
            <div class="col-md-12 text-старт">
                <button class="btn btn-primary  main-color">
                    <i class="fa-solid fa-download main-color me-2"></i>Експорт</button>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-square-rss text-warning me-2"></i>RSS Абониране</button>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.form_to') }}
                    @if($item->daysCnt <= \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS)
                        <a href="#short-term" class="text-decoration-none">
                            <i class="fa-solid fa-triangle-exclamation text-danger fs-5" title="{{ __('site.public_consultation.short_term_warning') }}"></i>
                        </a>
                    @endif
                </h3>
                <a href="#short-term" class="text-decoration-none"></a>
                <span class="obj-icon-info">
                  <i class="far fa-calendar me-2 main-color" title="{{ __('site.public_consultation.open_from') }}"></i>{{ displayDate($item->open_from) }} {{ __('site.year_short') }} </span>
                <span class="mx-2"> — </span>
                <span class="obj-icon-info">
                  <i class="far fa-calendar-check me-2 main-color" title="{{ __('site.public_consultation.open_to') }}"></i>{{ displayDate($item->open_to) }} {{ __('site.year_short') }} </span>
            </div>
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.reg_num') }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info me-2">
                    <i class="fas fa-hashtag me-2 main-color" title="Номер на консултация "></i>{{ $item->reg_num }}</span>
                </a>
            </div>
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">Сфера на действие</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info me-2">
                    <i class="fas fa-car me-2 main-color" title="Сфера на действие"></i>Транспорт </span>
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.type_consultation') }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info me-2">
                    <i class="fas fa-file-lines me-2 main-color" title="Тип консултация"></i>{{ $item->actType->name }} </span>
                </a>
            </div>
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.importer_type') }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info me-2">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2 main-color" title="{{ __('site.public_consultation.importer') }}"></i>{{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }} </span>
                </a>
            </div>
            <div class="col-md-4 ">
                <h3 class="mb-2 fs-18">Предишна версия</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info me-2">
                    <i class="fa-solid fa-code-compare me-2 main-color" title="История"></i>Версия 1.1 </span>
                </a>
            </div>
        </div>


        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                {!! $item->description !!}
            </div>
        </div>

        <div class="row mb-4 mt-4">
            <h3 class="mb-3">{{ __('site.public_consultation.contact_persons') }}</h3>
            @if($item->contactPersons->count())
                @foreach($item->contactPersons as $person)
                    <p> {{ $person->name }}
{{--                        <br> Главен секретар <br> ИА „Железопътна администрация" <br> Тел.: <a href="tel:359884 101 581" class="main-color">02/9409 378</a>, Мобилен: <a href="tel:359884 101 581" class="main-color">+359 884 101 581</a>--}}
                        @if($person->email)
                            <br> {{ __('custom.email') }}: <a href="mailto:{{ $person->email }}" class="main-color">{{ $person->email }}</a>
                        @endif
                        <br>
                    </p>
                @endforeach
            @else
                <p>---</p>
            @endif
        </div>

        @if($item->daysCnt <= \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS)
            <div class="row mb-4 mt-4" id="short-term">
                <h3 class="mb-3">{{ __('site.public_consultation.short_term_motive_label') }}</h3>
                <p> {{ $item->short_term_reason }} </p>
            </div>
        @endif


        <div class="row mb-4 mt-4">
            <h3 class="mb-3">Полезни линкове</h3>
            <div class="col-md-12">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none">
                            <i class="fas fa-regular fa-link  main-color me-2 fs-5"></i>
                        </a><a href="#" class="main-color text-decoration-none">Полезен линк 1</a>

                    </li>
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none">
                            <i class=" fas fa-regular fa-link main-color me-2 fs-5"></i>
                        </a><a href="#" class="main-color text-decoration-none">Полезен линк 2</a>

                    </li>
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none">
                            <i class="fas fa-regular fa-link main-color me-2 fs-5"></i>
                        </a><a href="#" class="main-color text-decoration-none">Полезен линк 3</a>

                    </li>
                </ul>
            </div>
        </div>

        <div class="row mb-4 mt-4">
            <h3 class="mb-3">{{ trans_choice('custom.documents', 2) }}</h3>
            <div class="row table-light">
                <div class="col-md-4">
                    <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.base_documents') }}</p>
                    <ul class="list-group list-group-flush">
                        @php($foundBaseDoc = false)
                        @if(isset($documents) && sizeof($documents))
                            @foreach($documents as $doc)
                                @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'base')))
                                    <li class="list-group-item">
                                        <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">
                                            {!! fileIcon($doc->content_type) !!} {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                        </a>
                                    </li>
                                    @php($foundBaseDoc = true)
                                @endif
                            @endforeach
                        @endif
                        @if(!$foundBaseDoc)
                            <p>---</p>
                        @endif
                    </ul>
                </div>

                <div class="col-md-4">
                    <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.kd_documents') }}</p>
                    <ul class="list-group list-group-flush">
                        @php($foundBaseDoc = false)
                        @if(isset($documents) && sizeof($documents))
                            @foreach($documents as $doc)
                                @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'kd')))
                                    <li class="list-group-item">
                                        <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">
                                            {!! fileIcon($doc->content_type) !!} {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                        </a>
                                    </li>
                                    @php($foundBaseDoc = true)
                                @endif
                            @endforeach
                        @endif
                        @if(!$foundBaseDoc)
                            <p>---</p>
                        @endif
                    </ul>
                </div>

                <div class="col-md-4">
                    <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.report_documents') }}</p>
                    <ul class="list-group list-group-flush">
                        @php($foundBaseDoc = false)
                        @if(isset($documents) && sizeof($documents))
                            @foreach($documents as $doc)
                                @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'report')))
                                    <li class="list-group-item">
                                        <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">
                                            {!! fileIcon($doc->content_type) !!} {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                        </a>
                                    </li>
                                    @php($foundBaseDoc = true)
                                @endif
                            @endforeach
                        @endif
                        @if(!$foundBaseDoc)
                            <p>---</p>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mb-0 mt-4">
            <div class="col-md-12">
                <div class="custom-card py-4 px-3">
                    <h3 class="mb-3">{{ __('site.public_consultation.polls') }}</h3>
                    <form class="row" action="">
                        @if($item->polls->count())
                            @foreach($item->polls as $pool)
                                <p class="text-primary"># {{ $pool->name }}</p>
                                @if($pool->questions->count())
                                    @foreach($pool->questions as $q)
                                        <div class="col-md-6 mb-4">
                                            <div class="comment-background p-2 rounded">
                                                <p class="fw-bold fs-18 mb-2">{{ $q->name }}</p>
                                                @if($q->answers->count())
                                                    @foreach($q->answers as $a)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                {{ $a->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif

{{--                        <div class="col-md-6 mb-4">--}}
{{--                            <div class="comment-background p-2 rounded">--}}
{{--                                <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>--}}

{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                        Отговор 1--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault2">--}}
{{--                                        Отговор 2--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 3--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 4--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="col-md-6 mb-4">--}}
{{--                            <div class="comment-background p-2 rounded">--}}
{{--                                <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>--}}

{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                        Отговор 1--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault2">--}}
{{--                                        Отговор 2--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 3--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 4--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="col-md-6 mb-4">--}}
{{--                            <div class="comment-background p-2 rounded">--}}
{{--                                <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>--}}

{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                        Отговор 1--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault2">--}}
{{--                                        Отговор 2--}}
{{--                                    </label>--}}
{{--                                </div>--}}


{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 3--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault3">--}}
{{--                                        Отговор 4--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}




                        <div class="col-md-12">
                            <button class="btn btn-primary">
                                Изпращане
                            </button>
                        </div>
                    </form>
                </div>
            </div>




        </div>


        <div class="row mb-3">
            <div class="col-md-12">
                <div class="custom-card py-4 px-3">
                    <h3 class="mb-3">Коментари</h3>
                    <div class="obj-comment comment-background p-2 rounded mb-3">
                        <div class="info">
                      <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                        <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Ivanov </span>
                            <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                        </div>
                        <div class="comment rounded py-2">
                            <p class="mb-0">Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група.</p>
                        </div>
                    </div>
                    <div class="obj-comment comment-background p-2 rounded mb-3">
                        <div class="info">
                      <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                        <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Иванов </span>
                            <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                        </div>
                        <div class="comment rounded py-2">
                            <p class="mb-0">Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби</p>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div>
                            <textarea class="form-control mb-3 rounded" id="exampleFormControlTextarea1" rows="2" placeholder="Въведете коментар"></textarea>
                            <button class=" cstm-btn btn btn-primary login m-0">Добавяне на коментар</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
