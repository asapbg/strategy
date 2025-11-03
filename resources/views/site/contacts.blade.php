@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.contacts_side_menu')

        <div class="col-lg-10 right-side-content py-2">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ $title }}
                </h2>
                @if(isset($users) && $users->count())
                    @if($form)
                        <div class="col-12 mb-5 ">
                            <div class="member d-flex align-items-center p-3 custom-shadow br-08">

                                <form class="col-12 mb-4 " action="{{ route('contacts.message') }}" method="POST">
                                    @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <div class="mb-3 d-flex flex-column  w-100">
                                                        <label for="subject" class="form-label">{{ __('site.contacts.subject') }} <span class="required">*</span></label>
                                                        <select id="subject" name="subject" class="form-control form-control-sm select2 @error('subjectsubject'){{ 'is-invalid' }}@enderror">
                                                            <option value="">---</option>
                                                            <option value="{{ __('site.contacts.subject.report_problem') }}">{{ __('site.contacts.subject.report_problem') }}</option>
                                                            <option value="{{ __('site.contacts.subject.question') }}">{{ __('site.contacts.subject.question') }}</option>
                                                            <option value="{{ __('site.contacts.subject.proposal') }}">{{ __('site.contacts.subject.proposal') }}</option>
                                                        </select>
                                                        @error('subject')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group ">
                                                    <div class="mb-3 d-flex flex-column w-100">
                                                        <label for="message" class="form-label">{{ __('site.contacts.message') }} <span class="required">*</span></label>
                                                        <div class="summernote-wrapper">
                                                            <textarea class="form-control @error('message'){{ 'is-invalid' }}@enderror" name="message">@if(!empty(old('message'))){!! old('message') !!}@endif</textarea>
                                                            @error('message')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">{{ __('custom.send') }}</button>
                                </form>

                            </div>
                        </div>
                        <h2 class="mb-4">
                            {{ trans_choice('site.administrators', 2) }}
                        </h2>
                    @endif

                    @foreach($users as $row)
                        <div class="col-lg-6 mb-4 ">
                            <div class="member d-flex align-items-center p-3 custom-shadow br-08">
                                <div class="member-info">
                                    <p class="team-member-name fs-3 main-color mb-0">
                                        {{ $row->fullName() }}
                                    </p>
                                    <p class="team-position text-secondary mb-2 fw-600 text-uppercase">
                                        @foreach($row->roles as $r)
                                            @if(in_array($r->name, $roles))
                                                <span class="d-block">{{ $r->display_name }}</span>
                                            @endif
                                        @endforeach
                                        @if(!empty($row->institution_id) && $row->institution)
                                            <span class="main-color me-4 fw-normal d-block mt-1">
                                                <i class="fa-solid fa-building me-1"></i>
                                                {{ $row->institution->name }}
                                            </span>
                                        @endif
                                    </p>
                                    @if((!empty($row->email) || !empty($row->phone)) && $row->show_contacts)
                                        <div class="team-member-contact d-flex flex-row mb-2">
                                            @if(!empty($row->phone))
                                                <a href="#" class="text-decoration-none me-4">
                                                    <i class="fa-solid fa-phone me-1"></i>
                                                    {{ $row->phone }}
                                                </a>
                                            @endif
                                            @if(!empty($row->email))
                                                <a href="mailto:{{ $row->email }}" class="text-decoration-none">
                                                    <i class="fa-solid fa-envelope me-1"></i>
                                                    {{ $row->email }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    @switch($section)
                                        @case('advisory-boards')
                                            @if($row->moderateAdvisoryBoards->count())
                                                @php($advBoards = $row->moderatedAdvBoardOrdered())
                                                @if($advBoards->count())
                                                    <div class="team-member-boards">
                                                        @foreach($advBoards as $ab)
                                                            <a class="w-100 d-block" href="{{ route('advisory-boards.view', $ab) }}">{{ $ab->name }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @break
                                        @case('strategy-documents')
                                            @if($row->institution)
                                                @php($institution = $row->institution)
                                                <div class="team-member-boards">
                                                    <a class="w-100 d-block" href="{{ route('institution.profile', $institution) }}">{{ $institution->name }}</a>
                                                    @if($institution->fieldsOfActionOrdered->count())
                                                        <ul class="mt-1">
                                                            @foreach($institution->fieldsOfActionOrdered as $foa)
                                                                <li class="main-color">
                                                                @switch($foa->parentid)
                                                                    @case(\App\Models\FieldOfAction::CATEGORY_NATIONAL)
                                                                            <a class="text-decoration-none" href="{{ route('strategy-documents.index').'?fieldOfActions[]='.$foa->id }}">{{ $foa->name }}</a>
                                                                    @break
                                                                    @case(\App\Models\FieldOfAction::CATEGORY_AREA)
                                                                            <a class="text-decoration-none" href="{{ route('strategy-documents.index').'?areas[]='.$foa->id }}">{{ $foa->name }}</a>
                                                                        @break
                                                                    @case(\App\Models\FieldOfAction::CATEGORY_MUNICIPAL)
                                                                            <a class="text-decoration-none" href="{{ route('strategy-documents.index').'?municipalities[]='.$foa->id }}">{{ $foa->name }}</a>
                                                                        @break
                                                                    @default
                                                                        {{ $foa->name }}
                                                                @endswitch
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endif
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
