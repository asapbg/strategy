@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="regulatory_act_id">{{ trans_choice('custom.regulatory_acts', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="regulatory_act_id" name="regulatory_act_id" class="form-control form-control-sm select2 @error('regulatory_act_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($regulatoryActs) && $regulatoryActs->count())
                                    @foreach($regulatoryActs as $row)
                                    <option value="{{ $row->id }}" @if(old('regulatory_act_id', ($item->id ? $item->regulatory_act_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('regulatory_act_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('admin.partial.edit_single_translatable', ['field' => 'description', 'required' => true])
                    
                        @include('admin.partial.edit_single_translatable', ['field' => 'author', 'required' => true])

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
                                <a href="{{ route('admin.links.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
