@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
<table width="100%">
    @for($a=0; $a<$loop; $a++)
    <tr>
        <td>
            @php($label = __($buttonLabel) . ' ' . $a+1)
            @include('form_partials.textarea', ['value' => array_key_exists($nameSimple, $state) ? $state[$nameSimple][$a] : '', 'nameDots' => "$nameSimple.$a"])
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