@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.contacts_side_menu')

        <div class="col-lg-10 right-side-content py-5">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ $title }}
                </h2>
                @if(isset($users) && $users->count())
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
                                                {{ $r->display_name }}
                                            @endif
                                        @endforeach
                                    </p>
                                    @if(!empty($row->email) || !empty($row->phone))
                                        <div class="team-member-contact d-flex flex-row">
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
