@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
    <div class="row">
        <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2">
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                               data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                            </a>
                            <hr class="custom-hr">
                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                    <li class="mb-2  active-item-left p-1"><a href="#"
                                                                              class="link-dark text-decoration-none">Законодателни
                                            инициативи</a>
                                    </li>
                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                            управление</a>
                                    </li>
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                        <ul class="list-unstyled ps-3">
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Планове
                                                </a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#"
                                                                class="link-dark  text-decoration-none">Отчети</a>
                                            </li>
                                            <hr class="custom-hr">
                                        </ul>
                                    </ul>

                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                        <hr class="custom-hr">
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-lg-10 py-5 d-flex justify-content-center">
            <div class="col-md-12 col-lg-8 custom-card p-3 col-sm-12">
                <form action="{{ route('legislative_initiatives.update', $item) }}" method="POST">
                    @csrf

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="user-name"
                                       class="form-label">{{ __('validation.attributes.name_organization_names') }}</label>
                                <input id="user-name" type="text" class="form-control"
                                       value="{{ auth()->user()->fullName() }}" disabled/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="user-email"
                                       class="form-label">{{ __('validation.attributes.email_address') }}</label>
                                <input id="user-email" type="email" class="form-control"
                                       value="{{ auth()->user()->email }}" disabled/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="operational_program_id" class="form-label">{{ __('custom.name_of_normative_act') }}</label>

                                <select id="operational_program_id" name="operational_program_id" data-types2ajax="op_record"
                                        data-urls2="{{ route('admin.select2.ajax', 'op_record') }}"
                                        data-placeholders2="{{ __('custom.search_op_record_js_placeholder') }}"
                                        class="form-control form-control-sm select2-autocomplete-ajax @error('operation_program_id'){{ 'is-invalid' }}@enderror">
                                    @if($item->operational_program_id)
                                        <option value="{{ $item->operational_program_id }}" selected="selected">{{ $item->operationalProgram?->value }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="description" class="form-label">{{ __('custom.description_of_suggested_change') }}</label>
                                <div class="summernote-wrapper">
                                    <textarea class="summernote" id="description" name="description">
                                        {{ $item->description }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('custom.send') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
