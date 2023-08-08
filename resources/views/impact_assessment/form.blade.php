<form action="{{ route('impact_assessment.store', ['form' => $formName, 'step' => $step]) }}" method="POST">
    @csrf
    
    @include("form_partials.$formName.steps.step$step")

    <table width="100%">
        <tr>
            <td>
                @if($step > 1)
                <button type="button" onclick="prevStep()">{{ __('custom.prev_step') }}</button>
                @endif
            </td>
            <td style="text-align: right;">
                @if($step < $steps)
                <button type="button" onclick="nextStep()">{{ __('custom.next_step') }}</button>
                @else
                <button type="button" onclick="submitForm()">{{ __('custom.submit_form') }}</button>
                @endif
            </td>
        </tr>
    </table>
</form>

<script>
function prevStep() {
    document.forms[0].action = '{{ route('impact_assessment.store', ['form' => $formName, 'step' => $step-1]) }}';
    document.forms[0].submit();
}
function nextStep() {
    document.forms[0].action = '{{ route('impact_assessment.store', ['form' => $formName, 'step' => $step+1]) }}';
    document.forms[0].submit();
}
function submitForm() {
    document.forms[0].action = '{!! route('impact_assessment.store', ['form' => $formName, 'inputId' => $inputId, 'submit' => true]) !!}';
    document.forms[0].submit();
}
</script>