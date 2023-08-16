<p>
    <b>
        Образецът на резюме на цялостна предварителна оценка на въздействието влиза в сила от 01 януари 2021 г.
    </b>
</p>
<h3>РЕЗЮМЕ НА ЦЯЛОСТНА ПРЕДВАРИТЕЛНА ОЦЕНКА НА ВЪЗДЕЙСТВИЕТО</h3>

<div class="row">
    <div class="col-sm-6">
        <h5>@include('form_partials.text', ['name' => 'institution'])</h5>
    </div>
    <div class="col-sm-6">
        <h5>@include('form_partials.text', ['name' => 'regulatory_act'])</h5>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <h5>@include('form_partials.text', ['name' => 'period_assessment'])</h5>
    </div>
    <div class="col-sm-6">
        <h5>
            От какво ниво възниква необходимостта от предприемане на действието?
        </h5>
        @include('form_partials.radio', ['name' => 'level', 'value' => 0, 'label' => 'forms.national'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 1, 'label' => 'forms.european'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 2, 'label' => 'forms.national_and_european'])
        @include('form_partials.radio', ['name' => 'level', 'value' => 3, 'label' => 'forms.international'])
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h5>@include('form_partials.text', ['name' => 'contact_person'])</h5>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <h5>@include('form_partials.text', ['name' => 'phone'])</h5>
    </div>
    <div class="col-sm-6">
        <h5>@include('form_partials.text', ['name' => 'email'])</h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h3>1. Проблеми, цели и варианти на действие</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>1.1 Проблем/проблеми за решаване:</h4>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('form_partials.shared.problems')
    </div>
</div>