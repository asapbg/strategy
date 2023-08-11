<h3>3. Разходи и ползи от вариантите за действие</h3>
<table width="100%">
    @include('form_partials.shared.expenses')
</table>

<h3>4. Проведени консултации</h3>
@include('form_partials.textarea', ['name' => "consultations", 'label' => 'forms.consultations', 'value' => Arr::get($state, 'consultations')])
<p>
    <i>Посочете основните заинтересовани страни, с които са проведени консултации. Посочете резултатите от консултациите, включително на ниво ЕС: спорни въпроси, многократно поставяни въпроси и др.</i>
</p>

<h3>5. Привеждане в действие и изпълнение</h3>
<table width="100%">
    <tr>
        <td>{{ __('forms.effective_from') }}</td>
        <td>
            @include('form_partials.text', ['name' => 'effective_from', 'type' => 'date', 'label' => 'forms.effective_from', 'value' => Arr::get($state, 'effective_from')])
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h6>Коя институция ще отговаря за изпълнението на предложението и за контрола?</h6>
            @include('form_partials.textarea', ['name' => 'responsibility', 'value' => Arr::get($state, 'responsibility')])
            <p>
                <i>Посочете отговорната институция за изпълнението на предложението. Посочете дали предложението предвижда разходи за отговорната или друга институция?</i>
            </p>
        </td>
    </tr>
</table>

@include('form_partials.text', ['name' => 'name', 'label' => 'forms.name'])
@include('form_partials.text', ['name' => 'job', 'label' => 'forms.job'])
@include('form_partials.text', ['name' => 'date', 'label' => 'forms.date'])
