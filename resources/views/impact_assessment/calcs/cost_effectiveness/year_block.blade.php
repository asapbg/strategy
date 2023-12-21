<div class="year row">
    <div class="form-group col-12  mb-3">
        <label class="border-start border-4 border-primary px-2 mb-2 w-100">
            @php($js = !isset($oldInputs))
            @php($yearNum = isset($oldInputs['year']) && isset($oldInputs['year']['key']) ?  ($oldInputs['year']['key'] + 1) : 0)
            {{ __('validation.attributes.year') }} #<span class="year-num">@if($yearNum && !$js) {{ $yearNum }} @else <script>$('.year-num').last().html($('.year').length);</script> @endif</span>
            <i class="text-danger fas fa-times-circle remove-year" role="button"></i>
        </label>
        <input type="hidden" value="{{ isset($oldInputs['year']) && isset($oldInputs['year']['val']) ? $oldInputs['year']['val'] : 1 }}" name="year[]">
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
            <i class="fa fa-info-circle text-primary me-2"></i>
            {{ __('site.calc.cost_and_benefits.regular_incoming') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['incoming']['key'])) @error('incoming.'.$oldInputs['incoming']['key']) is-invalid @enderror @endif" name="incoming[]" value="{{ isset($oldInputs) && isset($oldInputs['incoming']['val']) ? $oldInputs['incoming']['val'] : 0}}">
        @if(isset($oldInputs['incoming']['key']))
            @error('incoming.'.$oldInputs['incoming']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
    <div class="form-group col-md-6 mb-3">
        <label>
            <i class="fa fa-info-circle text-primary me-2"></i>
            {{ __('site.calc.cost_and_benefits.regular_costs') }}
        </label>
        <input type="number" class="form-control @if(isset($oldInputs['costs']['key'])) @error('costs.'.$oldInputs['costs']['key']) is-invalid @enderror @endif" name="costs[]" value="{{ isset($oldInputs) && isset($oldInputs['costs']['val']) ? $oldInputs['costs']['val'] : 0}}">
        @if(isset($oldInputs['costs']['key']))
            @error('costs.'.$oldInputs['costs']['key'])
            <div class="text-danger">{{ $message }}</div>
            @enderror
        @endif
    </div>
{{--    <div class="form-group">--}}
{{--        <input type="text" name="result[]" class="form-control" value="{{ isset($oldInputs) && isset($oldInputs['result']['val']) ? $oldInputs['result']['val'] : ''}}" disabled readonly placeholder="{{ trans_choice('custom.results', 1).':' }}"/>--}}
{{--    </div>--}}
    <hr class="mt-3">
</div>
