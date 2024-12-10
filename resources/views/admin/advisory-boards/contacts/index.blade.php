@extends('layouts.admin')

@section('content')
    <div class="col-lg-12 right-side-content py-2">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">
                            {{ __('site.advisory_boards.contacts.title') }}
                        </h2>
                    </div>
                </div>
            </div>

            @if(isset($moderators) && $moderators->count())
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach($moderators as $m)
                                    <div class="col-lg-6 mb-4">
                                        <div class="d-flex align-items-center p-3 shadow-sm br-08">
                                            <div class="">
                                                <p class="team-member-name fs-3 main-color mb-0">
                                                    {{ $m->fullName() }}
                                                </p>
                                                <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                                                    @foreach($m->roles as $r)
                                                        @if(in_array($r->name, [\App\Models\CustomRole::MODERATOR_ADVISORY_BOARDS, \App\Models\CustomRole::MODERATOR_ADVISORY_BOARD]))
                                                            {{ $r->display_name }}
                                                        @endif
                                                    @endforeach
                                                </p>

                                                @if(!empty($m->email) || !empty($m->phone))
                                                    <div class="team-member-contact d-flex flex-row">
                                                        @if(!empty($m->phone))
                                                            <a href="#" class="text-decoration-none me-4">
                                                                <i class="fa-solid fa-phone me-1"></i>
                                                                {{ $m->phone }}
                                                            </a>
                                                        @endif
                                                        @if(!empty($m->email))
                                                            <a href="mailto:{{ $m->email }}" class="text-decoration-none">
                                                                <i class="fa-solid fa-envelope me-1"></i>
                                                                {{ $m->email }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
