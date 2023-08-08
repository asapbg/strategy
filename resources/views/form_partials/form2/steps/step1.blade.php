<p>
    <b>
        Образецът на резюме на цялостна предварителна оценка на въздействието влиза в сила от 01 януари 2021 г.
    </b>
</p>
<h3>РЕЗЮМЕ НА ЦЯЛОСТНА ПРЕДВАРИТЕЛНА ОЦЕНКА НА ВЪЗДЕЙСТВИЕТО</h3>
<table class="table" width="100%">
    <tr>
        <td width="50%">
            @include('form_partials.select', ['name' => 'institution', 'options' => $institutions])
        </td>
        <td>
            @include('form_partials.select', ['name' => 'regulatory_act', 'options' => $regulatoryActs])
        </td>
    </tr>
    <tr>
        <td>
            @include('form_partials.text', ['name' => 'period_assessment'])
        </td>
        <td>
            @include('form_partials.radio', ['name' => 'level', 'value' => 0, 'label' => 'forms.national'])
            @include('form_partials.radio', ['name' => 'level', 'value' => 1, 'label' => 'forms.european'])
            @include('form_partials.radio', ['name' => 'level', 'value' => 2, 'label' => 'forms.national_and_european'])
            @include('form_partials.radio', ['name' => 'level', 'value' => 3, 'label' => 'forms.international'])
            <p>
                <i>От какво ниво възниква необходимостта от предприемане на действието?</i>
            </p>
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
</table>