@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])
                            
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="begin_date">{{ __('custom.begin_date') }} <span class="required">*</span></label>
                                    <input type="text" id="begin_date" name="begin_date" data-provide="datepicker" class="form-control form-control-sm"
                                    value="{{ old('begin_date', ($item->id ? $item->begin_date : '')) }}">
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="end_date">{{ __('custom.end_date') }} <span class="required">*</span></label>
                                    <input type="text" id="end_date" name="end_date" data-provide="datepicker" class="form-control form-control-sm"
                                    value="{{ old('end_date', ($item->id ? $item->end_date : '')) }}">
                                </div>
                                
                                @include('admin.partial.edit_single_translatable', ['field' => 'content', 'required' => true])
                                
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
                                        <a href="{{ route('admin.links.index') }}"
                                        class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h5>{{ trans_choice('custom.answers', 2) }}</h5>
                                <div class="form-group" id="answers-container">
                                    @foreach ($item->answers as $answer)
                                    <input type="text" name="answers[]" value="{{ $answer->title }}" class="form-control form-control-sm">
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success" onclick="AddAnswer()">
                                    <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.answers', 1) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function AddAnswer() {
        $('#answers-container').append('<input type="text" name="answers[]" class="form-control form-control-sm">');
    }
</script>
@endpush