@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>2.{{ $n+1 }}. По проблем {{ $n+1 }}:</h5>
    </div>
</div>
@php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-12">
        <h6>Вариант {{ $m+1 }}:</h6>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][description]", 'label' => 'forms.description', 'value' => Arr::get($state, "variants.$n.$m.description")])

        @include('form_partials.textarea', ['name' => "variants[$n][$m][positive_impact]", 'label' => 'forms.positive_impact_3years', 'value' => Arr::get($state, "variants.$n.$m.positive_impact")])
        <p><i>Опишете накратко най-важните парични и непарични изрази на положителните въздействия, включително върху всяка заинтересована страна/група заинтересовани страни.</i></p>
        
        @include('form_partials.textarea', ['name' => "variants[$n][$m][negative_impact]", 'label' => 'forms.negative_impact_3years', 'value' => Arr::get($state, "variants.$n.$m.negative_impact")])
        <p><i>Опишете накратко най-важните парични и непарични изрази на отрицателните въздействия, включително върху всяка заинтересована страна/група заинтересовани страни.</i></p>
        
        <h6>Специфични въздействия в тригодишна перспектива</h6>
        @include('form_partials.textarea', ['name' => "variants[$n][$m][small_mid_impact]", 'label' => 'forms.small_mid_impact', 'value' => Arr::get($state, "variants.$n.$m.small_mid_impact")])
        <p><i>
            Посочете дали има микро-, малки или средни предприятия, които са изключени от новите правила, въведени с предложението.
            <br>
            Посочете разпределението на разходите между микро-, малките и средните предприятия.
        </i></p>
        
        @include('form_partials.textarea', ['name' => "variants[$n][$m][admin_weight]", 'label' => 'forms.admin_weight', 'value' => Arr::get($state, "variants.$n.$m.admin_weight")])
        <p><i>
            Посочете промяната в административната тежест за заинтересованите страни.
            <br>
            Посочете дали се създават нови регулаторни режими или регистри, както и дали се засягат съществуващи регулаторни режими и регистри.
            <br>
            Посочете дали предложението надхвърля минималните изисквания за административна тежест на ЕС.
        </i></p>

        @include('form_partials.textarea', ['name' => "variants[$n][$m][risks]", 'label' => 'forms.potential_risks', 'value' => Arr::get($state, "variants.$n.$m.risks")])
        <p><i>
            Посочете дали има микро-, малки или средни предприятия, които са изключени от новите правила, въведени с предложението.
            <br>
            Посочете разпределението на разходите между микро-, малките и средните предприятия.
        </i></p>
    </div>
</div>
@endfor
@endfor