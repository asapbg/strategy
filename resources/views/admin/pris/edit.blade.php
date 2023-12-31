@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">
                                    {{ __('custom.general_info') }}
                                </a>
                            </li>
                            @if($item->id)
                                <li class="nav-item">
                                    <a class="nav-link" id="ct-files-tab" data-toggle="pill" href="#ct-files" role="tab" aria-controls="ct-files" aria-selected="false">{{ trans_choice('custom.files',2) }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                            <div class="row">
                                @php($storeRoute = route($storeRouteName, $item))
                                <div class="col-12">
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
                                                        <select id="legal_act_type_id" name="legal_act_type_id"  class="form-control form-control-sm select2 @error('legal_act_type_id'){{ 'is-invalid' }}@enderror">
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
                                            <div class="col-12"></div>
                                            @include('admin.partial.edit_field_translate', ['field' => 'importer', 'required' => true])
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
                                                        <select id="public_consultation_id" name="public_consultation_id"
                                                                @if($item->id && $item->public_consultation_id) data-current="{{ $item->public_consultation_id }}" @endif
                                                                data-types2ajax="pc" data-urls2="{{ route('admin.select2.ajax', 'pc') }}"
                                                                data-placeholders2="{{ __('custom.search_pc_record_js_placeholder') }}"
                                                                class="form-control form-control-sm select2-autocomplete-ajax @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                                                            <option value="">---</option>
                                                            @if(!old('public_consultation_id') && $item->id && $item->public_consultation_id)
                                                                <option value="{{ $item->public_consultation_id }}" selected="selected">{{ $item->consultation->reg_num }} / {{ $item->consultation->title }}</option>
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
                                            @if($item->id)
                                                <div class="col-12 mt-4 ml-1">
                                                    <label class="col-sm-12 control-label">
                                                        {{ __('custom.change_docs') }}
                                                    </label>
                                                    <span class="text-danger" id="connect-doc-error"></span>
                                                </div>
                                                <div class="col-12 mb-5 ml-2">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <select id="legal_act_type_filter" name="legal_act_type_filter"  class="form-control form-control-sm select2 @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror">
                                                                <option value="0">{{ trans_choice('custom.legal_act_types', 1) }}</option>
                                                                @if(isset($legalActTypes) && $legalActTypes->count())
                                                                    @foreach($legalActTypes as $row)
                                                                        <option value="{{ $row->id }}" @if(old('legal_act_type_filter', '') == $row->id) selected @endif>{{ $row->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @error('legal_act_type_filter')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            @php($itemChangedDocIds = $item->changedDocs->pluck('id')->toArray())
                                                            <select id="change_docs" name="change_docs[]" multiple="multiple"  data-types2ajax="pris_doc" data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}" class="form-control form-control-sm select2-autocomplete-ajax @error('change_docs'){{ 'is-invalid' }}@enderror">
                                                                @if(isset($item->changedDocs) && $item->changedDocs->count())
                                                                    @foreach($item->changedDocs as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->doc_num.' ('.$row->actType->name.')' }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @error('change_docs')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select id="connect_type" name="connect_type" class="form-control form-control-sm select2 @error('connect_type'){{ 'is-invalid' }}@enderror">
                                                                <option value="0" @if(old('connect_type', 0) == 0) selected @endif>---</option>
                                                                @foreach(\App\Enums\PrisDocChangeTypeEnum::options() as $name => $val)
                                                                    <option value="{{ $val }}" @if(old('connect_type', 0) == $val) selected @endif>{{ __('custom.pris.change_enum.'.$name) }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('connect_type')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-outline-success" id="connect-documents" data-pris="{{ $item->id }}">{{ __('custom.add') }}</button>
                                                        </div>
                                                        @if($item->changedDocs->count())
                                                            <div class="col-12 mt-4" id="connected_documents">
                                                                @foreach($item->changedDocs as $pris)
                                                                    <div id="disconnect_{{ $pris->id }}">
                                                                        <a class="mr-2" href="{{ route('admin.pris.edit', $pris->id) }}" target="_blank">
                                                                           <i class="text-primary fas fa-link"></i>{{ $pris->pivot->old_connect_type ?? $pris->pivot->connect_type ? __('custom.pris.change_enum.'.\App\Enums\PrisDocChangeTypeEnum::keyByValue($pris->pivot->connect_type)) : ''  }} {{ $pris->actType->name }} №{{ $pris->regNum }} {{ $pris->docYear }} г.
                                                                        </a>
                                                                        <i class="text-danger fas fa-trash disconnect-document" data-pris="{{ $item->id }}" data-disconnect="{{ $pris->id }}" role="button"></i>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12"></div>
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
                            </div>
                        </div>
                        @if($item->id)
                            <div class="tab-pane fade" id="ct-files" role="tabpanel" aria-labelledby="ct-files-tab">
                                <form class="row" action="{{ route('admin.upload.file.languages', ['object_id' => $item->id, 'object_type' => \App\Models\File::CODE_OBJ_PRIS]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-6 mb-3">
                                        <label for="description_bg" class="form-label">Публично име (BG)<span class="required">*</span> </label>
                                        <input value="{{ old('description_bg', '') }}" class="form-control form-control-sm @error('description_bg') is-invalid @enderror" id="description_bg" type="text" name="description_bg">
                                        @error('description_bg')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="description_en" class="form-label">Публично име (EN) </label>
                                        <input value="{{ old('description_en', '') }}" class="form-control form-control-sm @error('description_en') is-invalid @enderror" id="description_en" type="text" name="description_en">
                                        @error('description_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="formats" value="ALLOWED_FILE_PRIS">
                                    <div class="col-md-6 mb-3">
                                        <label for="file_bg" class="form-label">Изберете файл (BG)<span class="required">*</span> </label>
                                        <input class="form-control form-control-sm @error('file_bg') is-invalid @enderror" id="file_bg" type="file" name="file_bg">
                                        @error('file_bg')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="file_en" class="form-label">Изберете файл (EN)</label>
                                        <input class="form-control form-control-sm @error('file_en') is-invalid @enderror" id="file_en" type="file" name="file_en">
                                        @error('file_en')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                    </div>
                                </form>

                                @if($item->files->count())
                                    <div class="row">
                                        @foreach($item->files as $f)
                                            <div class="mb-3">
                                                <a class="mr-3" href="{{ route('admin.download.file', $f) }}" target="_blank" title="{{ __('custom.download') }}">
                                                    {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }} - {{ __('custom.'.$f->locale) }} | {{ __('custom.version_short').' '.$f->version }} | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $f->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}">{{ __('custom.preview') }}</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            let errorContainer = $('#connect-doc-error');
            $('#connect-documents').on('click', function(){
                errorContainer.html('');
                $.ajax({
                    url  : '<?php echo route("admin.pris.connect"); ?>',
                    type : 'POST',
                    data : { _token: '{{ csrf_token() }}', id: $(this).data('pris'), connectIds: $('#change_docs').val(), connect_type: $('#connect_type').val() },
                    success : function(data) {
                        if( typeof data.error != 'undefined' ) {
                            errorContainer.html(data.message);
                        } else {
                            location.reload();
                        }
                    },
                    error : function() {
                        errorContainer.html('System error');
                    }
                });
            });

            $('.disconnect-document').on('click', function(){
                errorContainer.html('');
                let prisId = $(this).data('pris');
                let connectId = $(this).data('disconnect');
                $.ajax({
                    url  : '<?php echo route("admin.pris.disconnect"); ?>',
                    type : 'POST',
                    data : { _token: '{{ csrf_token() }}', id: prisId, disconnect: connectId },
                    success : function(data) {
                        if( typeof data.error != 'undefined' ) {
                            errorContainer.html(data.message);
                        } else {
                            console.log('#disconnect_' + connectId);
                            $('#disconnect_' + connectId).remove();
                        }
                    },
                    error : function() {
                        errorContainer.html('System error');
                    }
                });
            });
        });
    </script>
@endpush
