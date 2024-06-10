@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', trans_choice('custom.advisory_boards', 2))

@section('content')

<div class="row">
    @include('site.advisory-boards.side_menu_detail_page')

    <div class="col-lg-10 right-side-content py-2">
        <div class="row mb-2">
{{--            <h2 class="mb-4">--}}
{{--                {{ __('site.advisory_boards.moderators_contacts.title') }}--}}
{{--            </h2>--}}
{{--            @if($item->moderatorInformation)--}}
{{--                <h3 class="fs-5">{{ __('site.advisory_boards.moderators_information') }}</h3>--}}
{{--                <hr>--}}
{{--                <div class="">{!! $item->moderatorInformation->description !!}</div>--}}

{{--                @if($item->moderatorInformation->files->count())--}}
{{--                    @foreach($item->moderatorInformation->files as $f)--}}
{{--                        --}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            @endif--}}

            <h3 class="fs-5 @if($item->moderatorInformation) mt-4 @endif">{{ __('site.advisory_boards.moderators') }}</h3>
            <hr>
            @if($item->moderators->count())
                @foreach($item->moderators as $m)
                    <div class="col-lg-6 mb-4 ">
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
    </div>
</div>
@endsection
