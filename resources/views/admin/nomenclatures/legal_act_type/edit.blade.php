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

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name_single', 'required' => true])
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="in_pris">
                                <input type="checkbox" id="in_pris" name="in_pris" value="1"
                                       @if ($item->in_pris) checked @endif
                                       class="checkbox @error('in_pris'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.in_pris') }} <span class="required">*</span>
                            </label>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                        <br/>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
