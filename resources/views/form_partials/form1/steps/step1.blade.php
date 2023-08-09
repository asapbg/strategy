<p>
    <b>Образецът на частична предварителна оценка на въздействието влиза в сила от 01 януари 2021 г.</b>
</p>
<h3>Частична предварителна оценка на въздействието</h3>
<table class="table" width="100%">
    <tr>
        <td width="50%">
            @include('form_partials.text', ['name' => 'institution'])
        </td>
        <td>
            @include('form_partials.text', ['name' => 'regulatory_act'])
        </td>
    </tr>
    <tr>
        <td>
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 0, 'label' => 'forms.not_included_in_program'])
        </td>
        <td>
            @include('form_partials.radio', ['name' => 'included_in_program', 'value' => 0, 'label' => 'forms.included_in_program'])
        </td>
    </tr>
    <tr>
        <td>
            @include('form_partials.text', ['name' => 'contact_person'])
        </td>
        <td>
            @include('form_partials.text', ['name' => 'phone_and_email'])
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>1. {{ __('forms.problem_to_solve') }}</h4>
            @include('form_partials.shared.problems')
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>2. {{ __('forms.goal') . '/' . __('forms.goals') }}</h4>
            @include('form_partials.shared.goals')
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>3. {{ __('forms.interested_parties') }}</h4>
            @include('form_partials.array_textarea_number', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party'])
            <p class="text-center">
                <i>Посочете всички потенциални заинтересовани страни/групи заинтересовани страни (в рамките на процеса по извършване на частичната предварителна частична оценка на въздействието и/или при обществените консултации по чл. 26 от Закона за нормативните актове), върху които предложенията ще окажат пряко или косвено въздействие (бизнес в дадена област/всички предприемачи, неправителствени организации, граждани/техни представители, държавни органи/общини и др.).</i>
            </p>
        </td>
    </tr>
</table>