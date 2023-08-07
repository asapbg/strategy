<table class="table" width="100%">
    <tr>
        <td>
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
            @include('form_partials.array_textarea', ['name' => 'problem_to_solve[]', 'buttonLabel' => 'custom.problem'])
            <p class="text-center">
                <i>1.1. Кратко опишете проблема/проблемите и причините за неговото/тяхното възникване. По възможност посочете числови стойности.<br>
                1.2. Посочете възможно ли е проблемът да се реши в рамките на съществуващото законодателство чрез промяна в организацията на работа и/или чрез въвеждане на нови технологични възможности (например съвместни инспекции между няколко органа и др.).<br>
                1.3. Посочете защо действащата нормативна рамка не позволява решаване на проблема/проблемите.<br>
                1.4. Посочете задължителните действия, произтичащи от нормативни актове от по-висока степен или актове от правото на ЕС.<br>
                1.5. Посочете дали са извършени последващи оценки на нормативния акт или анализи за изпълнението на политиката и какви са резултатите от тях? </i>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>2. {{ __('forms.goals') }}</h4>
            @include('form_partials.array_textarea', ['name' => 'goals[]', 'buttonLabel' => 'forms.goal'])
            <p class="text-center">
                <i>Посочете определените цели за решаване на проблема/проблемите, по възможно най-конкретен и измерим начин, включително индикативен график за тяхното постигане. Целите е необходимо да са насочени към решаването на проблема/проблемите и да съответстват на действащите стратегически документи.</i>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>3. {{ __('forms.interested_parties') }}</h4>
            @include('form_partials.array_textarea', ['name' => 'interested_parties[]', 'buttonLabel' => 'forms.interested_party'])
            <p class="text-center">
                <i>Посочете всички потенциални заинтересовани страни/групи заинтересовани страни (в рамките на процеса по извършване на частичната предварителна частична оценка на въздействието и/или при обществените консултации по чл. 26 от Закона за нормативните актове), върху които предложенията ще окажат пряко или косвено въздействие (бизнес в дадена област/всички предприемачи, неправителствени организации, граждани/техни представители, държавни органи/общини и др.).</i>
            </p>
        </td>
    </tr>
</table>