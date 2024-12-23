@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 py-5 d-flex justify-content-center right-side-content">
            <div class="col-md-12 col-lg-8 col-sm-12">
                <form  class="custom-card p-3" action="{{ route('legislative_initiatives.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="fst-italic text-danger mb-3">{{ __('custom.li_support_info_general', ['days' => $supportDays, 'cap' => $cap]) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="user-name"
                                           class="form-label">{{ __('validation.attributes.name_organization_names') }}</label>
                                    <input id="user-name" type="text" class="form-control"
                                           value="{{ auth()->user()->fullName() }}" disabled/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="user-email"
                                           class="form-label">{{ __('validation.attributes.email_address') }}</label>
                                    <input id="user-email" type="email" class="form-control"
                                           value="{{ auth()->user()->email }}" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="law_id" class="form-label">{{ trans_choice('custom.laws', 1) }}</label>
                                    <select id="law_id" name="law_id" @if(isset($lawWithActivePc) && sizeof($lawWithActivePc)) data-activepc="{{ json_encode($lawWithActivePc, JSON_UNESCAPED_UNICODE) }}" @endif data-types2ajax="law"
                                            data-urls2="{{ route('select2.ajax', 'law') }}"
                                            data-placeholders2="{{ __('custom.search_op_record_js_placeholder') }}"
                                            class="form-control form-control-sm select2-autocomplete-ajax  li-law @error('law_id'){{ 'is-invalid' }}@enderror">
                                    </select>
                                    @error('law_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="institutions_section">
                            <div class="input-group">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="institutions" class="form-label">{{ trans_choice('custom.institutions', 1) }}</label>
                                    <select id="institutions" name="institutions[]" multiple class="form-control form-control-sm select2 @error('institutions'){{ 'is-invalid' }}@enderror">
                                        <option value="0">{{ __('custom.send_to_all') }}</option>
                                        @if(isset($institutions) && $institutions->count())
                                            @foreach($institutions as $inst)
                                                <option value="{{ $inst->value }}" data-laws="{{ $inst->laws }}" @if(in_array($inst->value, old('institutions', []))) selected @endif>{{ $inst->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('institutions')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-none" id="active_consultation_info">
                        <div class="col-12">
                            <div class="text-danger mb-2">
                                {!! __('site.li_there_is_active_consultation') !!}
                            </div>
                            <div id="consultations">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 my-3">
                            <hr class="custom-hr">
                            <span class="py-2 d-inline-block"><strong>{{ __('custom.active_law_text') }}</strong></span>
                            <hr class="custom-hr">
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="law_paragraph" class="form-label">{{ __('validation.attributes.law_paragraph') }} <span class="required">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea class="summernote @error('law_paragraph'){{ 'is-invalid' }}@enderror" id="law_paragraph" name="law_paragraph">@if(!empty(old('law_paragraph'))){!! old('law_paragraph') !!}@endif</textarea>
                                        @error('law_paragraph')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="law_text" class="form-label">{{ __('validation.attributes.law_text') }} <span class="required">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea class="summernote @error('law_text'){{ 'is-invalid' }}@enderror" id="law_text" name="law_text">@if(!empty(old('law_text'))){!! old('law_text') !!}@endif</textarea>
                                        @error('law_text')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description" class="form-label">{{ __('custom.description_of_suggested_change') }} <span class="required">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea class="summernote @error('description'){{ 'is-invalid' }}@enderror" id="description" name="description">@if(old('description')){!! old('description') !!}@endif</textarea>
                                        @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="motivation" class="form-label">{{ __('custom.change_motivations') }} <span class="required">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea class="summernote @error('motivation'){{ 'is-invalid' }}@enderror" id="motivation" name="motivation">@if(old('motivation')){!! old('motivation') !!}@endif</textarea>
                                        @error('motivation')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="submit" id="new_li_submit" class="btn btn-primary">{{ __('custom.send') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#law_id').trigger('change');
        });
    </script>
@endpush
