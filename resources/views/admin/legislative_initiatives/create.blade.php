@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.legislative_initiatives.store') }}" method="post" name="form"
                          id="form">
                        @csrf

                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="regulatory_act_id">{{ trans_choice('custom.regulatory_acts', 1) }}<span
                                    class="required">*</span></label>
                            <div class="col-12">
                                <select id="regulatory_act_id" name="regulatory_act_id"
                                        class="form-control form-control-sm select2 @error('regulatory_act_id'){{ 'is-invalid' }}@enderror">
                                    <option value="">---</option>

                                    @if(isset($regulatoryActTypes) && $regulatoryActTypes->count())
                                        @foreach($regulatoryActTypes as $type)
                                            <option value="{{ $type->id }}"
                                                    @if(old('regulatory_act_id', ($type->id ? $type->regulatory_act_id : 0)) == $type->id) selected
                                                    @endif data-id="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @error('regulatory_act_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('admin.partial.edit_field_translate', ['field' => 'description', 'required' => true])

                        @include('admin.partial.edit_field_translate', ['field' => 'author', 'required' => true])

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.legislative_initiatives.index') }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
