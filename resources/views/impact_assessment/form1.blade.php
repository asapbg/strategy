<p>
    <b>Образецът на частична предварителна оценка на въздействието влиза в сила от 01 януари 2021 г.</b>
</p>
<h3>Частична предварителна оценка на въздействието</h3>
<form action="{{ route('impact_assessment.store', ['form' => $formName]) }}" method="POST">
    @csrf
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
        <tr>
            <td colspan="2">
                <h4>4. Варианти на действие. Анализ на въздействията:</h4>
            </td>
        </tr>
        @include('form_partials.form1.variant')
        <tr>
            <td colspan="2">
                <h5>5. Сравняване на вариантите:</h5>
                <p>Степени на изпълнение по критерии: 1) висока; 2) средна; 3) ниска.</p>
                @include('form_partials.form1.comparison')
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6. Избор на препоръчителен вариант:</h4>
            </td>
        </tr>
        @php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
        @for($n=0; $n<$loop; $n++)
        <tr>
            <td>
                <h5>{{ __('forms.on_problem') . ' ' . $n+1 . ': ' . __('forms.variant') }}</h5>
            </td>
            <td>
                @include('form_partials.text', ['name' => 'chosen_variants[]', 'label' => ''])
            </td>
        </tr>
        @endfor
        <tr>
            <td colspan="2">
                <p class="text-center">
                    <i>Посочете препоръчителните варианти за решаване на поставения проблем/проблеми.</i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6.1. Промяна в административната тежест за физическите и юридическите лица от прилагането на препоръчителния вариант (включително по отделните проблеми):</h4>   
                @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 0, 'label' => 'forms.will_increase'])
                <br>
                @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 1, 'label' => 'forms.will_decrease'])
                <br>
                @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 2, 'label' => 'forms.no_effect'])
                <br>
                @include('form_partials.textarea', ['name' => 'change_admin_weight_text', 'label' => ''])
                <p class="text-center">
                    <i>
                        1.1. Изборът следва да е съотносим с посочените специфични въздействия на препоръчителния вариант за решаване на всеки проблем.
                        <br>
                        1.2. Ако се предвижда въвеждането на такса, представете образуването на нейния размер съгласно Методиката по чл. 7а от Закона за ограничаване на административното регулиране и административния контрол върху стопанската дейност.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6.2. Създават ли се нови/засягат ли се съществуващи регулаторни режими и услуги от прилагането на препоръчителния вариант (включително по отделните проблеми)?</h4>   
                @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 1, 'label' => 'forms.yes'])
                <br>
                @include('form_partials.textarea', ['name' => 'affects_regulatory_acts_text', 'label' => ''])
                <br>
                @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 0, 'label' => 'forms.no'])
                <p class="text-center">
                    <i>
                        1.1. Изборът следва да е съотносим с посочените специфични въздействия на избрания вариант.
                        <br>
                        1.2. В случай че се предвижда създаване нов регулаторен режим, посочете неговия вид (за стопанска дейност: лицензионен, регистрационен; за отделна стелка или действие: разрешителен, уведомителен; удостоверителен и по какъв начин това съответства с постигането на целите).
                        <br>
                        1.3. Мотивирайте създаването на новия регулаторен режим съгласно изискванията на чл. 3, ал. 4  от Закона за ограничаване на административното регулиране и административния контрол върху стопанската дейност.
                        <br>
                        1.4. Посочете предложените нови регулаторни режими отговарят ли на изискванията на чл. 10 – 12 от Закона за дейностите по предоставяне на услуги.
                        <br>
                        1.5. Посочете изпълнено ли е изискването на § 2 от Допълнителните разпоредби на Закона за дейностите по предоставяне на услуги.
                        <br>
                        1.6. В случай че се изменят регулаторни режими или административни услуги, посочете промяната.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6.3. Създават ли се нови регистри от прилагането на препоръчителния вариант (включително по отделните проблеми)?</h4>   
                @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 1, 'label' => 'forms.yes'])
                <br>
                @include('form_partials.textarea', ['name' => 'affects_registry_text', 'label' => ''])
                <br>
                @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 0, 'label' => 'forms.no'])
                <p class="text-center">
                    <i>
                        Когато отговорът е „Да“, посочете регистрите, които се създават и по какъв начин те ще бъдат интегрирани в общата регистрова инфраструктура.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6.4. По какъв начин препоръчителният вариант въздейства върху микро-, малките и средните предприятия (МСП) (включително по отделните проблеми)?</h4>   
                @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 1, 'label' => 'forms.act_affects_smc'])
                <br>
                @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 0, 'label' => 'forms.act_not_affects_smc'])
                <p class="text-center">
                    <i>
                        Изборът следва да е съотносим с посочените специфични въздействия на препоръчителния вариант.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>6.5. Потенциални рискове от прилагането на препоръчителния вариант (включително по отделните проблеми):</h4>
                @include('form_partials.textarea', ['name' => 'potential_risks', 'label' => ''])
                <p class="text-center">
                    <i>
                        Посочете възможните рискове от прилагането на препоръчителния вариант, различни от отрицателните въздействия, напр. възникване на съдебни спорове и др.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>7. Консултации:</h4>
                @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 1, 'label' => 'forms.conducted_consultations'])
                <br>
                @include('form_partials.textarea', ['name' => 'conducted_consultations_text', 'label' => ''])
                <p class="text-center">
                    <i>
                        Посочете основните заинтересовани страни, с които са проведени консултации. Посочете резултатите от консултациите, включително на ниво ЕС: спорни въпроси, многократно поставяни въпроси и др.
                    </i>
                </p>
                @include('form_partials.radio', ['name' => 'not_conducted_consultations', 'value' => 0, 'label' => 'forms.not_conducted_consultations'])
                <br>
                @include('form_partials.textarea', ['name' => 'not_conducted_consultations_text', 'label' => ''])
                <p class="text-center">
                    <i>
                        Обобщете най-важните въпроси за обществени консултации. Посочете индикативен график за тяхното провеждане и видовете консултационни процедури.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>8. Приемането на нормативния акт произтича ли от правото на Европейския съюз?</h4>   
                @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 1, 'label' => 'forms.yes'])
                <br>
                @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 0, 'label' => 'forms.no'])
                <br>
                @include('form_partials.textarea', ['name' => 'is_from_eu_text', 'label' => ''])
                <p class="text-center">
                    <i>
                        1.1. Посочете изискванията на правото на Европейския съюз, включително информацията по т. 6.2 и 6.3, дали е извършена оценка на въздействието на ниво Европейски съюз, и я приложете (или посочете връзка към източник).
                        <br>
                        1.2. Изборът трябва да съответства на посоченото в раздел 1, съгласно неговата т. 1.5.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>9.  Изисква ли се извършване на цялостна предварителна оценка на въздействието поради очаквани значителни последици?</h4>   
                @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 1, 'label' => 'forms.yes'])
                <br>
                @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 0, 'label' => 'forms.no'])
                <p class="text-center">
                    <i>
                        (преценка съгласно чл. 20, ал. 3, т. 2 от Закона за нормативните актове)
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>10. Приложения:</h4>
                @include('form_partials.textarea', ['name' => 'applications', 'label' => ''])
                <p class="text-center">
                    <i>
                        Приложете необходимата допълнителна информация и документи
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>11. Информационни източници:</h4>
                @include('form_partials.textarea', ['name' => 'info_sources', 'label' => ''])
                <p class="text-center">
                    <i>
                        Посочете изчерпателен списък на информационните източници, които са послужили за оценка на въздействията на отделните варианти и при избора на вариант за действие: регистри, бази данни, аналитични материали и др.
                    </i>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>12. Име, длъжност, дата и подпис на директора на дирекцията, отговорна за извършването на частичната предварителна оценка на въздействието:</h4>
                @include('form_partials.text', ['name' => 'name_and_job', 'label' => 'forms.name_and_job'])
                @include('form_partials.text', ['name' => 'date', 'label' => 'forms.date'])
            </td>
        </tr>
    </table>
    
    <button type="submit">{{ __('custom.save') }}</button>
</form>