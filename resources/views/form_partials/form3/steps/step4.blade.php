<h4>5.2. {{ __('custom.forms3.text12') }}:</h4>
{!! __('custom.forms3.text13') !!}
@include('form_partials.textarea', ['name' => 'quality_assessment', 'label' => '', 'value' => Arr::get($state, 'quality_assessment'), 'class' => 'mb-4'])

@php($loop = array_key_exists('problem_to_solve', $state) ? count($state['problem_to_solve']) : 1)
@for($n=0; $n<$loop; $n++)
    <h5 class="@if($n > 0) mt-5 @endif" >{{ __('custom.forms3.text14') }} {{ $n+1 }}:</h5>

    @php($loop2 = Arr::get($state, "variant_simple.$n", false) ? count(Arr::get($state, "variant_simple.$n")) : 1)
    @for($m=0; $m<$loop2; $m++)
    <table width="100%">
        <tr>
            <th colspan="2">{{ __('custom.problem') }} {{ $n+1 }} / {{ __('custom.forms.variant') }} {{ $m+1 }}</th>
{{--            <th class="text-center">Вариант {{ $m+1 }}</th>--}}
        </tr>
    </table>
    @php($loop3 = 3)
    <table width="100%" class="table">
        <tr>
            <td class="text-center">{{ __('custom.forms3.text15') }}</td>
            @for($o=0; $o<$loop3; $o++)
            <td class="text-center">{{ __('custom.impact') }} {{ $o+1 }}</td>
            @endfor
        </tr>
        <tr>
            <td>{{ __('custom.low') }}</td>
            @for($o=0; $o<$loop3; $o++)
            <td class="text-center">
                @include('form_partials.radio', ['name' => "", 'value' => 0, 'label' => 'forms.not_included_in_program'])
            </td>
            @endfor
        </tr>
    </table>
    @endfor
@endfor
