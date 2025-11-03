@extends('layouts.site', ['fullwidth'=>true])

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end  mt-2">
                    @can('update', $item)
                        <a class="btn btn-primary main-color main-color" target="_blank" href="{{ route('admin.pris.edit', ['item' => $item->id]) }}">
                            <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}</a>
                    @endcan
                    <input type="hidden" id="subscribe_model" value="App\Models\Pris">
                    <input type="hidden" id="subscribe_model_id" value="{{ $item->id }}">
                        @includeIf('site.partial.subscribe-buttons', ['no_rss' => true, 'no_rss' => $item->in_archive, 'no_email_subscribe' => $item->in_archive ])
                </div>
            </div>
        </div>
    </section>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                @include('site.pris.side_menu')

            <div class="col-lg-10  right-side-content py-5">
                @if(isset($pageTopContent) && !empty($pageTopContent->value))
                    <div class="col-12 mb-2">
                        {!! $pageTopContent->value !!}
                    </div>
                @endif
                <div class="col-md-12 mb-5">
                    <h2 class="mb-3">{{ __('custom.pris_about') }}</h2>
                    {!! $item->about !!}
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-thumbtack main-color me-1"></i>{{ __('validation.attributes.category') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="{{ route($item->in_archive ? 'pris.archive' : 'pris.index').'?legalActTypes[]='.$item->actType->id }}" target="_blank"><span class="pris-tag">{{ $item->actType->name }}</span></a>
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

