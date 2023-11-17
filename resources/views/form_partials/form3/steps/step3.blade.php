<h4>5. Анализ на въздействията</h4>

<h5>5.1. Определяне на икономическите, социални и екологични въздействия</h5>
<p><i>Анализира се въздействието на всеки отделен вариант за решаването на всеки отделен проблем. (Ръководство, РМС № 728 от 2019 г., стр. 27 - 28).]</i></p>
@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h6 class="mt-3">5.1.{{ $n+1 }}. Проблем {{ $n+1 }}:</h6>
    </div>
</div>
<table width="100%">
<thead>
    <tr>
        <td></td>
        <td class="text-center">{{ __('forms.economic_impacts') }}</td>
        <td class="text-center">{{ __('forms.social_impact') }}</td>
        <td class="text-center">{{ __('forms.ecologic_impact') }}</td>
        <td class="text-center">{{ __('forms.specific_impact') . ' 1' }}</td>
        <td class="text-center">{{ __('forms.specific_impact') . ' 2' }}</td>
    </tr>
</thead>
@php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<tr>
    <td>
        <h6>Вариант {{ $m+1 }}:</h6>
    </td>
    <td class="px-4">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][economic_impacts]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.description")])
    </td>
    <td class="px-4">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][social_impact]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.positive_impact")])
    </td>
    <td class="px-4">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][ecologic_impact]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.negative_impact")])
    </td>
    <td class="px-4">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][specific_impact_1]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.small_mid_impact")])
    </td>
    <td class="px-4">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][specific_impact_2]", 'label' => '', 'value' => Arr::get($state, "variants.$n.$m.admin_weight")])
    </td>
</tr>
@endfor
</table>
@endfor

<h4 class="mt-4">5.2. Качествена оценка на по-значимите въздействия и специфичните им аспекти</h4>
<p><i>1. да се идентифицират областите, в които предлаганото действие трябва да доведе до ползи, както и областите, където то може да доведе до преки разходи или непредвидени отрицателни въздействия
<br>
2. да се установи скалата с ниска, средна или висока вероятност въздействието да се прояви, включително чрез извеждането на предположения относно факторите, които са извън контрола на лицата управляващи интервенцията , които могат да повлияят на тези вероятности
<br>
3. да се оценят и прогнозират величините на всяко въздействие чрез представяне на конкретни стойности или диапазони като се вземе предвид влиянието на интервенцията върху поведението на адресатите в социално-икономически и екологичен контекст
<br>
4. да се оцени значението на въздействията въз основа на двата предходни елемента (Ръководство, РМС № 728 от 2019 г., стр. 36).]
</i></p>
@include('form_partials.textarea', ['name' => 'quality_assessment', 'label' => '', 'value' => Arr::get($state, 'quality_assessment'), 'class' => 'mb-3'])
