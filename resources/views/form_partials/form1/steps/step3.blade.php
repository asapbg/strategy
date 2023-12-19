<div class="row">
    <div class="col-sm-12">
        <h4>6. Избор на препоръчителен вариант:</h4>
    </div>
</div>
@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
<div class="row">
    <div class="col-sm-12">
        <p>
            <i>Посочете препоръчителните варианти за решаване на поставения проблем/проблеми.</i>
        </p>
    </div>
</div>
@for($n=0; $n<$loop; $n++)
    <div class="row @if($n) mt-3 @endif">
        <div class="col-md-3">
            <h5>{{ __('forms.on_problem') . ' ' . $n+1 . ': ' . __('forms.variant') }}</h5>
        </div>
        <div class="col-md-9">
            @include('form_partials.text', ['name' => 'chosen_variants[]', 'label' => '', 'nameDots' => "chosen_variants.$n", 'value' => old("chosen_variants.$n", data_get($state, "chosen_variants.$n"))])
        </div>
    </div>
@endfor
<div class="row mb-5">
    <div class="col-sm-12 mt-5">
        <h4>6.1. Промяна в административната тежест за физическите и юридическите лица от прилагането на препоръчителния вариант (включително по отделните проблеми):</h4>
        <p>
            <i>
                1.1. Изборът следва да е съотносим с посочените специфични въздействия на препоръчителния вариант за решаване на всеки проблем.
                <br>
                1.2. Ако се предвижда въвеждането на такса, представете образуването на нейния размер съгласно Методиката по чл. 7а от Закона за ограничаване на административното регулиране и административния контрол върху стопанската дейност.
            </i>
        </p>
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 0, 'label' => 'forms.will_increase', 'clickSubmit' => true])
        <br>
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 1, 'label' => 'forms.will_decrease', 'clickSubmit' => true])
        <br>
        @include('form_partials.radio', ['name' => 'change_admin_weight', 'value' => 2, 'label' => 'forms.no_effect', 'clickSubmit' => true])
        @if((Arr::has($state, 'change_admin_weight') && in_array(data_get($state, 'change_admin_weight'), [0, 1])) || in_array(old('change_admin_weight'), [0,1]))
            <br>
            @include('form_partials.textarea', ['name' => 'change_admin_weight_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.2. Създават ли се нови/засягат ли се съществуващи регулаторни режими и услуги от прилагането на препоръчителния вариант (включително по отделните проблеми)?</h4>
        <p>
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
        <div>
            @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true, 'class' => 'me-3'])
            @include('form_partials.radio', ['name' => 'affects_regulatory_acts', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
        </div>

        @if((Arr::has($state, 'affects_regulatory_acts') && data_get($state, 'affects_regulatory_acts') == 1) || old('affects_regulatory_acts') == 1)
            <br>
            @include('form_partials.textarea', ['name' => 'affects_regulatory_acts_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.3. Създават ли се нови регистри от прилагането на препоръчителния вариант (включително по отделните проблеми)?</h4>
        <p>
            <i>
                Когато отговорът е „Да“, посочете регистрите, които се създават и по какъв начин те ще бъдат интегрирани в общата регистрова инфраструктура.
            </i>
        </p>
        <div>
            @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 1, 'label' => 'forms.yes', 'clickSubmit' => true, 'class' => 'me-3'])
            @include('form_partials.radio', ['name' => 'affects_registry', 'value' => 0, 'label' => 'forms.no', 'clickSubmit' => true])
        </div>

        @if((Arr::has($state, 'affects_registry') && data_get($state, 'affects_registry') == 1)  || old('affects_registry') == 1)
            <br>
            @include('form_partials.textarea', ['name' => 'affects_registry_text', 'label' => '', 'class' => 'mt-3'])
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12  mt-4">
        <h4>6.4. По какъв начин препоръчителният вариант въздейства върху микро-, малките и средните предприятия (МСП) (включително по отделните проблеми)?</h4>
        <p>
            <i>
                Изборът следва да е съотносим с посочените специфични въздействия на препоръчителния вариант.
            </i>
        </p>
        @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 1, 'label' => 'forms.act_affects_smc'])
        <br>
        @include('form_partials.radio', ['name' => 'affects_companies', 'value' => 0, 'label' => 'forms.act_not_affects_smc'])
    </div>
</div>
<div class="row mb-5">
    <div class="col-sm-12">
        <h4>6.5. Потенциални рискове от прилагането на препоръчителния вариант (включително по отделните проблеми):</h4>
        <p>
            <i>
                Посочете възможните рискове от прилагането на препоръчителния вариант, различни от отрицателните въздействия, напр. възникване на съдебни спорове и др.
            </i>
        </p>
        @include('form_partials.textarea', ['name' => 'potential_risks', 'label' => ''])
    </div>
</div>
