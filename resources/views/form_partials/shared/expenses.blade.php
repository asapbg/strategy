@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
<tr>
    <td colspan="2">
        <h5>3.{{ $n+1 }}. По проблем {{ $n+1 }}:</h5>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table width="100%">
            <tr>
                <td>Варианти за действие</td>
                <td width="40%">Общи годишни разходи</td>
                <td width="40%">Общи годишни ползи</td>
            </tr>
            @php($loop2 = Arr::get($state, "variants.$n", false) ? count(Arr::get($state, "variants.$n")) : 1)
            @for($m=0; $m<$loop2; $m++)
            <tr>
                <td>
                    <h6>Вариант {{ $m+1 }}:</h6>
                </td>
                <td>
                    @include('form_partials.textarea', ['name' => "expenses[$n][$m][expenses]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.expenses")])
                </td>
                <td>
                    @include('form_partials.textarea', ['name' => "expenses[$n][$m][benefits]", 'label' => '', 'value' => Arr::get($state, "expenses.$n.$m.benefits")])
                </td>
            </tr>
            @endfor
            <tr>
                <td></td>
                <td colspan="2">
                    <p><i>Опишете качествено и количествено всички значителни разходи и ползи на годишна база и коя от заинтересованите страни ще ги понесе. Използвайте приблизителни цифри и диапазони, включително парични разходи (в лв.).</i></p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endfor