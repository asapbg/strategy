<div class="activity row">
    <div class="form-group col-12  mb-3">
        <label class="border-start border-4 border-primary px-2 mb-2 w-100">
            {{ __('validation.attributes.activity_name') }}
            <i class="text-danger fas fa-times-circle remove-activity" role="button"></i>
        </label>
        <div class="col-12">
            <input type="text" name="items[]" class="form-control @if(isset($oldInputs['item']['key'])) @error('items.'.$oldInputs['item']['key']) is-invalid @enderror @endif" value="{{ isset($oldInputs) && isset($oldInputs['item']['val']) ? $oldInputs['item']['val'] : ''}}" />
            @if(isset($oldInputs['item']['key']))
                @error('items.'.$oldInputs['item']['key'])
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
            <i class="fa fa-info-circle text-primary me-2" data-toggle="tooltip" data-placement="right"
               title="{{ __('site.calc.standard.hours.tooltip') }}"></i>
            {{ __('site.calc.standard.hours') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['hours']['key'])) @error('hours.'.$oldInputs['hours']['key']) is-invalid @enderror @endif" name="hours[]" value="{{ isset($oldInputs) && isset($oldInputs['hours']['val']) ? $oldInputs['hours']['val'] : ''}}">
        @if(isset($oldInputs['hours']['key']))
            @error('hours.'.$oldInputs['hours']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
            <i class="fa fa-info-circle text-primary me-2" data-toggle="tooltip" data-placement="right"
               title="{{ __('site.calc.standard.salary.tooltip') }}"></i>
            {{ __('site.calc.standard.salary') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['salary']['key'])) @error('salary.'.$oldInputs['salary']['key']) is-invalid @enderror @endif" name="salary[]" value="{{ isset($oldInputs) && isset($oldInputs['salary']['val']) ? $oldInputs['salary']['val'] : ''}}">
        @if(isset($oldInputs['salary']['key']))
            @error('salary.'.$oldInputs['salary']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
{{--            <i class="fa fa-info-circle text-primary me-2" data-toggle="tooltip" data-placement="right" title="{{ __('site.calc.standard.firms.tooltip') }}"></i>--}}
            {{ __('site.calc.standard.firms') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['firms']['key'])) @error('firms.'.$oldInputs['firms']['key']) is-invalid @enderror @endif" name="firms[]" value="{{ isset($oldInputs) && isset($oldInputs['firms']['val']) ? $oldInputs['firms']['val'] : ''}}">
        @if(isset($oldInputs['firms']['key']))
            @error('firms.'.$oldInputs['firms']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
{{--            <i class="fa fa-info-circle text-primary me-2" data-toggle="tooltip" data-placement="right" title="{{ __('site.calc.standard.per_year.tooltip') }}"></i>--}}
            {{ __('site.calc.standard.per_year') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['per_year']['key'])) @error('per_year.'.$oldInputs['per_year']['key']) is-invalid @enderror @endif" name="per_year[]" value="{{ isset($oldInputs) && isset($oldInputs['per_year']['val']) ? $oldInputs['per_year']['val'] : ''}}">
        @if(isset($oldInputs['per_year']['key']))
            @error('per_year.'.$oldInputs['per_year']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
    <div class="form-group">
        <input type="text" name="result[]" class="form-control" value="{{ isset($oldInputs) && isset($oldInputs['result']['val']) ? $oldInputs['result']['val'] : ''}}" disabled readonly placeholder="{{ trans_choice('custom.results', 1).':' }}"/>
    </div>
    <hr class="mt-3">
</div>
