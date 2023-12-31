@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>3.{{ $n+1 }}. По проблем {{ $n+1 }}:</h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">Варианти за действие</div>
    <div class="col-sm-4">Общи годишни разходи</div>
    <div class="col-sm-4">Общи годишни ползи</div>
</div>
@php($loop2 = Arr::get($state, "variants.$n", false) ? count(Arr::get($state, "variants.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-4">
        <h6>Вариант {{ $m+1 }}:</h6>
    </div>
    <div class="col-sm-4">
        @include('form_partials.textarea', ['name' => "expenses[$n][$m][expenses]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.expenses")])
    </div>
    <div class="col-sm-4">
        @include('form_partials.textarea', ['name' => "expenses[$n][$m][benefits]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.benefits")])
    </div>
</div>
@endfor
<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        <p><i>Опишете качествено и количествено всички значителни разходи и ползи на годишна база и коя от заинтересованите страни ще ги понесе. Използвайте приблизителни цифри и диапазони, включително парични разходи (в лв.).</i></p>
    </div>
</div>
@endfor