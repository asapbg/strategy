<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ route('admin.ogp.plan.add_area', $item->id) }}">
            @csrf
            <div class="form-group">
                <label class="col-sm-12 control-label" for="ogp_area_id">{{ trans_choice('custom.area', 1) }} <span class="required">*</span></label>
                <div class="col-12">
                    <select name="ogp_area" id="ogp_area" class="form-select @error('ogp_area'){{ 'is-invalid' }}@enderror">
                        <option value=""></option>
                        @foreach($ogpArea as $v)
                            <option value="{{ $v->id }}" @if(old('ogp_area', 0) == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                        @endforeach
                    </select>
                    @error('ogp_area')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <button id="save" type="submit" class="btn btn-success">Добави</button>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <form action="{{ route('admin.ogp.plan.'.($item->id ? "edit" : "create").'_store') }}" method="post" name="form" id="form">
            @csrf
            @if($item->id)
                @method('PUT')
            @endif
            <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

            <div class="row mb-4">
                @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
            </div>
            <div class="row mb-4">
                @include('admin.partial.edit_field_translate', ['field' => 'content', 'required' => true])
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="from_date">{{ __('custom.from_date') }} <span class="required">*</span></label>
                        <div class="col-12">
                            <div class="input-group">
                                <input type="text" id="from_date" name="from_date" class="form-control form-control-sm datepicker @error('from_date'){{ 'is-invalid' }}@enderror" value="{{ old('to_date', displayDate($item->to_date) ?? '') }}" autocomplete="off">
                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                            </div>
                            @error('from_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="to_date">{{ __('custom.to_date') }} <span class="required">*</span></label>
                        <div class="col-12">
                            <div class="input-group">
                                <input type="text" id="to_date" name="to_date" class="form-control form-control-sm datepicker @error('to_date'){{ 'is-invalid' }}@enderror" value="{{ old('to_date', displayDate($item->to_date) ?? '') }}" autocomplete="off">
                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-solid fa-calendar"></i></span>
                            </div>
                            @error('to_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    @include('admin.partial.active_field')
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 col-md-offset-3">
                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                    <a href="{{ route('admin.ogp.plan.index') }}"
                       class="btn btn-primary">{{ __('custom.cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

