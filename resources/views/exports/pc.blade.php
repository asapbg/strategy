@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $item->title }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 22px;
            }
            table, th, td {
                border: 2px solid black;
                border-collapse: collapse;
                padding: 10px 15px;
            }
            .spechtag {
                background-color: #bce4ec;
                text-align: center;
                border-radius: 5px;
                padding:10px 20px;
            }
            .indentp {
                padding-left: 20px;
            }
        </style>
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div style="font-size: 22px;">
@endif
            <h3 style="text-align: center">{{ $item->title }}</h3>

            <h4 class="spechtag">{{ __('custom.info') }}</h4>
            <p>
                <strong>{{ __('site.public_consultation.form_to') }}:
                    @if($item->daysCnt <= \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS)
                        <i class="fa-solid fa-triangle-exclamation text-danger fs-5" title="{{ __('site.public_consultation.short_term_warning') }}"></i>
                    @endif
                </strong>
                <span>{{ displayDate($item->open_from) }} {{ __('site.year_short') }} - {{ displayDate($item->open_to) }} {{ __('site.year_short') }}</span>
                <span class="{{ $item->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $item->inPeriod }}</span>
            </p>
            <p>
                <strong>{{ __('site.public_consultation.reg_num') }}: </strong>
                <span>#{{ $item->reg_num }}</span>
            </p>
            <p>
                <strong>{{ trans_choice('custom.field_of_actions', 1) }}: </strong>
                <span>
                      @if($item->fieldOfAction) {{ $item->fieldOfAction->name }} @else{{ '---' }}@endif
                  </span>
            </p>
            <p>
                <strong>{{ __('site.public_consultation.type_consultation') }}: </strong>
                <span>
                      @if($item->actType){{ $item->actType->name }}@else{{ '---' }}@endif
                  </span>
            </p>
            @if($item->importer_institution_id != config('app.default_institution_id'))
                <p>
                    <strong>{{ __('site.public_consultation.importer') }}: </strong>
                    <span>
                        {{ $item->importerInstitution->name }} @if(!empty($item->importer)){{ '('.$item->importer.')' }}@endif
                  </span>
                </p>
            @endif
            @if($item->consultation_level_id)
                <p>
                    <strong>{{ __('site.public_consultation.importer_type') }}: </strong>
                    <span>
                        {{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}
                  </span>
                </p>
            @endif
            <hr>
            <div>
                {!! $item->description !!}
            </div>

            @if($item->responsibleInstitution && $item->responsibleInstitution->id != config('app.default_institution_id'))
                <h4 class="spechtag">{{ __('site.public_consultation.responsible_institution') }}</h4>
                <div class="row mb-4 mt-4">
                    <h3 class="mb-3">{{ __('site.public_consultation.responsible_institution') }}</h3>
                    <p>
                        <strong>{{ $item->responsibleInstitution->name }} </strong>
                        <br> {{ __('custom.address') }}: {{ ($item->responsibleInstitution->settlement ? $item->responsibleInstitution->settlement->ime.', ' : '').$item->responsibleInstitution->address }}
                        <br> {{ __('custom.email') }}: @if($item->responsibleInstitution->email){{ $item->responsibleInstitution->email }}@else ---@endif
                    </p>
                </div>
            @endif

            @if($item->contactPersons->count())
                <h4 class="spechtag">{{ __('site.public_consultation.contact_persons') }}</h4>
                <p>
                    @foreach($item->contactPersons as $person)
                        {{ $person->name }} @if($person->email) | {{ __('custom.email') }}: {{ $person->email }} @endif<br>
                    @endforeach
                </p>
            @endif

            @if($item->daysCnt <= \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS && !empty($item->short_term_reason))
                <h4 class="spechtag">{{ __('site.public_consultation.short_term_motive_label') }}</h4>
                <p> {{ $item->short_term_reason }} </p>
            @endif

            @if(!empty($item->proposal_ways))
                <h4 class="spechtag">{{ __('custom.proposal_ways') }}</h4>
                <div>
                    {!! $item->proposal_ways !!}
                </div>
            @endif

            @if($item->importerInstitution && $item->importerInstitution->links->count())
                <h4 class="spechtag">{{ trans_choice('custom.useful_links', 2)  }}</h4>
                @foreach($item->importerInstitution->links as $l)
                    <p>{{ $l->title }} - {{ $l->link }}</p>
                @endforeach
            @endif

            <div>
                <h4 class="spechtag">{{ trans_choice('custom.documents', 2) }}</h4>
                <p><strong>{{ __('site.public_consultation.base_documents') }}:</strong></p>
                <div>
                    @php($foundBaseDoc = false)
                    @if(isset($documents) && sizeof($documents))
                        @foreach($documents as $doc)
                            @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'base')))
                                <p>
                                    <a href="{{ route('download.file', $doc->id) }}">
                                        {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                    </a>
                                </p>
                                @php($foundBaseDoc = true)
                            @endif
                            @if($item->documentsAttByLocale->count())
                                @php($foundBaseDocSub = 0)
                                @foreach($item->documentsAtt as $att)
                                    @if($att->doc_type == $doc->doc_type.'00' && $att->locale == app()->getLocale())
                                        @php($foundBaseDoc = true)
                                            <p class="indentp">
                                                <a href="{{ route('download.file', $att->id) }}">
                                                    {{ $att->description }} - {{ __('custom.version_short').' '.$att->version }} | {{ displayDate($att->created_at) }}
                                                </a>
                                            </p>
                                        @php($foundBaseDocSub = 1)
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    @if($documentsImport->count())
                        @foreach($documentsImport as $doc)
                            <p>
                                <a href="{{ route('download.file', $doc->id) }}">
                                    {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                </a>
                            </p>
                        @endforeach
                        @php($foundBaseDoc)
                    @endif
                    @if(!$foundBaseDoc)
                        ---
                    @endif
                </div>

                <p><strong>{{ __('site.public_consultation.kd_documents') }}:</strong></p>
                <div>
                    @php($foundKdDoc = false)
                    @if(isset($documents) && sizeof($documents))
                        @foreach($documents as $doc)
                            @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'kd')))
                                <p>
                                    <a href="{{ route('download.file', $doc->id) }}">
                                        {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                    </a>
                                </p>
                                @php($foundKdDoc = true)
                                @if($item->documentsAttByLocale->count())
                                    @php($foundKdDocSub = 0)
                                    @foreach($item->documentsAtt as $att)
                                        @if($att->doc_type == $doc->doc_type.'00')
                                            @php($foundKdDoc = true)
                                                <p class="indentp">
                                                    <a href="{{ route('download.file', $att->id) }}">
                                                        {{ $att->description }} - {{ __('custom.version_short').' '.$att->version }} | {{ displayDate($att->created_at) }}
                                                    </a>
                                                </p>
                                            @php($foundKdDocSub = 1)
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                    @if(!$foundKdDoc)
                        ---
                    @endif
                </div>

                <p><strong>{{ __('site.public_consultation.report_documents') }}:</strong></p>
                <div>
                    @php($foundReportDoc = false)
                    @if(isset($documents) && sizeof($documents))
                        @foreach($documents as $doc)
                            @if(in_array($doc->doc_type, \App\Enums\DocTypesEnum::docByActTypeInSections($item->act_type_id, 'report')))
                                <p>
                                    <a href="{{ route('download.file', $doc->id) }}">
                                        {{ $doc->description }} - {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }}
                                    </a>
                                </p>
                                @php($foundReportDoc = true)

                                @if($item->documentsAttByLocale->count())
                                    @php($foundReportDocSub = 0)
                                    @foreach($item->documentsAtt as $att)
                                        @if($att->doc_type == $doc->doc_type.'00')
                                            @php($foundReportDoc = true)
                                            <p class="indentp">
                                                <a href="{{ route('download.file', $att->id) }}">
                                                    {{ $att->description }} - {{ __('custom.version_short').' '.$att->version }} | {{ displayDate($att->created_at) }}
                                                </a>
                                            </p>
                                            @php($foundReportDocSub = 1)
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                    @if(!$foundReportDoc)
                        ---
                    @endif
                </div>
            </div>

            @if($item->pollsFinished->count())
                <h4 class="spechtag">{{ __('site.public_consultation.polls') }}</h4>
                @foreach($item->pollsFinished as $poll)
                    <div>
                        <p><strong>{{ trans_choice('custom.polls', 1) }}: {{ $poll->name }}</strong></p>
                    </div>
                    @php($statistic = $poll->getStats())
                    @if($poll->questions->count())
                        @foreach($poll->questions as $key => $q)
                            <div>
                                <p><strong>{{ __('custom.question_with_number', ['number' => ($key+1)]) }}</strong> {{ $q->name }} ({{ trans_choice('custom.users', 2) }}: {{ isset($statistic[$q->id]) ? $statistic[$q->id]['users'] : 0 }})</p>
{{--                                <p>{{ trans_choice('custom.users', 2) }}: {{ isset($statistic[$q->id]) ? $statistic[$q->id]['users'] : 0 }} </p>--}}
                                @foreach($q->answers as $key => $a)
                                    @php($percents = 0)
                                    @if(sizeof($statistic) && isset($statistic[$q->id]) && isset($statistic[$q->id]['options'][$a->id]))
                                        @php($percents = ($statistic[$q->id]['options'][$a->id] * 100) / $statistic[$q->id]['users'])
                                    @endif
                                    <div>
                                        - {{ $a->name }} ({{ $percents }}%)
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif

            <h4 class="spechtag">{{ trans_choice('custom.comments', 2) }}</h4>
            @php($fPdf = $item->commentsDocumentPdf())
            @if($fPdf)
                <p>
                    <a href="{{ route('download.file', $fPdf) }}">
                        {{ $fPdf->{'description_'.$fPdf->locale} }}
                    </a>
                </p>
            @endif
            @php($fCsv = $item->commentsDocumentCsv())
            @if($fCsv)
                <p>
                    <a href="{{ route('download.file', $fCsv) }}">
                        {{ $fCsv->{'description_'.$fCsv->locale} }}
                    </a>
                </p>
            @endif
            @if($item->comments->count())
                @foreach($item->comments as $c)
                    <div>
                        <p>
                            <strong>{{ __('custom.author') }}: </strong>
                            <span>
                                {{ $c->author ? $c->author->fullName() : __('custom.anonymous') }} ({{ displayDateTime($c->created_at) }})
                          </span>
                        </p>
                        <div>
                            {!! $c->content !!}
                        </div>
                    </div>
                @endforeach
            @endif

            <h4 class="spechtag">{{ __('site.history') }}</h4>
            @if(isset($timeline) && sizeof($timeline))
                <div>
                    @foreach($timeline as $t)
                        <p><strong>{{ $t['label'] }} {{ isset($t['date']) && !empty($t['date']) ? ' - '. $t['date'] : '' }}</strong></p>
                        @if(isset($t['description']) && !empty($t['description']))
                            <div>{!! $t['description'] !!}</div>
                        @endif
                    @endforeach
                </div>
            @endif

@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
