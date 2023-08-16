<form id="ia-form" action="{{ route('impact_assessment.store', ['form' => $formName, 'step' => $step, 'inputId' => $inputId]) }}" method="POST">
    @csrf
    <input type="hidden" name="currentStep" value="{{ $step }}">
    @include("form_partials.$formName.steps.step$step")

<div class="row">
    <div class="col-sm-6">
        @if($step > 1)
        <button type="button" class="btn btn-primary" onclick="prevStep()">{{ __('custom.prev_step') }}</button>
        @endif
    </div>
    <div class="col-sm-6 text-end">
        @if($step < $steps)
        <button type="button" class="btn btn-primary" onclick="nextStep()">{{ __('custom.next_step') }}</button>
        @else
        <button type="button" class="btn btn-primary" onclick="submitForm()">{{ __('custom.submit_form') }}</button>
        @endif
    </div>
</div>
</form>

<script>
function prevStep() {
    $('#ia-form').attr('action', '{!! route('impact_assessment.store', ['form' => $formName, 'inputId' => $inputId, 'step' => $step-1]) !!}');
    $('#ia-form').submit();
}
function nextStep() {
    $('#ia-form').attr('action', '{!! route('impact_assessment.store', ['form' => $formName, 'inputId' => $inputId, 'step' => $step+1]) !!}');
    $('#ia-form').submit();
}
function submitForm() {
    $('#ia-form').attr('action', '{!! route('impact_assessment.store', ['form' => $formName, 'inputId' => $inputId, 'submit' => true]) !!}');
    $('#ia-form').submit();
}
</script>