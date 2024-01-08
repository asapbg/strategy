@extends('layouts.site', ['fullwidth' => true])

<style>
    #siteLogo, #ok, #ms, .nav-link, .nav-item, #register-link, #login-btn, #search-btn, #back-to-admin, #profile-toggle {
        transition: 0.4s;
    }
</style>

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_detail_page')

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

            <!-- Представител на НПО -->
            @if($item->has_npo_presence && isset($item->npos) && $item->npos->count() > 0)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.presence_npo_representative') }}</h3>
                            <ul class="list-group list-group-flush">
                                @foreach($item->npos as $npo)
                                    <li class="list-group-item">{{ $npo->name }};</li>
                                @endforeach
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
            @if(isset($item->viceChairmen) && $item->viceChairmen->count() > 0)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.vice_chairman_site') }}</h3>

                            <ul class="list-group list-group-flush">

                                @foreach($item->viceChairmen as $chairman)
                                    <li class="list-group-item">
                                        @if(!empty($chairman->member_job))
                                            {{ $chairman->member_name . ', ' .$chairman->member_job }}
                                        @else
                                            {{ $chairman->member_name }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Членове -->
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('validation.attributes.council_members') }}</h3>
                        <ul class="list-group list-group-flush">
                            @if(isset($item->members) && $item->members->count() > 0)
                                @foreach($item->members as $member)
                                    <li class="list-group-item">
                                        @php
                                            $name = '';

                                            if (!empty($member->member_name)) {
                                                $name .= $member->member_name;
                                            }

                                            if (!empty($member->member_job)) {
                                                $name .= ', ' . $member->member_job;
                                            }

                                            if (!empty($member->institution)) {
                                                $name .= ', ' . '<a href="#" class="text-decoration-none">' . $member->institution->name . '</a>';
                                            }
                                        @endphp

                                        {!! $name !!}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Секретар -->
            @if(isset($item->secretaryCouncil) && $item->secretaryCouncil->count() > 0)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ trans_choice('custom.secretary', 1) }}</h3>
                            <ul class="list-group list-group-flush">

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
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Секретариат -->
            @if(!empty($item->secretariat))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.secretariat') }}</h3>

                            <p>
                                {!! $item->secretariat->description !!}
                            </p>

                            @if(!empty($item->secretariat?->siteFiles) && $item->secretariat->siteFiles->count() > 0)
                                @foreach($item->secretariat->siteFiles as $file)
                                    @includeIf('site.partial.file', ['file' => $file])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Акт на създаване -->
            @if(!empty($item->establishment) && $item->establishment->count() > 0)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('validation.attributes.act_of_creation') }}</h3>

                            {!! $item->establishment->description !!}

                            @foreach($item->establishment->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Правилник за вътрешната организация на дейността -->
            @if(!empty($item->organizationRule) && $item->organizationRule->count() > 0)
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.rules_internal_organization') }}</h3>

                            {!! $item->organizationRule->description !!}

                            @foreach($item->organizationRule->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Работна програма -->
            @if(!empty($item->workingProgram))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-3 fs-4">{{ __('custom.work_program') }}</h3>

                            <p>
                                {!! $item->workingProgram->description !!}
                            </p>

                            @if(!empty($item->workingProgram->siteFiles) && $item->workingProgram->siteFiles->count() > 0)
                                @foreach($item->workingProgram->siteFiles as $file)
                                    @includeIf('site.partial.file', ['file' => $file])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Заседания и решения -->
            @if((!empty($item->meetings) && $item->meetings->count() > 0) || (isset($nextMeeting) && $nextMeeting))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.meetings_and_decisions') }}</h3>
                            @if((isset($nextMeeting) && $nextMeeting))
                                <p class="fw-bold mt-3">{{ __('validation.attributes.next_meeting') }} {{ __('custom.of') }} {{ __('custom.date') }}: <span class="fw-normal">{{ displayDate($nextMeeting->next_meeting) }}</span></p>
                                <p>
                                    {!! $nextMeeting->description !!}
                                </p>
                                @if(isset($nextMeeting->siteFiles) && $nextMeeting->siteFiles->count() > 0)
                                    @foreach($nextMeeting->siteFiles as $file)
                                        @includeIf('site.partial.file', ['file' => $file, 'debug' => true])
                                    @endforeach
                                @endif
                                <hr>
                            @endif
                            @foreach($item->meetings as $meeting)
                                @continue(isset($nextMeeting) && $nextMeeting && $nextMeeting->id == $meeting->id)
                                <p class="fw-bold mt-3">{{ __('custom.date') }}: <span class="fw-normal">{{ displayDate($meeting->next_meeting) }}</span></p>
                                <p>
                                    {!! $meeting->description !!}
                                </p>
                                @if(isset($meeting->siteFiles) && $meeting->siteFiles->count() > 0)
                                    @foreach($meeting->siteFiles as $file)
                                        @includeIf('site.partial.file', ['file' => $file, 'debug' => true])
                                    @endforeach
                                @endif
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Инфорация за модератора „Консултативен съвет“ -->
            @if(!empty($item->moderatorInformation))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ __('custom.advisory_board_moderator_info') }}</h3>

                            <p>
                                {!! $item->moderatorInformation->description !!}
                            </p>

                            @if(!empty($item->moderatorFiles) && $item->moderatorFiles->count() > 0)
                                @foreach($item->moderatorFiles as $file)
                                    @includeIf('site.partial.file', ['file' => $file])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

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
                                        {{ $item->name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
