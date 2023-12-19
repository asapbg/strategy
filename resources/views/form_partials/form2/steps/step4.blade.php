<div class="row">
    <div class="col-sm-12">
        <h4>3. Разходи и ползи от вариантите за действие</h4>
        @include('form_partials.shared.expenses')

        <h4>4. Проведени консултации</h4>
        @include('form_partials.textarea', ['name' => "consultations", 'label' => 'forms.consultations', 'value' => Arr::get($state, 'consultations')])
        <p>
            <i>Посочете основните заинтересовани страни, с които са проведени консултации. Посочете резултатите от консултациите, включително на ниво ЕС: спорни въпроси, многократно поставяни въпроси и др.</i>
        </p>

        <h4 class="mt-5">5. Привеждане в действие и изпълнение</h4>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        @include('form_partials.text', ['name' => 'effective_from', 'type' => 'text', 'label' => 'forms.effective_from', 'value' => Arr::get($state, 'effective_from'), 'class' => 'datepicker'])
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
<div class="row mb-3">
    <div class="col-sm-12">
        <div class="col-md-4">
            @include('form_partials.text', ['name' => 'name', 'label' => 'forms.name'])
        </div>
        <div class="col-md-4">
            @include('form_partials.text', ['name' => 'job', 'label' => 'forms.job'])
        </div>
        <div class="col-md-4">
            @include('form_partials.date', ['name' => 'date', 'label' => 'forms.date'])
        </div>

    </div>
</div>
