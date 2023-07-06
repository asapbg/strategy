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
                            <label class="col-sm-12 control-label" for="consultation-type-select">{{ trans_choice('custom.consultation_type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="consultation-type-select" name="consultation_type_id" class="form-control form-control-sm select2 @error('consultation_type_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($consultationTypes) && $consultationTypes->count())
                                    @foreach($consultationTypes as $row)
                                    <option value="{{ $row->id }}" @if(old('consultation_type_id', ($item->id ? $item->consultation_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('consultation_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="program-projectselect">{{ trans_choice('custom.consultation_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="program-projectselect" name="consultation_category_id" class="form-control form-control-sm select2 @error('consultation_category_id'){{ 'is-invalid' }}@enderror">
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
                            <label class="col-sm-12 control-label" for="program-projectselect">{{ trans_choice('custom.program_project', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="program-projectselect" name="program_project_id" class="form-control form-control-sm select2 @error('program_project_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($programProjects) && $programProjects->count())
                                        @foreach($programProjects as $row)
                                            <option value="{{ $row->id }}" @if(old('program_project_id', ($item->id ? $item->program_project_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('program_project_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="link-category-select">{{ trans_choice('custom.link_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="link-category-select" name="link_category_id" class="form-control form-control-sm select2 @error('link_category_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($linkCategories) && $linkCategories->count())
                                        @foreach($linkCategories as $row)
                                            <option value="{{ $row->id }}" @if(old('link_category_id', ($item->id ? $item->link_category_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('link_category_id')
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

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="description">{{ __('validation.attributes.description') }} <span class="required">*</span></label>
                            <textarea id="description" name="description" rows="8"
                                class="ckeditor form-control form-control-sm @error('description'){{ 'is-invalid' }}@enderror">{{ old('description', ($item->id ? $item->translate(app()->getLocale())->description : '')) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label class="col-sm-12 control-label" for="open_from">{{ __('validation.attributes.open_from') }} <span class="required">*</span></label>
                                <input type="date" id="open_from" name="open_from"
                                    class="form-control form-control-sm @error('open_from'){{ 'is-invalid' }}@enderror"
                                    value="{{ old('open_from', ($item->id ? $item->translate(app()->getLocale())->open_from : '')) }}">
                            </div>

                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label" for="open_to">{{ __('validation.attributes.open_to') }} <span class="required">*</span></label>
                                <input type="date" id="open_to" name="open_to"
                                class="form-control form-control-sm @error('open_to'){{ 'is-invalid' }}@enderror"
                                value="{{ old('open_to', ($item->id ? $item->translate(app()->getLocale())->open_to : '')) }}">
                            </div>
                            
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">{{ __('custom.diff_days') }}</label>
                                <p>
                                    <span id="period-total"></span>
                                    дни
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="shortTermReason">{{ __('validation.attributes.shortTermReason') }} <span class="required">*</span></label>
                            <input type="text" id="shortTermReason" name="shortTermReason"
                                class="form-control form-control-sm @error('shortTermReason'){{ 'is-invalid' }}@enderror"
                                value="{{ old('shortTermReason', ($item->id ? $item->translate(app()->getLocale())->shortTermReason : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="responsibleUnit">{{ __('validation.attributes.responsibleUnit') }} <span class="required">*</span></label>
                            <input type="text" id="responsibleUnit" name="responsibleUnit"
                                class="form-control form-control-sm @error('responsibleUnit'){{ 'is-invalid' }}@enderror"
                                value="{{ old('responsibleUnit', ($item->id ? $item->translate(app()->getLocale())->responsibleUnit : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="responsiblePerson">{{ __('validation.attributes.shortTermReason') }} <span class="required">*</span></label>
                            <input type="text" id="responsiblePerson" name="responsiblePerson"
                                class="form-control form-control-sm @error('responsiblePerson'){{ 'is-invalid' }}@enderror"
                                value="{{ old('responsiblePerson', ($item->id ? $item->translate(app()->getLocale())->responsiblePerson : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="address">{{ __('validation.attributes.address') }} <span class="required">*</span></label>
                            <input type="text" id="address" name="address"
                                class="form-control form-control-sm @error('address'){{ 'is-invalid' }}@enderror"
                                value="{{ old('address', ($item->id ? $item->translate(app()->getLocale())->address : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="email">{{ __('validation.attributes.email') }} <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control form-control-sm @error('email'){{ 'is-invalid' }}@enderror"
                                value="{{ old('email', ($item->id ? $item->translate(app()->getLocale())->email : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="phone">{{ __('validation.attributes.phone') }} <span class="required">*</span></label>
                            <input type="text" id="phone" name="phone"
                                class="form-control form-control-sm @error('phone'){{ 'is-invalid' }}@enderror"
                                value="{{ old('phone', ($item->id ? $item->translate(app()->getLocale())->phone : '')) }}">
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

@push('scripts')
<script>
    $(document).ready(function () {
        $('#open_from, #open_to').on('change', onDateChange);
        onDateChange();
    });

    function onDateChange() {
        const date1 = new Date($('#open_from').val());
        const date2 = new Date($('#open_to').val());
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        $('#period-total').text(diffDays ? diffDays : 0);
    }
</script>
@endpush