<div class="row">
    <div class="col-sm-12">
        <h3>3. Разходи и ползи от вариантите за действие</h3>
        @include('form_partials.shared.expenses')

        <h3>4. Проведени консултации</h3>
        @include('form_partials.textarea', ['name' => "consultations", 'label' => 'forms.consultations', 'value' => Arr::get($state, 'consultations')])
        <p>
            <i>Посочете основните заинтересовани страни, с които са проведени консултации. Посочете резултатите от консултациите, включително на ниво ЕС: спорни въпроси, многократно поставяни въпроси и др.</i>
        </p>

        <h3>5. Привеждане в действие и изпълнение</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        {{ __('forms.effective_from') }}
    </div>
    <div class="col-sm-6">
        @include('form_partials.text', ['name' => 'effective_from', 'type' => 'date', 'label' => 'forms.effective_from', 'value' => Arr::get($state, 'effective_from')])
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h6>Коя институция ще отговаря за изпълнението на предложението и за контрола?</h6>
        @include('form_partials.textarea', ['name' => 'responsibility', 'value' => Arr::get($state, 'responsibility')])
        <p>
            <i>Посочете отговорната институция за изпълнението на предложението. Посочете дали предложението предвижда разходи за отговорната или друга институция?</i>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('form_partials.text', ['name' => 'name', 'label' => 'forms.name'])
        @include('form_partials.text', ['name' => 'job', 'label' => 'forms.job'])
        @include('form_partials.text', ['name' => 'date', 'label' => 'forms.date'])
    </div>
</div>