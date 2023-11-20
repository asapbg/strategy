@php
    $nameSimple = Str::substr($name, 0, -2);
    $nameDots = str_replace('[', '.', $nameSimple);
    $nameDots = str_replace(']', '', $nameDots);
    $loop = \Arr::has($state, $nameDots) ? count(data_get($state, $nameDots)) : 1;
@endphp
<table width="100%">
    @for($a=0; $a<$loop; $a++)
    <tr>
        <td @if(!$a) class="pb-4" @endif>
            @php($label = __($buttonLabel) . ' ' . $a+1)
            @include('form_partials.textarea', ['nameDots' => "$nameDots.$a", "class" => ($class ?? '')])
            @if($a > 0)
                <div class="col-12 mt-2 mb-4">
                    @include('form_partials.remove_button')
                </div>
            @endif
        </td>
    </tr>
    @endfor
</table>
@include('form_partials.add_button')
