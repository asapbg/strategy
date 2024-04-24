@extends('layouts.site', ['fullwidth' => true])

@section('content')

    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 right-side-content pb-5 pt-1 " id="listContainer">
            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <h2 class="mb-2">{{ __('custom.information') }} @if($item) <a class="fs-16" href="{{ route('advisory-boards.view', $item) }}" target="_blank">({{ $item->name }})</a> @endif</h2>
                </div>
            </div>

            @if($customContent)
                <div class="row mb-4">
                    {!! $customContent->value !!}
                </div>
            @endif

            @if($item)
                <!-- Област на политика -->
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ trans_choice('custom.field_of_actions', 1) }}</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="{{ route('advisory-boards.index').($item->policyArea ? '?fieldOfActions[]='.$item->policyArea->id : '') }}"
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

                <!-- Орган, към който е създаден консултативен съвет -->
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ trans_choice('custom.authority_advisory_board', 1) }}</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="{{ route('advisory-boards.index').($item->authority ? '?authoritys[]='.$item->authority->id : '') }}" class="main-color text-decoration-none">
                                        <i class="fa-solid fa-right-to-bracket me-1 main-color"
                                           title="{{ $item->authority?->name }}"></i>
                                        {{ $item->authority?->name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if(isset($item->chairmen) && $item->chairmen->count() > 0)
                    <!-- Председатели -->
                    <div class="row mb-4 ks-row">
                        <div class="col-md-12">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ __('custom.chairman_site') }}</h3>
                                <ul class="list-group list-group-flush">
                                    @foreach($item->chairmen as $chairmen)
                                        @php($dataChairmen = [])
                                        @foreach(['member_name', 'member_job', 'institution'] as $n)
                                            @if(!empty($chairmen->{$n}))
                                                @php($dataChairmen[] = $n != 'institution' ? $chairmen->{$n} : $chairmen->institution->name)
                                            @endif
                                        @endforeach
                                        <li class="list-group-item">
                                            @if(sizeof($dataChairmen))
                                                <span class="d-block mb-2">{{ implode(', ', $dataChairmen) }}</span>
                                            @endif
                                            @if(!empty($chairmen->member_notes))
                                                {!! $chairmen->member_notes !!}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Заместник председатели -->
                @if($item->viceChairmen && $item->viceChairmen->count() > 0)
                    <div class="row mb-4 ks-row">
                        <div class="col-md-12">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ __('custom.vice_chairman_site') }}</h3>

                                <ul class="list-group list-group-flush">
                                    @foreach($item->viceChairmen as $viceChairmen)
                                        @php($dataViceChairmen = [])
                                        @foreach(['member_name', 'member_job', 'institution'] as $n)
                                            @if(!empty($viceChairmen->{$n}))
                                                @php($dataViceChairmen[] = $n != 'institution' ? $viceChairmen->{$n} : $viceChairmen->institution->name)
                                            @endif
                                        @endforeach
                                        <li class="list-group-item">
                                            @if(sizeof($dataViceChairmen))
                                                <span class="d-block mb-2">{{ implode(', ', $dataViceChairmen) }}</span>
                                            @endif
                                            @if(!empty($viceChairmen->member_notes))
                                                {!! $viceChairmen->member_notes !!}
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
                                        @if($member->advisory_type_id == \App\Enums\AdvisoryTypeEnum::MEMBER->value)
                                            <li class="list-group-item">
                                                <span class="d-block mb-2">
                                                    @if(!empty($member->member_name))
                                                        {{ $member->member_name }}
                                                    @endif
                                                    @if(!empty($member->member_job))
                                                        {{ ', ' .$member->member_job }}
                                                    @endif
                                                    @if(!empty($member->institution))
                                                        {{ ', ' .$member->institution->name }}
                                                    @endif
                                                </span>
                                                @if(!empty($member->member_notes))
                                                    {!! $member->member_notes !!}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Представител на НПО -->
                @if($item->has_npo_presence && isset($item->npos) && $item->npos->count() > 0)
                    <div class="row mb-4 ks-row">
                        <div class="col-md-12">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ __('site.presence_npo_representative') }}</h3>
                                <ul class="list-group list-group-flush">
                                    @foreach($item->npos as $npo)
                                        <li class="list-group-item">{{ $npo->name }};</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Секретар -->
                @if(isset($item->secretaryCouncil) && $item->secretaryCouncil->count() > 0)
                    <div class="row mb-4 ks-row">
                        <div class="col-md-12">
                            <div class="custom-card p-3">
                                <h3 class="mb-2 fs-4">{{ trans_choice('custom.secretary', 1) }}</h3>
                                <ul class="list-group list-group-flush">

                                    @foreach($item->secretaryCouncil as $secretary)
                                        @php($dataSecretary = [])
                                        @foreach(['member_name', 'member_job', 'institution'] as $n)
                                            @if(!empty($secretary->{$n}))
                                                @php($dataSecretary[] = $n != 'institution' ? $secretary->{$n} : $secretary->institution->name)
                                            @endif
                                        @endforeach
                                        <li class="list-group-item">
                                            @if(sizeof($dataSecretary))
                                                <span class="d-block mb-2">{{ implode(', ', $dataSecretary) }}</span>
                                            @endif
                                            @if(!empty($secretary->member_notes))
                                                {!! $secretary->member_notes !!}
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
                                        @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
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
                                    @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
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
                                    @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
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
                                        @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
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
                                    <p class="fw-bold mt-3 custom-left-border" style="font-size: 20px;">{{ __('validation.attributes.next_meeting') }} {{ __('custom.of') }} {{ __('custom.date') }}: <span class="fw-normal">{{ displayDate($nextMeeting->next_meeting) }}</span></p>
                                    <p>
                                        {!! $nextMeeting->description !!}
                                    </p>
                                    @if(isset($nextMeeting->siteFiles) && $nextMeeting->siteFiles->count() > 0)
                                        @foreach($nextMeeting->siteFiles as $file)
                                            @includeIf('site.partial.file', ['file' => $file, 'debug' => true, 'no_second_active_status' => true])
                                        @endforeach
                                    @endif
                                    <hr>
                                @endif
                                @foreach($item->meetings as $meeting)
                                    @continue(isset($nextMeeting) && $nextMeeting && $nextMeeting->id == $meeting->id)
                                    <p class="fw-bold mt-3 custom-left-border" style="font-size: 20px;">{{ __('custom.meeting_at') }}: <span class="fw-normal">{{ displayDate($meeting->next_meeting) }} {{ __('custom.year_short') }}</span></p>
                                    <p>
                                        {!! $meeting->description !!}
                                    </p>
                                    @if(isset($meeting->siteFiles) && $meeting->siteFiles->count() > 0)
                                        @foreach($meeting->siteFiles as $file)
                                            @includeIf('site.partial.file', ['file' => $file, 'debug' => true, 'no_second_active_status' => true])
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


                @if($item->moderatorInformation || $item->moderators->count())
                    <div class="row mb-4 ks-row">
                        @if(($item->moderatorInformation && !empty($item->moderatorInformation->description)) || ($item->moderatorFiles && $item->moderatorFiles->count()))
                            <div class="col-md-12">
                                <div class="custom-card p-3">
                                    <h3 class="mb-2 fs-4">{{ __('custom.advisory_board_moderator_info') }}</h3>

                                    <p>
                                        {!! $item->moderatorInformation->description !!}
                                    </p>

                                    @if($item->moderatorInformation->filesByLocale->count())
                                        @foreach($item->moderatorInformation->filesByLocale as $file)
                                            @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif


                        @if($item->moderators->count())
                            @foreach($item->moderators as $m)
                                <div class="col-lg-6 mb-4 @if($loop->first) mt-4 @endif">
                                    <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                                        <div class="member-info">
                                            <p class="team-member-name fs-3 main-color mb-0">
                                                {{ $m->user ? $m->user->fullName() : '---' }}
                                            </p>
                                            <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                                                @foreach($m->user->roles as $r)
                                                    @if(in_array($r->name, [\App\Models\CustomRole::MODERATOR_ADVISORY_BOARDS, \App\Models\CustomRole::MODERATOR_ADVISORY_BOARD]))
                                                        {{ $r->display_name }}
                                                    @endif
                                                @endforeach
                                            </p>
                                            {{--                                <p class="team-member-info dark-text mb-2">--}}
                                            {{--                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.Duis aute--}}
                                            {{--                                    irure dolor in reprehenderit in.--}}
                                            {{--                                </p>--}}
                                            @if($m->user && !empty($m->user->email) || !empty($m->user->phone))
                                                <div class="team-member-contact d-flex flex-row">
                                                    @if(!empty($m->user->phone))
                                                        <a href="#" class="text-decoration-none me-4">
                                                            <i class="fa-solid fa-phone me-1"></i>
                                                            {{ $m->user->phone }}
                                                        </a>
                                                    @endif
                                                    @if(!empty($m->user->email))
                                                        <a href="mailto:{{ $m->user->email }}" class="text-decoration-none">
                                                            <i class="fa-solid fa-envelope me-1"></i>
                                                            {{ $m->user->email }}
                                                        </a>
                                                    @endif

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>{{ __('site.advisory_boards.no_moderators_contacts') }}</p>
                        @endif

                    </div>
                @endif

            <!-- Препратка към Интегрираната информационна система на държавната администрация (ИИСДА) -->
{{--            @if(!empty($item->integration_link))--}}
{{--                <div class="row mb-4 ks-row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="custom-card p-3">--}}
{{--                            <h3 class="mb-2 fs-4">{{ __('validation.attributes.redirect_to_iisda') }}</h3>--}}
{{--                            <ul class="list-group list-group-flush">--}}
{{--                                <li class="list-group-item">--}}
{{--                                    <a href="{{ $item->integration_link }}" target="_blank"--}}
{{--                                       class="main-color text-decoration-none">--}}
{{--                                        <i class=" fa-solid fa-link main-color me-2 fs-5"></i>--}}
{{--                                        {{ $item->name }}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
            @endif
            @if(!$item && !$customContent))
                <p>Не е открита информация</p>
            @endif
        </div>

    </div>
@endsection

