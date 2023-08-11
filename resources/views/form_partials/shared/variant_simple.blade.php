<table width="100%">
    @php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
    @for($n=0; $n<$loop; $n++)
    <tr>
        <td colspan="2">
            <h5>{{ isset($point) ? $point : '1.3' }}.{{ $n+1 }}. По проблем {{ $n+1 }}:</h5>
        </td>
    </tr>
    @php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
    @for($m=0; $m<$loop2; $m++)
    <tr>
        <td>
            <h6>Вариант {{ $m+1 }}:</h6>
            @include('form_partials.textarea', ['name' => "variant_simple[$n][$m]", 'label' => '', 'value' => Arr::get($state, "variant_simple.$n.$m")])
        </td>
        <td width="50">
            <div class="float-end">
                @include('form_partials.remove_button')
            </div>
        </td>
    </tr>
    @endfor
    <tr>
        <td colspan="2">
        @include('form_partials.add_button', ['name' => "variant_simple.$n", 'buttonLabel' => 'forms.variant'])
        </td>
    </tr>
    @endfor
</table>