@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">
                                {{ __('custom.general_info') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form class="row" action="{{ route('admin.advisory-boards.messages.send') }}" method="post" name="form" id="form" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="type">
                                        {{ __('custom.send_to') }}: <span class="requred">*</span>
                                    </label>
                                    <div class="d-inline">
                                        <select id="members" name="recipient[]" class="form-control select2 form-control-sm @error('recipient'){{ 'is-invalid' }}@enderror" multiple>
{{--                                            <option value="" @if(empty(old('recipient'))) selected @endif></option>--}}
                                            @if(isset($moderators) && $moderators->count())
                                                @foreach($moderators as $row)
                                                    <option value="{{ $row->id }}" @if(in_array($row->id, old('recipient', []))) selected @endif>{{ $row->fullInformation }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('recipient')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cl-12 mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" id="send_to_all" name="send_to_all" class="form-check-input" value="1" @if(old('send_to_all', 0)) checked="" @endif>
                                        <label class="form-check-label" for="date_valid_indefinite_main">
                                            {{ __('custom.send_to_all') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="type">
                                        {{ __('validation.attributes.title') }}: <span class="requred">*</span>
                                    </label>
                                    <div class="d-inline">
                                        <input id="text" name="title" class="form-control form-control-sm @error('title'){{ 'is-invalid' }}@enderror" value="{{ old('title', '') }}">
                                        @error('title')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="type">
                                        {{ __('validation.attributes.content') }}: <span class="requred">*</span>
                                    </label>
                                    <div class="d-inline">
                                        <textarea name="content" class="form-control form-control-sm summernote @error('content'){{ 'is-invalid' }}@enderror">{{ old('content', '') }}</textarea>
                                        @error('content')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.send_2') }}</button>
                                <a href="{{ route('admin.advisory-boards.messages') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            let membersSelect = $('#members');

            function controlMemberSelect(){
                if($('#send_to_all').is(':checked')){
                    membersSelect.val('').trigger('change');
                    membersSelect.prop('disabled', true);
                } else{
                    membersSelect.prop('disabled', false);
                }
            }

            $('#send_to_all').on('change', function (){
                controlMemberSelect();
            });

            controlMemberSelect();
        });
    </script>
@endpush
