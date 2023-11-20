@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h6>{{ isset($point) ? $point : '1.3' }}.{{ $n+1 }}. По проблем {{ $n+1 }}:</h6>
    </div>
</div>
@php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-6">
        <h6>Вариант {{ $m+1 }}:</h6>
        @include('form_partials.textarea', ['name' => "variant_simple[$n][$m]", 'label' => '', 'value' => Arr::get($state, "variant_simple.$n.$m")])
    </div>
    <div class="col-sm-6">
        @if($m > 0)
        <div class="float-end">
            @include('form_partials.remove_button')
        </div>
        @endif
    </div>
</div>
@endfor
<div class="row">
    <div class="col-sm-12">
    @include('form_partials.add_button', ['name' => "variant_simple.$n", 'buttonLabel' => 'forms.variant'])
    </div>
</div>
@endfor
