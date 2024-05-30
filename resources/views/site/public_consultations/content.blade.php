<div class="col-lg-10 py-5 right-side-content">
    <div>
        <div class="row mb-4 action-btn-wrapper">
            <div class="col-md-12">
                <h2 class="mb-3">{{ __('custom.information') }}</h2>
            </div>
            <div class="col-md-12 text-start">
                <button class="btn btn-primary  main-color">
                    <i class="fa-solid fa-download main-color me-2"></i>{{ __('custom.export') }}</button>
                <input type="hidden" id="subscribe_model" value="App\Models\Consultations\PublicConsultation">
                <input type="hidden" id="subscribe_model_id" value="{{ $item->id }}">
                @includeIf('site.partial.subscribe-buttons', ['no_rss' => true])
            </div>
        </div>


        <div class="row">
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.form_to') }}
                    @if($item->daysCnt <= \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS)
                        <a href="#short-term" class="text-decoration-none">
                            <i class="fa-solid fa-triangle-exclamation text-danger fs-5" title="{{ __('site.public_consultation.short_term_warning') }}"></i>
                        </a>
                    @endif
                </h3>
                <a href="#short-term" class="text-decoration-none"></a>
                <span class="obj-icon-info fw-bold" style="font-size: 18px;">
                  <i class="far fa-calendar me-2 main-color" title="{{ __('site.public_consultation.open_from') }}"></i>{{ displayDate($item->open_from) }} {{ __('site.year_short') }} </span>
                <span class="mx-2"> — </span>
                <span class="obj-icon-info fw-bold me-2" style="font-size: 18px;">
                  <i class="far fa-calendar-check me-2 main-color" title="{{ __('site.public_consultation.open_to') }}"></i>{{ displayDate($item->open_to) }} {{ __('site.year_short') }} </span>
                <span class="{{ $item->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $item->inPeriod }}</span>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.reg_num') }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info">
                    <i class="fas fa-hashtag me-2 main-color" title="Номер на консултация "></i>{{ $item->reg_num }}</span>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-18">{{ trans_choice('custom.field_of_actions', 1) }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info">
                    <i class="{{ $item->fieldOfAction ? $item->fieldOfAction->icon_class : 'fas fa-certificate' }} me-2 main-color" title="{{ trans_choice('custom.field_of_actions', 1) }}"></i>
                      @if($item->fieldOfAction)<a href="{{ route('public_consultation.index').'?fieldOfAction='.$item->fieldOfAction->id }}" target="_blank">{{ $item->fieldOfAction->name }}</a>@else{{ '---' }}@endif
                  </span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <h3 class="mb-2 fs-18">{{ __('site.public_consultation.type_consultation') }}</h3>
                <a href="#" class="main-color text-decoration-none">
                  <span class="obj-icon-info">
                    <i class="fas fa-file-lines me-2 main-color" title="{{ __('site.public_consultation.type_consultation') }}"></i>
                      @if($item->actType)<a class="act-type act-type-{{ $item->act_type_id }}" href="{{ route('public_consultation.index').'?actType='.$item->act_type_id }}" target="_blank">{{ $item->actType->name }}</a>@else{{ '---' }}@endif
                  </span>
                </a>
            </div>
            @if($item->importer_institution_id != config('app.default_institution_id'))
                <div class="col-md-4 mb-4">
                    <h3 class="mb-2 fs-18">{{ __('site.public_consultation.importer') }}</h3>
                    <a class="main-color text-decoration-none" href="{{ route('institution.profile', $item->importer_institution_id) }}" target="_blank">
                      <span class="obj-icon-info">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2 main-color" title="{{ __('site.public_consultation.importer') }}"></i>
                          {{ $item->importerInstitution->name }} @if(!empty($item->importer)){{ '('.$item->importer.')' }}@endif
                      </span>
                    </a>
                </div>
            @endif
            @if($item->consultation_level_id)
                <div class="col-md-4 mb-4">
                    <h3 class="mb-2 fs-18">{{ __('site.public_consultation.importer_type') }}</h3>
    {{--                <a href="#" class="main-color text-decoration-none">--}}
    {{--                  <span class="obj-icon-info me-2">--}}
    {{--                    <i class="fa-solid fa-arrow-right-from-bracket me-2 main-color" title="{{ __('site.public_consultation.importer') }}"></i>--}}
    {{--                      <a class="level-{{ strtolower(\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}" target="_blank" href="{{ route('public_consultation.index').'?level='.$item->consultation_level_id }}">{{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}</a>--}}
    {{--                  </span>--}}
    {{--                </a>--}}
                    <a class="institution-level level-{{ strtolower(\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}" target="_blank" href="{{ route('public_consultation.index').'?level[]='.$item->consultation_level_id }}">{{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}</a>
                </div>
            @endif
{{--            <div class="col-md-4 ">--}}
{{--                <h3 class="mb-2 fs-18">Предишна версия</h3>--}}
{{--                <a href="#" class="main-color text-decoration-none">--}}
{{--                  <span class="obj-icon-info me-2">--}}
{{--                    <i class="fa-solid fa-code-compare me-2 main-color" title="История"></i>Версия 1.1 </span>--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>


        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                {!! $item->description !!}
            </div>
        </div>

        @if($item->responsibleInstitution && $item->responsibleInstitution->id != config('app.default_institution_id'))
            <div class="row mb-4 mt-4">
                <h3 class="mb-3">{{ __('site.public_consultation.responsible_institution') }}</h3>
                <p> <strong>{{ $item->responsibleInstitution->name }} </strong>
                    <br> {{ __('custom.address') }}: {{ ($item->responsibleInstitution->settlement ? $item->responsibleInstitution->settlement->ime.', ' : '').$item->responsibleInstitution->address }}
                    <br> {{ __('custom.email') }}: @if($item->responsibleInstitution->email) <a href="mailto:{{ $item->responsibleInstitution->email }}" class="main-color">{{ $item->responsibleInstitution->email }}</a>@else ---@endif
                </p>
            </div>
        @endif
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

        <div class="row mb-4 mt-4" id="short-term">
            <h3 class="mb-3">{{ __('custom.proposal_ways') }}</h3>
            <div>
                {!! $item->proposal_ways !!}
            </div>
        </div>

        @if($item->importerInstitution && $item->importerInstitution->links->count())
        <div class="row mb-4 mt-4">
            <h3 class="mb-3">{{ trans_choice('custom.useful_links', 2)  }}</h3>
            <div class="col-md-12">
                <ul class="list-group list-group-flush">
                    @foreach($item->importerInstitution->links as $l)
                        <li class="list-group-item">
                             <a href="{{ $l->link }}" target="_blank" class="main-color text-decoration-none"><i class="fas fa-regular fa-link  main-color me-1 fs-6"></i> {{ $l->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="row mb-4 mt-4">
            <h3 class="mb-3">{{ trans_choice('custom.documents', 2) }}</h3>
            <div class="row table-light">
                @if(!$item->old_id)
                    <div class="col-12 mb-2">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.base_documents') }}</p>
                        <ul class="list-group list-group-flush">
                            @php($foundBaseDoc = false)
                            @if(isset($documents) && sizeof($documents))
                                @foreach($documents as $doc)
                                    @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'base')))
                                        <li class="list-group-item">
                                            <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('modal.file_preview', ['id' => $doc->id]) }}">
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

                    <div class="col-12 mb-2">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.kd_documents') }}</p>
                        <ul class="list-group list-group-flush">
                            @php($foundKdDoc = false)
                            @if(isset($documents) && sizeof($documents))
                                @foreach($documents as $doc)
                                    @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'kd')))
                                        <li class="list-group-item">
                                            <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('modal.file_preview', ['id' => $doc->id]) }}">
                                                {!! fileIcon($doc->content_type) !!} {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                            </a>
                                        </li>
                                        @php($foundKdDoc = true)
                                    @endif
                                @endforeach
                            @endif
                            @if(!$foundKdDoc)
                                <p>---</p>
                            @endif
                        </ul>
                    </div>

                    <div class="col-12">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.report_documents') }}</p>
                        <ul class="list-group list-group-flush">
                            @php($foundReportDoc = false)
                            @if(isset($documents) && sizeof($documents))
                                @foreach($documents as $doc)
                                    @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'report')))
                                        <li class="list-group-item">
                                            <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $doc->id }}" data-url="{{ route('modal.file_preview', ['id' => $doc->id]) }}">
                                                {!! fileIcon($doc->content_type) !!} {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                            </a>
                                        </li>
                                        @php($foundReportDoc = true)
                                    @endif
                                @endforeach
                            @endif
                            @if(!$foundReportDoc)
                                <p>---</p>
                            @endif
                        </ul>
                    </div>
                @endif
                @if($documentsImport->count())
                    <div class="col-12">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">{{ __('site.public_consultation.base_documents') }}</p>
                        <ul class="list-group list-group-flush">
                            @foreach($documentsImport as $doc)
                                <li class="list-group-item">
                                    @include('site.partial.file_preview_or_download', ['f' => $doc])
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        @include('site.public_consultations.polls')
        @include('site.public_consultations.comments')

    </div>
</div>
