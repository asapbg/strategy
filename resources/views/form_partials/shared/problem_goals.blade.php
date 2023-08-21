@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<div class="row">
    <div class="col-sm-12">
        <h5>Проблем {{ $n+1 }}:</h5>
    </div>
</div>
@php($loop2 = Arr::get($state, "goals.$n", false) ? count(Arr::get($state, "goals.$n")) : 1)
@for($m=0; $m<$loop2; $m++)
<div class="row">
    <div class="col-sm-6">
        <h6>{{ __('forms.goal_goals') .' '. $m+1 }}:</h6>
        @include('form_partials.textarea', ['name' => "goals[$n][$m]", 'label' => '', 'value' => Arr::get($state, "goals.$n.$m")])
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
    @include('form_partials.add_button', ['name' => "goals.$n", 'buttonLabel' => 'forms.goals'])
    </div>
</div>
@endfor