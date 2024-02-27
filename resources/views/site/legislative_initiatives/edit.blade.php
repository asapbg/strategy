@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 py-5 d-flex justify-content-center right-side-content">
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
