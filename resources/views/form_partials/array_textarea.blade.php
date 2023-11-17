@php
    $nameSimple = Str::substr($name, 0, -2);
    $nameDots = str_replace('[', '.', $nameSimple);
    $nameDots = str_replace(']', '', $nameDots);
    $loop = \Arr::has($state, $nameDots) ? count(data_get($state, $nameDots)) : 1;
@endphp
<table width="100%">
    @for($a=0; $a<$loop; $a++)
    <tr>
        <td>
            @php($label = __($buttonLabel) . ' ' . $a+1)
            @include('form_partials.textarea', ['nameDots' => "$nameDots.$a", "class" => $class ?? ''])
        </td>
        <td width="50">
            @if($a > 0)
                @include('form_partials.remove_button')
            @endif
        </td>
    </tr>
    @endfor
</table>
@include('form_partials.add_button')
