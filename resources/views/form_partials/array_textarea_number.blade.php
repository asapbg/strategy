@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
<table class="table" width="100%">
@for ($a=0; $a<$loop; $a++)
<tr>
    <td valign="top">
        @php($label = __($buttonLabel) . ' ' . $a+1)
        @include('form_partials.textarea', ['name' => $nameSimple."[$a][text]", 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.text") : ''])
    </td>
    <td valign="top" width="100">
        @include('form_partials.text', ['name' => $nameSimple."[$a][number]", 'label' => 'forms.number_people', 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.number") : ''])
    </td>
    <td width="50">
        @if($a > 0)
            @include('form_partials.remove_button')
        @endif
    </td>
</tr>
@endfor
</table>
@include('form_partials.add_array_button', ['name' => $nameSimple, 'buttonLabel' => "forms.$nameSimple"])