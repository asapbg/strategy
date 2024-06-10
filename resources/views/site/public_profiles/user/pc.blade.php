@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.public_profiles.user_menu')
        <div class="col-lg-10 right-side-content py-2">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ __('site.user.pc.title', ['name' => $item->fullName()]) }}
                </h2>
                @if($items->count())
                    @foreach($items as $consultation)
{{--                        Like Profile layout--}}
                        @php
                            $inPeriod = $consultation->inPeriodBoolean
                        @endphp
                        <div class="row @if(!$loop->last) mb-3 @endif">
                            <div class="col-sm-8">
                                <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" title="{{ $consultation->title }}"
                                   class="ps-3 border-start border-4  @if(!$inPeriod) border-warning @else border-success @endif">
                                    {{ $consultation->title }})
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <span class="{{ $consultation->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $consultation->inPeriod }}</span>
                            </div>
                            @if($consultation->comments->count())
                                <div class="col-12">
                                    <p class="mt-2 mb-1">{{ __('site.my_comments') }}</p>
                                    <hr class="custom-hr mb-2">
                                    @foreach($consultation->comments as $c)
                                        <div class="col-12">
                                            <span><i class="fas fa-clock main-color me-2"></i><strong>{{ displayDateTime($c->created_at) }}</strong></span>
                                            {!! $c->content !!}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
{{--                        Like public consultation list  layout--}}
{{--                        <div class="col-md-12 mb-4">--}}
{{--                            <div class="consul-wrapper">--}}
{{--                                <div class="single-consultation d-flex">--}}
{{--                                    --}}{{--                            <div class="consult-img-holder">--}}
{{--                                    --}}{{--                                <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">--}}
{{--                                    --}}{{--                            </div>--}}
{{--                                    <div class="consult-body">--}}

{{--                                        <div class="consul-item">--}}
{{--                                            <div class="consult-item-header d-flex justify-content-between">--}}
{{--                                                <div class="consult-item-header-link">--}}
{{--                                                    <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" class="text-decoration-none" title="{{ $consultation->title }}">--}}
{{--                                                        <h3 class="mb-2">{{ $consultation->title }}</h3>--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                                <div class="consult-item-header-edit">--}}
{{--                                                    @can('delete', $consultation)--}}
{{--                                                        <a href="javascript:;"--}}
{{--                                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"--}}
{{--                                                           data-target="#modal-delete-resource"--}}
{{--                                                           data-resource-id="{{ $consultation->id }}"--}}
{{--                                                           data-resource-name="{{ $consultation->title }}"--}}
{{--                                                           data-resource-delete-url="{{ route('admin.consultations.public_consultations.delete', $consultation) }}"--}}
{{--                                                           data-toggle="tooltip"--}}
{{--                                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>--}}
{{--                                                        </a>--}}
{{--                                                    @endcan--}}
{{--                                                    @can('update', $consultation)--}}
{{--                                                        <a href="{{ route('admin.consultations.public_consultations.edit', $consultation) }}" target="_blank">--}}
{{--                                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">--}}
{{--                                                                <span class="d-none">{{ __('custom.edit') }}</span>--}}
{{--                                                            </i>--}}
{{--                                                        </a>--}}
{{--                                                    @endcan--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="meta-consul mb-2">--}}
{{--                                <span class="text-secondary d-flex flex-row align-items-center lh-normal"> <i class="fas fa-hashtag text-secondary me-1" title="{{ __('custom.period') }}"></i>--}}
{{--                                    {{ $consultation->reg_num }}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="meta-consul mb-2">--}}
{{--                            <span class="text-secondary d-flex flex-row align-items-baseline lh-normal"><i class="far fa-calendar text-secondary me-1"--}}
{{--                                                                                                           title="{{ __('custom.period') }}"></i> {{ displayDate($consultation->open_from) }} ---}}
{{--                                {{ displayDate($consultation->open_to) }}</span>--}}
{{--                                            </div>--}}
{{--                                            @if($consultation->actType || $consultation->consultation_level_id)--}}
{{--                                                <div class="meta-consul justify-content-start">--}}
{{--                                                    @if($consultation->actType)--}}
{{--                                                        <span class="me-2 mb-2">--}}
{{--                                        <strong>{{ __('site.public_consultation.type_consultation') }}:</strong>--}}
{{--                                        <a class="act-type act-type-{{ $consultation->act_type_id }}" target="_blank" href="{{ route('public_consultation.index').'?actType='.$consultation->act_type_id }}">{{ $consultation->actType->name }}</a>--}}
{{--                                    </span>--}}
{{--                                                    @endif--}}
{{--                                                    @if($consultation->actType && $consultation->consultation_level_id)--}}
{{--                                                        <span class="item-separator mb-2">|</span>--}}
{{--                                                    @endif--}}
{{--                                                    @if($consultation->consultation_level_id)--}}
{{--                                                        <span class="ms-2 mb-2">--}}
{{--                                        <strong>{{ __('site.public_consultation.importer_type') }}:</strong>--}}
{{--                                        <a class="institution-level level-{{ strtolower(\App\Enums\InstitutionCategoryLevelEnum::keyByValue($consultation->consultation_level_id)) }}" target="_blank" href="{{ route('public_consultation.index').'?level='.$consultation->consultation_level_id }}">{{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($consultation->consultation_level_id)) }}</a>--}}
{{--                                    </span>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                            <div class="meta-consul">--}}
{{--                            <span>--}}
{{--                                <strong>{{ __('custom.status') }}:</strong>--}}
{{--                                <span class="{{ $consultation->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $consultation->inPeriod }}</span>--}}
{{--                            </span>--}}
{{--                                                <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" title="{{ $consultation->title }}" class="mt-2"><i--}}
{{--                                                        class="fas fa-arrow-right read-more text-end"></i><span class="d-none">{{ $consultation->title }}</span>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    @endforeach
                @else
                    <div class="col-lg-6 mb-4 ">
                        <p class="main-color">{{ __('messages.records_not_found') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.public_consultations', 1)])
@endsection
