@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($p=0; $p<$loop; $p++)
<h5>5.{{ $p+1 . '. ' . __('forms.on_problem') . ' ' . $p+1 }}</h5>
<table class="table" width="100%">
    @php($maxVariants = count(Arr::get($state, "variants.$p", [[]])))
    <tr>
        <td width="50"></td>
        <td></td>
        @for($m=0; $m<$maxVariants; $m++)
        <td>{{ __('forms.variant') . ' ' . $m+1 }}</td>
        @endfor
    </tr>
    @php($loop2 = count(Arr::get($state, 'goals', [[]])))
    @for($n=0; $n<$loop2; $n++)
    <tr>
        @if($n == 0)
        <td rowspan="{{ $loop2 }}">Ефективност</td>
        @endif
        <td>Цел {{ $n+1 }}</td>
        @for($m=0; $m<$maxVariants; $m++)
        <td>
            <input name="comparison[{{ $p }}][{{ $n }}][{{ $m }}][0]" class="form-control" type="number"
                value="{{ data_get($state, "comparison.$p.$n.$m.0") }}">
        </td>
        @endfor
    </tr>
    @endfor
    @for($n=0; $n<$loop2; $n++)
    <tr>
        @if($n == 0)
        <td rowspan="{{ $loop2 }}">Ефикасност</td>
        @endif
        <td>Цел {{ $n+1 }}</td>
        @for($m=0; $m<$maxVariants; $m++)
        <td>
            <input name="comparison[{{ $p }}][{{ $n }}][{{ $m }}][1]" class="form-control" type="number"
                value="{{ data_get($state, "comparison.$p.$n.$m.1") }}">
        </td>
        @endfor
    </tr>
    @endfor
    @for($n=0; $n<$loop2; $n++)
    <tr>
        @if($n == 0)
        <td rowspan="{{ $loop2 }}">Съгласуваност</td>
        @endif
        <td>Цел {{ $n+1 }}</td>
        @for($m=0; $m<$maxVariants; $m++)
        <td>
            <input name="comparison[{{ $p }}][{{ $n }}][{{ $m }}][2]" class="form-control" type="number"
                value="{{ data_get($state, "comparison.$p.$n.$m.2") }}">
        </td>
        @endfor
    </tr>
    @endfor
    <tr>
        <td colspan="2" class="text-center">
            <p>
                <i>1.1. Сравнете вариантите чрез сравняване на ключовите им положителни и отрицателни въздействия.
                <br>1.2. Посочете степента, в която вариантите ще изпълнят определените цели, съгласно основните критерии за сравняване на вариантите:
                ефективност, чрез която се измерва степента, до която вариантите постигат целите на предложението;
                ефикасност, която отразява степента, до която целите могат да бъдат постигнати при определено ниво на ресурсите или при най-малко разходи;
                съгласуваност, която показва степента, до която вариантите съответстват на действащите стратегически документи.</i>
            </p>                
        </td>
    </tr>
</table>
@endfor
