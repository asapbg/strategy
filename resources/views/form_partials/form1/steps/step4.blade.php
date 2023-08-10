<table width="100%">
    <tr>
        <td colspan="2">
            <h4>7. Консултации:</h4>
            @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 1, 'label' => 'forms.conducted_consultations', 'clickSubmit' => true])
            <br>
            @if(Arr::has($state, 'conducted_consultations') && data_get($state, 'conducted_consultations') == 1)
                @include('form_partials.textarea', ['name' => 'conducted_consultations_text', 'label' => ''])
                <p>
                    <i>
                        Посочете основните заинтересовани страни, с които са проведени консултации. Посочете резултатите от консултациите, включително на ниво ЕС: спорни въпроси, многократно поставяни въпроси и др.
                    </i>
                </p>
            @endif
            @include('form_partials.radio', ['name' => 'conducted_consultations', 'value' => 0, 'label' => 'forms.not_conducted_consultations', 'clickSubmit' => true])
            @if(Arr::has($state, 'conducted_consultations') && data_get($state, 'conducted_consultations') == 0)
            <br>
            @include('form_partials.textarea', ['name' => 'not_conducted_consultations_text', 'label' => ''])
            <p>
                <i>
                    Обобщете най-важните въпроси за обществени консултации. Посочете индикативен график за тяхното провеждане и видовете консултационни процедури.
                </i>
            </p>
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>8. Приемането на нормативния акт произтича ли от правото на Европейския съюз?</h4>   
            @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true])
            <br>
            @if(Arr::has($state, 'is_from_eu') && data_get($state, 'is_from_eu') == 1)
                @include('form_partials.textarea', ['name' => 'is_from_eu_text', 'label' => ''])
                <p>
                    <i>
                        1.1. Посочете изискванията на правото на Европейския съюз, включително информацията по т. 6.2 и 6.3, дали е извършена оценка на въздействието на ниво Европейски съюз, и я приложете (или посочете връзка към източник).
                        <br>
                        1.2. Изборът трябва да съответства на посоченото в раздел 1, съгласно неговата т. 1.5.
                    </i>
                </p>
                <br>
            @endif
            @include('form_partials.radio', ['name' => 'is_from_eu', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>9.  Изисква ли се извършване на цялостна предварителна оценка на въздействието поради очаквани значителни последици?</h4>   
            @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 1, 'label' => 'forms.yes'])
            <br>
            @include('form_partials.radio', ['name' => 'initial_assessment_required', 'value' => 0, 'label' => 'forms.no'])
            <p>
                <i>
                    (преценка съгласно чл. 20, ал. 3, т. 2 от Закона за нормативните актове)
                </i>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>10. Приложения:</h4>
            @include('form_partials.textarea', ['name' => 'applications', 'label' => '', 'placeholder' => 'forms.field_required'])
            <p>
                <i>
                    Приложете необходимата допълнителна информация и документи
                </i>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>11. Информационни източници:</h4>
            @include('form_partials.textarea', ['name' => 'info_sources', 'label' => '', 'placeholder' => 'forms.field_required'])
            <p>
                <i>
                    Посочете изчерпателен списък на информационните източници, които са послужили за оценка на въздействията на отделните варианти и при избора на вариант за действие: регистри, бази данни, аналитични материали и др.
                </i>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>12. Име, длъжност, дата и подпис на директора на дирекцията, отговорна за извършването на частичната предварителна оценка на въздействието:</h4>
            @include('form_partials.text', ['name' => 'name', 'label' => 'forms.name'])
            @include('form_partials.text', ['name' => 'job', 'label' => 'forms.job'])
            @include('form_partials.text', ['name' => 'date', 'label' => 'forms.date'])
        </td>
    </tr>
</table>