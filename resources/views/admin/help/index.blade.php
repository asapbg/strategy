@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">
                    <div class="row">
                        @php($user = auth()->user())
                        @if($user->hasAnyRole([\App\Models\CustomRole::ADMIN_USER_ROLE, \App\Models\CustomRole::SUPER_USER_ROLE, \App\Models\CustomRole::MODERATOR_ADVISORY_BOARD, \App\Models\CustomRole::MODERATOR_ADVISORY_BOARDS]))
                            <div class="col-lg-4 col-md-12 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'advisory_boards']) }}" class="btn btn-info px-3 py-3" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Консултативни съвети"
                                </span>
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection





