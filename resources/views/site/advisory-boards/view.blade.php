@extends('layouts.site', ['fullwidth' => true])

<style>
    #siteLogo, #ok, #ms, .nav-link, .nav-item, #register-link, #login-btn, #search-btn, #back-to-admin, #profile-toggle {
        transition: 0.4s;
    }
</style>

@section('pageTitle', 'Консултативни съвети - Вътрешна страница')

@section('content')
    <div class="row">
        <!-- Left side menu -->
        <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2">
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                               data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>Консултативни съвети
                            </a>
                            <hr class="custom-hr">
                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                    <li class="mb-2 "><a href="#" class="link-dark text-decoration-none">Контакти</a>
                                    </li>
                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Актуална
                                            информация
                                            и
                                            събития</a>
                                    </li>
                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <hr class="custom-hr">
                    </ul>
                </div>
            </div>

        </div>

        <!-- Right side -->
        <div class="col-lg-10 py-5 right-side-content">
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <h2 class="mb-2">{{ __('custom.information') }}</h2>
                </div>
            </div>

            <!-- Област на политика -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ trans_choice('custom.field_of_actions', 1) }}</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="{{ route('advisory-boards.index') }}"
                                   class="main-color text-decoration-none fs-5">
                                    <i class="fa-solid fa-hospital me-1 main-color"
                                       title="{{ $item->policyArea?->name }}"></i>
                                    {{ $item->policyArea?->name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Наименование -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.name_of_advisory_board') }}</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                {{ $item->name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Представител на НПО -->
            @if($item->has_npo_presence)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">Наличие на представител на НПО в състава на съвета </h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Представител/и на академичната общност;
                                </li>
                                <li class="list-group-item">Представител/и на местното самоуправление/на Националното
                                    сдружение на
                                    общините в Република България;
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Орган, към който е създаден консултативен съвет -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ trans_choice('custom.authority_advisory_board', 1) }}</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="#" class="main-color text-decoration-none">
                                    <i class="fa-solid fa-right-to-bracket me-1 main-color"
                                       title="{{ $item->authority?->name }}"></i>
                                    {{ $item->authority?->name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Председатели -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.chairman_site') }}</h3>
                        <ul class="list-group list-group-flush">
                            @if(isset($item->chairmen) && $item->chairmen->count() > 0)
                                @foreach($item->chairmen as $chairman)
                                    <li class="list-group-item">
                                        @if(!empty($chairman->member_job))
                                            {{ $chairman->member_name . ', ' .$chairman->member_job }}
                                        @else
                                            {{ $chairman->member_name }}
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Заместник председатели -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.vice_chairman_site') }}</h3>

                        <ul class="list-group list-group-flush">
                            @if(isset($item->viceChairmen) && $item->viceChairmen->count() > 0)
                                @foreach($item->viceChairmen as $chairman)
                                    <li class="list-group-item">
                                        @if(!empty($chairman->member_job))
                                            {{ $chairman->member_name . ', ' .$chairman->member_job }}
                                        @else
                                            {{ $chairman->member_name }}
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Членове -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('validation.attributes.council_members') }}</h3>
                        <ul class="list-group list-group-flush">
                            @if(isset($item->members) && $item->members->count() > 0)
                                @foreach($item->members as $member)
                                    <li class="list-group-item">
                                        @if(!empty($member->member_job))
                                            {{ $member->member_name . ', ' .$member->member_job }}
                                        @else
                                            {{ $member->member_name }}
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Секретар -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ trans_choice('custom.secretary', 1) }}</h3>
                        <ul class="list-group list-group-flush">
                            @if(isset($item->secretaryCouncil) && $item->secretaryCouncil->count() > 0)
                                @foreach($item->secretaryCouncil as $secretary)
                                    <li class="list-group-item">
                                        @if(!empty($secretary->member_name) && !empty($secretary->job) && !empty($secretary->notes))
                                            {{ $secretary->member_name . ', ' . $secretary->job . ', '}} {!! $secretary->notes !!}
                                        @elseif(!empty($secretary->member_name) && !empty($secretary->job))
                                            {{ $secretary->member_name . ', ' . $secretary->job }}
                                        @else
                                            {{ $secretary->member_name }}
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Секретариат -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.secretariat') }}</h3>

                        @if(!empty($item->secretariat))
                            {!! $item->secretariat->description !!}
                        @endif

                        @if(!empty($item->secretariat?->siteFiles) && $item->secretariat->siteFiles->count() > 0)
                            @foreach($item->secretariat->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Правилник за вътрешната организация на дейността -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.rules_internal_organization') }}</h3>

                        @if(!empty($item->regulatoryFiles) && $item->regulatoryFiles->count() > 0)
                            @foreach($item->regulatoryFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Работна програма -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-3 fs-4">{{ __('custom.function') }}</h3>

                        @if(!empty($item->advisoryFunction))
                            {!! $item->advisoryFunction->description !!}
                        @endif

                        @if(!empty($item->advisoryFunction?->files) && $item->advisoryFunction?->files->count() > 0)
                            @foreach($item->advisoryFunction?->files as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Заседания и решения -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.meetings_and_decisions') }}</h3>

                        @if(!empty($item->meetings) && $item->meetings->count() > 0)
                            @foreach($item->meetings as $meeting)
                                <p>
                                    {!! $meeting->description !!}
                                </p>
                            @endforeach
                        @endif

                        @if(!empty($item->meetings) && $item->meetings->count() > 0)
                            @foreach($item->meetings as $meeting)
                                @if(isset($meeting->files) && $meeting->files->count() > 0)
                                    @foreach($meeting->files as $file)
                                        @includeIf('site.partial.file', ['file' => $file])
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Инфорация за модератора „Консултативен съвет“ -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.advisory_board_moderator_info') }}</h3>

                        @if(!empty($item->moderatorInformation))
                            <p>
                                {!! $item->moderatorInformation->description !!}
                            </p>
                        @endif

                        @if(!empty($item->moderatorFiles) && $item->moderatorFiles->count() > 0)
                            @foreach($item->moderatorFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Препратка към Интегрираната информационна система на държавната администрация (ИИСДА) -->
            @if(!empty($item->integration_link))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('validation.attributes.redirect_to_iisda') }}</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="{{ $item->integration_link }}" target="_blank"
                                       class="main-color text-decoration-none">
                                        <i class=" fa-solid fa-link main-color me-2 fs-5"></i>
                                        {{ __('custom.advisory_board_description_link') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ръчно направени секции -->
            @if(isset($item->customSections) && $item->customSections->count() > 0)
                @foreach($item->customSections as $section)
                    <div class="row mb-4 ks-row">
                        <div class="col-md-12">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ $section->title }}</h3>

                                <p>{!! $section->body !!}</p>

                                @if(!empty($section->files) && $section->files->count() > 0)
                                    @foreach($section->files as $file)
                                        @includeIf('site.partial.file', ['file' => $file])
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
