@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>{{ isset($point) ? $point : '1.3' }}.{{ $n+1 }}. По проблем {{ $n+1 }}:</h5>
    </div>
</div>
@php($loop2 = Arr::get($state, "variants.$n", false) ? count(Arr::get($state, "variants.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row @if($m > 0) mt-5 @endif">
    <div class="col-sm-12">
        <h6>Вариант {{ $m+1 }}:</h6>
        <p>
            <i>
                1.1. Опишете качествено (при възможност – и количествено) всички значителни потенциални икономически, социални и екологични въздействия, включително върху всяка заинтересована страна/група заинтересовани страни. Пояснете кои въздействия се очаква да бъдат значителни и кои второстепенни.
                <br>
                1.2. Опишете специфичните въздействия с акцент върху малките и средните предприятия и административната тежест (задължения за информиране, такси, регулаторни режими, административни услуги и др.)
            </i>
        </p>
    </div>
{{--</div>--}}
{{--<div class="row">--}}
    <div class="col-sm-12">
        @include('form_partials.textarea', ['name' => "variants[$n][$m][description]", 'label' => 'forms.description', 'value' => Arr::get($state, "variants.$n.$m.description")])

        @include('form_partials.textarea', ['name' => "variants[$n][$m][positive_impact]", 'label' => 'forms.positive_impact', 'value' => Arr::get($state, "variants.$n.$m.positive_impact")])
        <p><i>(върху всяка заинтересована страна/група заинтересовани страни)</i></p>

        @include('form_partials.textarea', ['name' => "variants[$n][$m][negative_impact]", 'label' => 'forms.negative_impact', 'value' => Arr::get($state, "variants.$n.$m.negative_impact")])
        <p><i>(върху всяка заинтересована страна/група заинтересовани страни)</i></p>

        <h6>Специфични въздействия:</h6>
        @include('form_partials.textarea', ['name' => "variants[$n][$m][small_mid_impact]", 'label' => 'forms.small_mid_impact', 'value' => Arr::get($state, "variants.$n.$m.small_mid_impact")])

        @include('form_partials.textarea', ['name' => "variants[$n][$m][admin_weight]", 'label' => 'forms.admin_weight', 'value' => Arr::get($state, "variants.$n.$m.admin_weight")])
{{--        <p>--}}
{{--            <i>--}}
{{--                1.1. Опишете качествено (при възможност – и количествено) всички значителни потенциални икономически, социални и екологични въздействия, включително върху всяка заинтересована страна/група заинтересовани страни. Пояснете кои въздействия се очаква да бъдат значителни и кои второстепенни.--}}
{{--                <br>--}}
{{--                1.2. Опишете специфичните въздействия с акцент върху малките и средните предприятия и административната тежест (задължения за информиране, такси, регулаторни режими, административни услуги и др.)--}}
{{--            </i>--}}
{{--        </p>--}}
    </div>
</div>
@endfor
<div class="row">
    <div class="col-sm-12">
    @include('form_partials.add_array_button', ['name' => "variants.$n", 'buttonLabel' => 'forms.variant'])
    </div>
</div>
@endfor
