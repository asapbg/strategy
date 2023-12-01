@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end  mt-2">
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-square-rss text-warning me-2"></i>{{ __('custom.rss_subscribe') }}</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-envelope me-2 main-color"></i>{{ __('custom.subscribe') }}</button>
                    @can('update', $item)
                        <a class="btn rss-sub main-color main-color" target="_blank" href="{{ route('admin.pris.edit', ['item' => $item->id]) }}">
                            <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </section>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                @include('site.pris.side_menu')

            <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">
                @if(isset($pageTopContent) && !empty($pageTopContent->value))
                    <div class="col-12 mb-5">
                        {!! $pageTopContent->value !!}
                    </div>
                @endif

                <div class="col-md-12">
                    <h2 class="mb-2">Описание на документа</h2>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-thumbtack main-color me-1"></i>{{ __('validation.attributes.category') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="{{ route('pris.index').'?legalActTypes[]='.$item->actType->id }}" target="_blank"><span class="pris-tag">{{ $item->actType->name }}</span></a>
                    </div>
                </div>
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-hashtag main-color me-1"></i>{{ __('validation.attributes.number') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <span>
                            {{ $item->regNum }}
                        </span>
                    </div>
                </div>

                @if($item->consultation)
                    <div class="row pris-row pb-2 mb-2">
                        <div class="col-md-3 pris-left-column">
                            <i class="fa-brands fa-keycdn main-color me-1"></i>{{ __('custom.consultation_number_') }}
                        </div>

                        <div class="col-md-9 pris-left-column">
                            <a href="{{ route('public_consultation.view', ['id' => $item->consultation->id ]) }}" title="{{ __('custom.consultation_number_').' '.$item->consultation->reg_num }}" class="text-decoration-none">{{ $item->consultation->reg_num }}</a>
                        </div>
                    </div>
                @endif

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-calendar-check main-color me-1"></i>{{ __('custom.date_issued') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ displayDate($item->doc_date) }}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-calendar-days main-color me-1"></i>{{ __('custom.date_published') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ displayDate($item->published_at) }}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class=" fa-solid fa-arrow-up-right-from-square main-color me-1"></i>{{ __('custom.pris_about') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {!! $item->about !!}
                    </div>
                </div>
                @if($item->institution)
                    <div class="row pris-row pb-2 mb-2">
                        <div class="col-md-3 pris-left-column">
                            <i class="fa-solid fa-university main-color me-1"></i>{{ trans_choice('custom.institutions', 1) }}
                        </div>

                        <div class="col-md-9 pris-left-column">
                            <a href="{{ route('admin.strategic_documents.institutions.edit', $item->institution) }}" class="text-decoration-none" target="_blank" title="{{ $item->institution->name }}">{{ $item->institution->name }} </a>
                        </div>
                    </div>
                @endif

                @if($item->importer)
                    <div class="row pris-row pb-2 mb-2">
                        <div class="col-md-3 pris-left-column">
                            <i class="fa-solid fa-right-to-bracket main-color me-1"></i>{{ __('site.public_consultation.importer') }}
                        </div>

                        <div class="col-md-9 pris-left-column">
                            <a href="{{ route('pris.index').'?importer='.$item->importer }}" class="text-decoration-none">{{ $item->importer }} </a>
                        </div>
                    </div>
                @endif

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-file-lines main-color me-1"></i>{{ __('validation.attributes.protocol') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="{{ route('pris.index').'?protocol='.$item->protocol }}" title="{{ trans_choice('custom.public_consultations', 2) }} - {{ $item->protocol }}" target="_blank">{{ $item->protocol }}</a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-newspaper main-color me-1"></i>{{ __('validation.attributes.newspaper') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="{{ route('pris.index').'?newspaperNumber='.$item->newspaper_number.'&newspaperYear='.$item->newspaper_year }}" title="{{ trans_choice('custom.public_consultations', 2) }} - {{ $item->newspaper }}" target="_blank" class="text-decoration-none"> {{ $item->newspaper }}</a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-gavel main-color me-1"></i>{{ __('custom.pris_legal_reason') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {!! $item->legal_reason !!}
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-tags main-color me-1"></i>{{ trans_choice('custom.tags', 2) }}
                    </div>
                    <div class="col-md-9 pris-left-column">
                        @if($item->tags->count())
                            @foreach($item->tags as $tag)
                                <a href="{{ route('pris.index').'?tag='.$tag->id }}" title="{{ trans_choice('custom.public_consultations', 2) }} - {{ $tag->label }}" target="_blank"><span class="pris-tag">{{ $tag->label }}</span></a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-arrow-right-arrow-left main-color me-1"></i>{{ __('custom.change_docs') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        @if($item->changedDocs->count())
                            @foreach($item->changedDocs as $doc)
                                <a href="{{ route('pris.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name), 'id' => $doc->id]) }}" target="_blank"
                                   class="text-decoration-none main-color d-block">
                                    {{ __('custom.pris.change_enum.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($doc->pivot->connect_type)) }} {{ $doc->displayName.' от '.$doc->docYear.' '.__('site.year_short') }}
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
                        <h2 class="mb-1">{{ __('custom.files') }}</h2>
                    </div>
                </div>
                <div class="row p-3">
                    @if($item->files->count())
                        @php($locale = app()->getLocale())
                        @foreach($item->files as $f)
                            @if($f->locale == $locale)
                                <div class="custom-card p-3 mb-5">
                                    <div class="col-md-12">
{{--                                        <h4 class="mb-2">{{ $f->{'description_'.$locale} }}</h4>--}}
{{--                                        {!! fileHtmlContent($f) !!}--}}
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-2">{{ $f->{'description_'.$locale} }}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-start">
                                                    <span class="text-start me-3">
                                                        <strong>{{ __('custom.date_created') }}:</strong> {{ displayDate($f->cretaed_at) }} {{ __('custom.year_short') }}.
                                                    </span>
                                                    <span class="text-end">
                                                        <strong>{{ __('custom.date_published') }}:</strong> 20.05.2023г.
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <a href="{{ route('download.file', $f) }}" class="btn btn-primary">{{ __('custom.download') }}</a>
{{--                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">--}}
{{--                                                    {!! fileIcon($f->content_type) !!} {{ !empty($f->{'description_'.$locale}) ? $f->{'description_'.$locale} : $f->filename }} | {{ displayDate($f->created_at) }}--}}
{{--                                                </a>--}}
                                                <button type="button"
                                                        class="btn btn-success text-success preview-file-modal mr-2"
                                                        data-file="{{ $f->id }}"
                                                        data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}">
                                                    {!! fileIcon($f->content_type) !!}
                                                    {{ __('custom.preview') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        ---
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
