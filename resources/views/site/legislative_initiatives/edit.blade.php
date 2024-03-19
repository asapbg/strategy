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
                                <label for="operational_program_id" class="form-label">{{ trans_choice('custom.laws', 1) }}</label>

                                <select id="law_id" name="law_id" data-types2ajax="law"
                                        data-urls2="{{ route('admin.select2.ajax', 'law') }}"
                                        data-placeholders2="{{ __('custom.search_op_record_js_placeholder') }}"
                                        class="form-control form-control-sm select2-autocomplete-ajax li-law @error('law_id'){{ 'is-invalid' }}@enderror">
                                    @if($item->law_id)
                                        <option value="{{ $item->law_id }}" selected="selected">{{ $item->law?->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="institutions_section">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                @php($itemInstitutions = $item->institutions->pluck('id')->toArray())
                                <label for="institutions" class="form-label">{{ trans_choice('custom.institutions', 1) }}</label>
                                <select id="institutions" name="institutions[]" multiple class="form-control form-control-sm select2 @error('institutions'){{ 'is-invalid' }}@enderror">
                                    <option value="0">{{ __('custom.send_to_all') }}</option>
                                    @if(isset($institutions) && $institutions->count())
                                        @foreach($institutions as $inst)
                                            <option value="{{ $inst->value }}" data-laws="{{ $inst->laws }}" @if(in_array($inst->value, old('institutions', $itemInstitutions))) selected @endif>{{ $inst->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('institutions')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
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
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#law_id').trigger('change');
        });
    </script>
@endpush
