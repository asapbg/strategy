<table width="100%">
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
            @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 0, 'label' => 'forms.not_conducted_consultations'])
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