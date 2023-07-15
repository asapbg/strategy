@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        
                        @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])
                    
                        @include('admin.partial.edit_single_translatable', ['field' => 'description', 'required' => true])

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="strategic_document_level_id">{{ trans_choice('custom.strategic_document_level', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="strategic_document_level_id" name="strategic_document_level_id" class="form-control form-control-sm select2 @error('strategic_document_level_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($strategicDocumentLevels) && $strategicDocumentLevels->count())
                                        @foreach($strategicDocumentLevels as $row)
                                            <option value="{{ $row->id }}" @if(old('strategic_document_level_id', ($item->id ? $item->strategic_document_level_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('strategic_document_level_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="policy_area_id">{{ trans_choice('custom.policy_area', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="policy_area_id" name="policy_area_id" class="form-control form-control-sm select2 @error('policy_area_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($policyAreas) && $policyAreas->count())
                                        @foreach($policyAreas as $row)
                                            <option value="{{ $row->id }}" @if(old('policy_area_id', ($item->id ? $item->policy_area_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('policy_area_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="strategic_document_type_id">{{ trans_choice('custom.strategic_document_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="strategic_document_type_id" name="strategic_document_type_id" class="form-control form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                        @foreach($strategicDocumentTypes as $row)
                                            <option value="{{ $row->id }}" @if(old('strategic_document_type_id', ($item->id ? $item->strategic_document_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('strategic_document_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="strategic_act_type_id">{{ trans_choice('custom.strategic_act_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="strategic_act_type_id" name="strategic_act_type_id" class="form-control form-control-sm select2 @error('strategic_act_type_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($strategicActTypes) && $strategicActTypes->count())
                                        @foreach($strategicActTypes as $row)
                                            <option value="{{ $row->id }}" @if(old('strategic_act_type_id', ($item->id ? $item->strategic_act_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('strategic_act_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="document_number">{{ __('custom.document_number') }} <span class="required">*</span></label>
                            <input type="text" id="document_number" name="document_number" class="form-control form-control-sm"
                                value="{{ old('document_number', ($item->id ? $item->document_number : '')) }}">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="authority_accepting_strategic_id">{{ trans_choice('custom.authority_accepting_strategic', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="authority_accepting_strategic_id" name="authority_accepting_strategic_id" class="form-control form-control-sm select2 @error('authority_accepting_strategic_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($authoritiesAcceptingStrategic) && $authoritiesAcceptingStrategic->count())
                                        @foreach($authoritiesAcceptingStrategic as $row)
                                            <option value="{{ $row->id }}" @if(old('authority_accepting_strategic_id', ($item->id ? $item->authority_accepting_strategic_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('authority_accepting_strategic_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="document_date">{{ __('custom.document_date') }} <span class="required">*</span></label>
                            <input type="text" id="document_date" name="document_date" data-provide="datepicker" class="form-control form-control-sm"
                                value="{{ old('document_date', ($item->id ? $item->document_date : '')) }}">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="consultation_number">{{ __('custom.consultation_number') }} <span class="required">*</span></label>
                            <input type="text" id="consultation_number" name="consultation_number" class="form-control form-control-sm"
                                value="{{ old('consultation_number', ($item->id ? $item->consultation_number : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" value="1"
                                    @if ($item->active) checked @endif
                                    class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                    {{ __('validation.attributes.active') }} <span class="required">*</span>
                            </label>
                        </div>
                        @if ($item->id)
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="deleted">
                                <input type="checkbox" id="deleted" name="deleted" class="checkbox" value="1"
                                    @if ($item->deleted_at) checked @endif
                                >
                                {{ __('validation.attributes.deleted') }}
                            </label>
                        </div>
                        @endif
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.strategic_documents.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                    
                    @include('admin.partial.attached_documents')
                </div>
            </div>
        </div>
    </section>
@endsection
