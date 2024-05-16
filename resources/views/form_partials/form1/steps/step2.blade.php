<div class="row">
    <div class="col-sm-12">
        <h4>4. {{ __('custom.forms1.variant_actions') }}:</h4>
    </div>
</div>

@include('form_partials.shared.variant', ['point' => 4])

<div class="row mt-5">
    <div class="col-sm-12">
        <h5>5. {{ __('custom.forms1.variant_compare') }}:</h5>
        <p>{{ __('custom.forms1.variant_compare_levels') }}</p>
        @include('form_partials.shared.comparison')
    </div>
</div>
