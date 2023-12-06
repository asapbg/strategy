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
                                        @if(!empty($secretary->name) && !empty($secretary->job) && !empty($secretary->notes))
                                            {{ $secretary->name . ', ' . $secretary->job . ', '}} {!! $secretary->notes !!}
                                        @elseif(!empty($secretary->name) && !empty($secretary->job))
                                            {{ $secretary->name . ', ' . $secretary->job }}
                                        @else
                                            {{ $secretary->name }}
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

                        @if(!empty($item->secretariat?->files) && $item->secretariat->files->count() > 0)
                            @foreach($item->secretariat->files as $file)
                                @includeIf('site.partial.file', ['file' => $file])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.rules_internal_organization') }}</h3>

                        <div class="document-wrapper-ks mt-3">
                            <a href="#" class="main-color text-decoration-none fs-18">
                                <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>
                                {{ __('custom.example_file') }}
                            </a>

                            <div class="document-info-field d-flex mt-3 pb-2">
                                <div class="doc-info-item">
                                    <strong> {{ __('custom.status') }}:</strong>
                                    <span class="active-li w-min-content">Активен</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ПМС №:</strong>
                                    <span><a href="#" class="text-decoration-none">150 обн.</a></span>
                                </div>
                                <div class="doc-info-item">
                                    <strong> ДВ:</strong>
                                    <span><a href="#" class="text-decoration-none">№105</a></span>
                                </div>
                                <div class="doc-info-item">
                                    <strong> {{ __('custom.effective_from') }}:</strong>
                                    <span>10.10.2023г.</span>
                                </div>
                                <div class="doc-info-item">
                                    <strong> {{ __('custom.date_published') }}:</strong>
                                    <span>15.10.2023г.</span>
                                </div>
                                <div class="doc-info-item">
                                    <strong> {{ trans_choice('custom.kinds', 1) }}:</strong>
                                    <span class="text-success">Действащ документ</span>
                                </div>
                            </div>
                            <div class="file-version pb-2">
                                <strong> {{ __('custom.versions') }}:</strong>

                                <span><a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 ks-row">
                <div class="col-md-12 ">
                    <div class="custom-card p-3">
                        <h3 class="mb-3 fs-4">{{ __('custom.function') }}</h3>

                        <p>Свободен текст на работна програма</p>

                        <p class="fw-600">Примерна таблица</p>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="document-wrapper-ks mt-3">
                            <a href="#" class="main-color text-decoration-none fs-18"><i
                                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                            <div class="document-info-field d-flex mt-3 pb-2">
                                <div class="doc-info-item">
                                    <strong> {{ __('custom.status') }}:</strong> <span class="active-li w-min-content">Активен</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ПМС №:</strong>
                                    <span><a href="#" class="text-decoration-none">150 обн.</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ДВ:</strong>
                                    <span><a href="#" class="text-decoration-none">№105</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.effective_from') }}:</strong>
                                    <span>10.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.date_published') }}:</strong>
                                    <span>15.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ trans_choice('custom.kinds', 1) }}:</strong>
                                    <span class="text-success">Действащ документ</span>
                                </div>
                            </div>

                            <div class="file-version pb-2">
                                <strong> {{ __('custom.versions') }}:</strong>
                                <span>
                                    <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
                                </span>
                            </div>
                        </div>

                        <div class="document-wrapper-ks mt-3">
                            <a href="#" class="main-color text-decoration-none fs-18"><i
                                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>

                            <div class="document-info-field d-flex mt-3 pb-2">
                                <div class="doc-info-item">
                                    <strong> {{ __('custom.status') }}:</strong> <span class="closed-li w-min-content">Неактивен</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ПМС №:</strong>
                                    <span><a href="#" class="text-decoration-none">150 обн.</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ДВ:</strong>
                                    <span><a href="#" class="text-decoration-none">№105</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.effective_from') }}:</strong>
                                    <span>10.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.date_published') }}:</strong>
                                    <span>15.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ trans_choice('custom.kinds', 1) }}:</strong>
                                    <span class="text-danger">Недействащ документ</span>
                                </div>
                            </div>

                            <div class="file-version pb-2">
                                <strong> {{ __('custom.versions') }}:</strong>
                                <span>
                                    <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.meetings_and_decisions') }}</h3>

                        <p>
                            Проведено бе заседание на ВСФ на 10.03.2017г . на което, чрез тайно гласуване бе избран
                            заместник-председател на ВСФ. Обсъдено бе текущото състояние на системата на
                            лекарствоснабдяването и
                            актуалните проблеми през 2017г.
                        </p>

                        <div class="document-wrapper-ks mt-3">
                            <a href="#" class="main-color text-decoration-none fs-18"><i
                                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                            <div class="document-info-field d-flex mt-3 pb-2">
                                <div class="doc-info-item">
                                    <strong>{{ __('custom.status') }}:</strong> <span class="active-li w-min-content">Активен</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ПМС №:</strong>
                                    <span><a href="#" class="text-decoration-none">150 обн.</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ДВ:</strong>
                                    <span><a href="#" class="text-decoration-none">№105</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.effective_from') }}:</strong>
                                    <span>10.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ __('custom.date_published') }}:</strong>
                                    <span>15.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong>{{ trans_choice('custom.kinds', 1) }}:</strong>
                                    <span class="text-success">Действащ документ</span>
                                </div>
                            </div>
                            <div class="file-version pb-2">
                                <strong>{{ __('custom.versions') }}:</strong>
                                <span>
                                    <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.advisory_board_moderator_info') }}</h3>

                        <p>Свободен текст</p>

                        <div class="document-wrapper-ks mt-3">
                            <a href="#" class="main-color text-decoration-none fs-18"><i
                                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>

                            <div class="document-info-field d-flex mt-3 pb-2">
                                <div class="doc-info-item">
                                    <strong>{{ __('custom.status') }}:</strong> <span class="active-li w-min-content">Активен</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ПМС №:</strong>
                                    <span><a href="#" class="text-decoration-none">150 обн.</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> ДВ:</strong>
                                    <span><a href="#" class="text-decoration-none">№105</a></span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> В сила от:</strong>
                                    <span>10.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> Дата на публикуване:</strong>
                                    <span>15.10.2023г.</span>
                                </div>

                                <div class="doc-info-item">
                                    <strong> Вид:</strong>
                                    <span class="text-success">Действащ документ</span>
                                </div>
                            </div>

                            <div class="file-version pb-2">
                                <strong> {{ __('custom.versions') }}:</strong>

                                <span>
                                    <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
                                    <span>&#47;</span>
                                    <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 ks-row">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-2 fs-4">{{ __('custom.redirect_to_iisda') }}</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="#" class="main-color text-decoration-none">
                                    <i class=" fa-solid fa-link main-color me-2 fs-5"></i>
                                    {{ __('custom.advisory_board_description_link') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
