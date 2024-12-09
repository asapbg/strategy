@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', trans_choice('custom.advisory_boards', 2))

@section('content')

<div class="row">
    @include('site.strategic_documents.side_menu')

    <div class="col-lg-10 right-side-content py-2">
        <div class="row mb-2">
            <h2 class="mb-4">
                {{ __('site.strategic_document.contacts.title') }}
            </h2>

            @if(isset($moderators) && $moderators->count())
                @foreach($moderators as $m)
                    <div class="col-lg-6 mb-4 ">
                        <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                            <div class="member-info">
                                <p class="team-member-name fs-3 main-color mb-0">
                                    {{ $m->fullName() }}
                                </p>
                                <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                                    @foreach($m->roles as $r)
                                        @if(in_array($r->name, [\App\Models\CustomRole::MODERATOR_STRATEGIC_DOCUMENTS, \App\Models\CustomRole::MODERATOR_STRATEGIC_DOCUMENT]))
                                            {{ $r->display_name }}
                                        @endif
                                    @endforeach
                                </p>
                                @if($m->institution)
                                    <p class="text-secondary mb-0">
                                        {{ $m->institution->name }}
                                    </p>
                                    <p class="text-secondary">
                                        {{ $m->institution->address }}
                                    </p>
                                    @if($m->institution->fieldsOfAction->count())
                                        <p class="text-secondary small">
                                            {{ trans_choice('custom.field_of_actions', $m->institution->fieldsOfAction->count()) }}:
                                            {{ implode(', ', $m->institution->fieldsOfAction->pluck('name')->toArray()) }}
                                        </p>
                                    @endif
                                @endif
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
            @endif
        </div>
    </div>
</div>
@endsection
