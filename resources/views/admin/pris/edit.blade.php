@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    @if($item->id)
                        {{ $item->about }}
                    @else
                        {{ __('custom.new_record') }}
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        @php($storeRoute = route($storeRouteName, $item))
                        <div class="col-md-6">
                            <form action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
                                @csrf
                                @if($item->id)
                                    @method('PUT')
                                    <input type="hidden" name="type" value="{{ $item->type ?? 0 }}">
                                @endif
                                <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="doc_num">
                                                {{ __('validation.attributes.doc_num') }}
                                            </label>
                                            <div class="col-12">
                                                <input type="number" name="doc_num" value="{{ old('doc_num', $item->id ? $item->doc_num : '') }}" class="form-control form-control-sm @error('doc_num'){{ 'is-invalid' }}@enderror">
                                                @error('doc_num')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="doc_date">
                                                {{ __('validation.attributes.doc_date') }}
                                            </label>
                                            <div class="col-12">
                                                <input type="text" name="doc_date" value="{{ old('doc_date', $item->id ? $item->doc_date : '') }}" class="form-control form-control-sm datepicker @error('doc_date'){{ 'is-invalid' }}@enderror">
                                                @error('doc_date')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="legal_act_type_id">
                                                {{ trans_choice('custom.legal_act_types', 1) }}
                                            </label>
                                            <div class="col-12">
                                                <select id="legal_act_type_id" name="legal_act_type_id"  class="form-control form-control-sm @error('legal_act_type_id'){{ 'is-invalid' }}@enderror">
                                                    @if(!$item->id)
                                                        <option value="">---</option>
                                                    @endif
                                                    @if(isset($legalActTypes) && $legalActTypes->count())
                                                        @foreach($legalActTypes as $row)
                                                            <option value="{{ $row->id }}" @if(old('legal_act_type_id', ($item->id ? $item->legal_act_type_id : '')) == $row->id) selected @endif>{{ $row->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('legal_act_type_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12"></div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-12 control-label" for="institution_id">
                                                {{ trans_choice('custom.institutions', 1) }}
                                            </label>
                                            <div class=" col-12 d-flex flex-row">
                                                <div class="input-group">
                                                    <select class="form-control form-control-sm select2 @error('institution_id') is-invalid @enderror" name="institution_id" id="institution_id">
                                                        <option value="" @if('' == old('institution_id', '')) selected @endif>---</option>
                                                        @if(isset($institutions) && sizeof($institutions))
                                                            @foreach($institutions as $option)
                                                                <option value="{{ $option['value'] }}" @if($option['value'] == old('institution_id', ($item->id ? $item->institution_id : ''))) selected @endif>{{ $option['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                        data-title="{{ trans_choice('custom.institutions',2) }}"
                                                        data-url="{{ route('modal.institutions').'?select=1&multiple=0&admin=1&dom=institution_id' }}">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                            </div>
                                            @error('institution_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="protocol">
                                                {{ __('validation.attributes.protocol') }}
                                            </label>
                                            <div class="col-12">
                                                <input type="text" name="protocol" value="{{ old('protocol', $item->id ? $item->protocol : '') }}" class="form-control form-control-sm @error('protocol'){{ 'is-invalid' }}@enderror">
                                                @error('protocol')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="public_consultation_id">
                                                {{ trans_choice('custom.public_consultations', 1) }}
                                            </label>
                                            <div class="col-12">
                                                <select id="public_consultation_id" name="public_consultation_id"  class="form-control form-control-sm @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                                                    <option value="">---</option>
                                                    @if(isset($publicConsultations) && $publicConsultations->count())
                                                        @foreach($publicConsultations as $row)
                                                            <option value="{{ $row->id }}" @if(old('public_consultation_id', ($item->id ? $item->public_consultation_id : '')) == $row->id) selected @endif>{{ $row->title }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('public_consultation_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="newspaper_number">
                                                {{ __('validation.attributes.newspaper_number') }}
                                            </label>
                                            <div class="col-12">
                                                <input type="text" name="newspaper_number" value="{{ old('newspaper_number', $item->id ? $item->newspaper_number : '') }}" class="form-control form-control-sm @error('newspaper_number'){{ 'is-invalid' }}@enderror">
                                                @error('newspaper_number')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @include('admin.partial.edit_field_translate', ['field' => 'about', 'required' => true])
                                    @include('admin.partial.edit_field_translate', ['field' => 'legal_reason', 'required' => true])

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="change_docs[]">
                                                {{ __('custom.change_docs') }}
                                            </label>
                                            <div class="col-12">
                                                @php($itemChangedDocIds = $item->changedDocs->pluck('id')->toArray())
                                                <select id="change_docs" name="change_docs[]" multiple="multiple"  data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="dsfsf" class="form-control form-control-sm select2-autocomplete-ajax @error('change_docs'){{ 'is-invalid' }}@enderror">
                                                    @if(isset($item->changedDocs) && $item->changedDocs->count())
                                                        @foreach($item->changedDocs as $row)
                                                            <option value="{{ $row->id }}" @if(in_array($row->id, old('change_docs', $itemChangedDocIds))) selected @endif>{{ $row->doc_num.' ('.$row->actType->name.')' }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('change_docs')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="tags[]">
                                                {{ trans_choice('custom.tags', 2) }}
                                            </label>
                                            <div class="col-12">
                                                @php($itemTagsIds = $item->tags->pluck('id')->toArray())
                                                <select id="tags" name="tags[]" multiple="multiple" class="form-control form-control-sm select2 select2-hidden-accessible @error('tags'){{ 'is-invalid' }}@enderror">
                                                    @if(isset($tags) && $tags->count())
                                                        @foreach($tags as $row)
                                                            <option value="{{ $row->id }}" @if(in_array($row->id, old('tags', $itemTagsIds))) selected @endif>{{ $row->label }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('tags')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @if($item->id)
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">{{ __('custom.created_at') }}</label>
                                                <div class="col-12">{{ displayDate($item->created_at) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">{{ __('custom.published_at') }}</label>
                                                <div class="col-12">@if($item->published_at){{ displayDate($item->published_at) }}@else{{ '---' }}@endif</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                        @can('publish', $item)
                                            <button id="save" type="submit" class="btn btn-success" name="publish" value="1">{{ __('custom.publish') }}</button>
                                        @endcan
                                        <a href="{{ route($listRouteName) }}"
                                           class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                    </div>
                                </div>
                                <br/>
                            </form>
                        </div>
{{--                        @if($item->id)--}}
{{--                            <div class="col-md-6">--}}
{{--                                Файлове--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection