@php
    $nameSimple = Str::substr($name, 0, -2);
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
<table class="table" width="100%">
@for ($a=0; $a<$loop; $a++)
<tr>
    <td valign="top">
        <div class="col-md-2 mb-2">
            @include('form_partials.text', ['name' => $nameSimple."[$a][number]", 'type' => 'number', 'label' => 'forms.number_people', 'value' => old($nameSimple."[$a][number]", (array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.number") : ''))])
        </div>
        @php($label = __($buttonLabel) . ' ' . $a+1)
        @include('form_partials.textarea', ['name' => $nameSimple."[$a][text]", 'value' => old($nameSimple.'['.$a.']', array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.text") : ''), 'class' => $class ?? ''])
        @if($a > 0)
            <div class="col-12 my-2">
                @include('form_partials.remove_button')
            </div>
        @endif
    </td>
{{--    <td valign="top" width="100">--}}
{{--        @include('form_partials.text', ['name' => $nameSimple."[$a][number]", 'type' => 'number', 'label' => 'forms.number_people', 'value' => array_key_exists($nameSimple, $state) ? data_get($state, "$nameSimple.$a.number") : ''])--}}
{{--    </td>--}}
{{--    <td width="50">--}}
{{--        @if($a > 0)--}}
{{--            @include('form_partials.remove_button')--}}
{{--        @endif--}}
{{--    </td>--}}
</tr>
@endfor
</table>
@include('form_partials.add_array_button', ['name' => $nameSimple, 'buttonLabel' => "forms.$nameSimple"])
