@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
@for ($a=0; $a<$loop; $a++)
<tr>
    <td valign="top">
        @include('form_partials.text', ['name' => $nameSimple."[$a][$keys[0]]", 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.$keys[0]") : ''])
    </td>
    <td valign="top">
        @include('form_partials.text', ['name' => $nameSimple."[$a][$keys[1]]", 'type' => 'text', 'label' => '', 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.$keys[1]") : ''])
    </td>
    <td width="50">
        @if($a > 0)
            @include('form_partials.remove_button')
        @endif
    </td>
</tr>
@endfor
<tr>
    <td colspan="3">
        @include('form_partials.add_array_button', ['name' => $nameSimple, 'buttonLabel' => "forms.$nameSimple"])
    </td>
</tr>