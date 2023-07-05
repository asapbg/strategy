@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        @if($item->id)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="consultation-category-select">{{ trans_choice('custom.consultation_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="consultation-category-select" name="consultation_category_id" class="form-control form-control-sm select2 @error('consultation_category_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($consultationCategories) && $consultationCategories->count())
                                        @foreach($consultationCategories as $row)
                                            <option value="{{ $row->id }}" @if(old('consultation_category_id', ($item->id ? $item->consultation_category_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('consultation_category_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="act-type-select">{{ trans_choice('custom.act_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="act-type-select" name="act_type_id" class="form-control form-control-sm select2 @error('act_type_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($actTypes) && $actTypes->count())
                                        @foreach($actTypes as $row)
                                            <option value="{{ $row->id }}" @if(old('act_type_id', ($item->id ? $item->act_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('act_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="title">{{ __('validation.attributes.title') }} <span class="required">*</span></label>
                            <input type="text" id="title" name="title"
                                class="form-control form-control-sm @error('title'){{ 'is-invalid' }}@enderror"
                                value="{{ old('title', ($item->id ? $item->translate(app()->getLocale())->title : '')) }}">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
