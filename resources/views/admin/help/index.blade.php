@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">
                    <div class="row">
                        @php($user = auth()->user())
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'admin']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Администратор"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_ADVISORY_BOARDS
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'advisory_boards']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Раздел Консултативни съвети"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_ADVISORY_BOARD
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'advisory_boards_inner']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Консултативни съвети"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_STRATEGIC_DOCUMENTS
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'sd']) }}" class="btn btn-info px-3 py-3 w-100" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Раздел Стратегически документи"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_STRATEGIC_DOCUMENTS
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'sd_inner']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Стратегически документи"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_PARTNERSHIP
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'ogp']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Партньорство за открито управление"
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_PRIS
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'pris']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за Актове на Министерския съвет
                                </span>
                                </a>
                            </div>
                        @endif
                        @if($user->hasAnyRole([
                                \App\Models\CustomRole::ADMIN_USER_ROLE,
                                \App\Models\CustomRole::SUPER_USER_ROLE,
                                \App\Models\CustomRole::MODERATOR_PUBLIC_CONSULTATION
                            ]))
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('admin.help.guide', ['section' => 'pc']) }}" class="btn btn-info px-3 py-3 w-100 guide-btn-box" target="_blank">
                                <span class="home-icon">
                                    <i class="fas fa-file text-white"></i>
                                </span>
                                    <span class="home-section-button-txt">
                                    Ръководство за "Обществени консултации"
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





