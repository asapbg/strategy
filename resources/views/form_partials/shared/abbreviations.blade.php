@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
@for ($a=0; $a<$loop; $a++)
<tr>
    <td valign="top">
        @include('form_partials.text', ['name' => $nameSimple."[$a][abbreviation]", 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.abbreviation") : ''])
    </td>
    <td valign="top">
        @include('form_partials.text', ['name' => $nameSimple."[$a][meaning]", 'type' => 'text', 'label' => '', 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.meaning") : ''])
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