{{--                @if($item->institutions->count() && ($item->institutions->count() > 1 || $item->institutions->first()->id != config('app.default_institution_id')) )--}}
{{--                    <div class="row pris-row pb-2 mb-2">--}}
{{--                        <div class="col-md-3 pris-left-column">--}}
{{--                            <i class="fa-solid fa-university main-color me-1"></i>{{ trans_choice('custom.institutions', 1) }}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-9 pris-left-column">--}}
{{--                            @foreach($item->institutions as $i)--}}
{{--                                @if($i->id != config('app.default_institution_id'))--}}
{{--                                    <a href="{{ route('admin.strategic_documents.institutions.edit', $i) }}" class="text-decoration-none d-block" target="_blank" title="{{ $i->name }}">{{ $i->name }} </a>--}}
{{--                                    <a href="{{ route('institution.profile', $i) }}" class="text-decoration-none d-block" target="_blank"--}}
{{--                                       title="{{ $i->getHistorycalName(databaseDate($item->doc_date)) }}"--}}
{{--                                    >--}}
{{--                                        {{ $i->getHistorycalName(databaseDate($item->doc_date)) }}--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}

                @if(!in_array($item->actType->id, [App\Models\LegalActType::TYPE_PROTOCOL,App\Models\LegalActType::TYPE_TRANSCRIPTS]))
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-right-to-bracket main-color me-1"></i>{{ __('site.public_consultation.importer') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {{ !empty($item->old_importers) ? $item->old_importers : $item->importer }}
{{--                            <a href="{{ route('pris.index').'?importer='.$item->importer }}" class="text-decoration-none">{{ $item->importer }} </a>--}}
                    </div>
                </div>
                @endif

                @php
                    $locale = app()->getLocale();
                    $decisionProtocol = $item->decision_protocol && $item->decisionProtocol ? $item->decisionProtocol : null
                @endphp
                @if($item->actType->id != App\Models\LegalActType::TYPE_PROTOCOL)
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-file-lines main-color me-1"></i>{{ __('validation.attributes.protocol') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        @if($decisionProtocol)
                            <a href="{{ $item->in_archive ? route('pris.archive.view', ['category' => \Illuminate\Support\Str::slug($decisionProtocol->actType->name_single), 'id' => $decisionProtocol->id]) : route('pris.view', ['category' => $decisionProtocol->actType->name_single, 'id' => $decisionProtocol->id]) }}" target="_blank">@if(!empty($item->protocol_point)){{ __('site.point_short').' '.$item->protocol_point }} {{ __('custom.from') }} @endif{{ $decisionProtocol->mcDisplayName }}</a>
                        @else
                            {{ $item->protocol }}
                        @endif
                    </div>
                </div>
                @endif

                @if($item->newspaper)
                    <div class="row pris-row pb-2 mb-2">
                        <div class="col-md-3 pris-left-column">
                            <i class="fa-solid fa-newspaper main-color me-1"></i>{{ __('validation.attributes.newspaper') }}
                        </div>
                        <div class="col-md-9 pris-left-column">
                            @if($item->newspaper_number || $item->newspaper_year)
                                <a href="{{ route($item->in_archive ? 'pris.archive' : 'pris.index').'?newspaperNumber='.$item->newspaper_number.'&newspaperYear='.$item->newspaper_year }}" title="{{ __('validation.attributes.newspaper') }} - {{ $item->newspaper }}" target="_blank" class="text-decoration-none"> {{ $item->newspaper }}</a>
                            @else
                                {{ $item->newspaper }}
                            @endif
                        </div>
                    </div>
                @endif

                @if(!in_array($item->actType->id, [App\Models\LegalActType::TYPE_PROTOCOL,App\Models\LegalActType::TYPE_TRANSCRIPTS]))
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-gavel main-color me-1"></i>{{ __('custom.pris_legal_reason') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        {!! $item->legal_reason !!}
                    </div>
                </div>
                @endif

                @if(!in_array($item->actType->id, [App\Models\LegalActType::TYPE_PROTOCOL,App\Models\LegalActType::TYPE_TRANSCRIPTS]))
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-tags main-color me-1"></i>{{ trans_choice('custom.tags', 2) }}
                    </div>
                    <div class="col-md-9 pris-left-column">
                        @if($item->tags->count())
                            @foreach($item->tags as $tag)
                                <a href="{{ route($item->in_archive ? 'pris.archive' : 'pris.index').'?tag='.$tag->id }}" title="{{ trans_choice('custom.public_consultations', 2) }} - {{ $tag->label }}" target="_blank"><span class="pris-tag">{{ $tag->label }}</span></a>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                @if(!in_array($item->actType->id, [App\Models\LegalActType::TYPE_PROTOCOL,App\Models\LegalActType::TYPE_TRANSCRIPTS]))
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-arrow-right-arrow-left main-color me-1"></i>{{ __('custom.change_docs') }}
                    </div>

                    <div class="col-md-9 pris-left-column">
                        @if($item->changedDocs->count() || $item->changedByDocs->count() || $item->changedDocsWithoutRelation->count())
                            @foreach($item->changedDocs as $doc)
                                @if ($doc->legal_act_type_id == \App\Models\LegalActType::TYPE_ORDER)
                                    <div id="disconnect_text_{{ $item->id }}">
                                        {{ $doc->pivot->old_connect_type ?? $doc->pivot->connect_type ? __('custom.pris.change_enum.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($doc->pivot->connect_type)) : ''  }}
                                        {{ $doc->mcDisplayName }} {{ $doc->pivot->connect_text ? "({$doc->pivot->connect_text})" : "" }}
                                    </div>
                                @else
                                    <a href="{{ $item->in_archive ? route('pris.archive.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name), 'id' => $doc->id]) : route('pris.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name), 'id' => $doc->id]) }}" target="_blank"
                                       class="text-decoration-none main-color d-block"
                                    >
                                        {{ $doc->pivot->old_connect_type ?? $doc->pivot->connect_type ? __('custom.pris.change_enum.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($doc->pivot->connect_type)) : ''  }}
                                        {{ $doc->mcDisplayName }} {{ $doc->pivot->connect_text ? "({$doc->pivot->connect_text})" : "" }}
                                    </a>
                                @endif
                            @endforeach
                            @foreach($item->changedByDocs as $doc)
                                @if ($doc->legal_act_type_id == \App\Models\LegalActType::TYPE_ORDER)
                                    <div id="disconnect_text_{{ $item->id }}">
                                        {{ $doc->pivot->old_connect_type ?? $doc->pivot->connect_type ? __('custom.pris.change_enum.reverse.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($doc->pivot->connect_type)) : ''  }}
                                        {{ $doc->mcDisplayName }} {{ $doc->pivot->connect_text ? "({$doc->pivot->connect_text})" : "" }}
                                    </div>
                                @else
                                    <a href="{{ $doc->in_archive ? route('pris.archive.view', ['category' => \Illuminate\Support\Str::slug($doc->actType->name), 'id' => $doc->id]) : route('pris.view', ['category' => \Illuminate\Support\Str::slug($item->actType->name), 'id' => $doc->id]) }}" target="_blank"
                                       class="text-decoration-none main-color d-block"
                                    >
                                        {{ $doc->pivot->old_connect_type ?? $doc->pivot->connect_type ? __('custom.pris.change_enum.reverse.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($doc->pivot->connect_type)) : ''  }}
                                        {{ $doc->mcDisplayName }} {{ $doc->pivot->connect_text ? "({$doc->pivot->connect_text})" : "" }}
                                    </a>
                                    @endif
                            @endforeach
                            @foreach($item->changedDocsWithoutRelation as $pris)
                                <div id="disconnect_text_{{ $item->id }}">
                                    {{ $pris->full_text }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                @if($item->files->count())
                    <div class="row mb-0 mt-5">
                        <div class="mb-2">
                            <h2 class="mb-1">{{ __('custom.files') }}</h2>
                        </div>
                    </div>
                    @foreach($item->files as $f)
                        @if($f->locale == $locale)
                            <div class="row p-1">
                                <div class="accordion" id="accordionExample">

                                    <div class="card custom-card">
                                        <div class="card-header" id="heading{{ $f->id }}">
                                            <h2 class="mb-0">
                                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start @if(!$loop->first) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $f->id }}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $f->id }}">
                                                  <i class="me-1 bi bi-file-earmark-text fs-18"></i>  {{ $f->{'description_'.$locale} }}
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse{{ $f->id }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $f->id }}" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <div class="text-start">
                                                            <span class="text-start me-3">
                                                                <strong>{{ __('custom.date_created') }}:</strong> {{ displayDate($f->created_at) }} {{ __('custom.year_short') }}.
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <a href="{{ route('download.file', $f) }}" class="btn btn-primary">{{ __('custom.download') }}</a>
                                                    </div>
                                                </div>
                                                {!! fileHtmlContent($f) !!}
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="text-start">
                                                            <span class="text-start me-3">
                                                                <strong>{{ __('custom.date_created') }}:</strong> {{ displayDate($f->created_at) }} {{ __('custom.year_short') }}.
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <a href="{{ route('download.file', $f) }}" class="btn btn-primary">{{ __('custom.download') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </section>
@endsection
