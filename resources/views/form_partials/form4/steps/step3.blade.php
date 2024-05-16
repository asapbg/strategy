@php
    $nameSimple = 'changes';
    $loop = array_key_exists($nameSimple, $state) ? count($state[$nameSimple]) : 1;
@endphp
<table width="100%">
    @for ($a=0; $a<$loop; $a++)
    <tr>
        <td>
            <h5>
                {{ __('custom.forms4.text21') }} {{ $a+1 }}
                @if($a > 0)
                    @include('form_partials.remove_button')
                @endif
            </h5>

            <h6>1. {{ __('custom.forms4.text22') }}</h6>
            {!! __('custom.forms4.text23') !!}
            @include('form_partials.textarea', ['name' => "changes[$a][investigate_changes]"])

            <h6 class="mt-3">А1. {{ __('custom.forms4.text24') }}</h6>
            <p>{{ __('custom.forms4.text25') }}</p>
            @include('form_partials.array_textarea', ['name' => "changes[$a][problem_to_solve][]", 'buttonLabel' => 'custom.problem'])

            <h6 class="mt-3">А2.	{{ __('custom.forms4.text26') }}</h6>
            <p><b>{{ __('custom.forms4.text27') }}:</b></p>
            <p>{{ __('custom.forms4.text28') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][resources_used]", 'class' => 'mb-3'])

            <p><b>{{ __('custom.forms4.text29') }}:</b></p>
            <p>{{ __('custom.forms4.text30') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][achieved_results]", 'class' => 'mb-3'])

            <p><b>{{ __('custom.forms4.text31') }}:</b></p>
            <p>{{ __('custom.forms4.text32') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][direct_effects]", 'class' => 'mb-3'])

            <p><b>{{ __('custom.forms4.text33') }}:</b></p>
            <p>{{ __('custom.forms4.text34') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][impacts]", 'class' => 'mb-3'])

            <h6 class="mt-3">А3.	{{ __('custom.forms4.text35') }}</h6>
            @include('form_partials.textarea', ['name' => "changes[$a][links_regulatory_acts]", 'class' => 'mb-3'])

            <h6 class="mt-3">А4.	{{ __('custom.forms4.text36') }}</h6>
            @include('form_partials.textarea', ['name' => "changes[$a][main_highlights]", 'class' => 'mb-3'])

            <h6 class="mt-3">А5.	{{ __('custom.forms4.text37') }}</h6>
            @include('form_partials.textarea', ['name' => "changes[$a][results_court]", 'class' => 'mb-3'])

            <h4 class="mt-5">2. {{ __('custom.forms4.text38') }}</h4>
            <p>{{ __('custom.forms4.text39') }}</p>

            <h6 class="mt-3">{{ __('custom.forms4.text40') }}</h6>
            <p>{{ __('custom.forms4.text41') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][criteria1]", 'class' => 'mb-3'])

            <h6 class="mt-3">{{ __('custom.forms4.text42') }}</h6>
            <p>{{ __('custom.forms4.text43') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][criteria2]", 'class' => 'mb-3'])

            <h6 class="mt-3">{{ __('custom.forms4.text44') }}</h6>
            <p>{{ __('custom.forms4.text45') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][criteria3]", 'class' => 'mb-3'])

            <h6 class="mt-3">{{ __('custom.forms4.text46') }}</h6>
            <p>{{ __('custom.forms4.text47') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][criteria4]", 'class' => 'mb-3'])

            <h6 class="mt-3">{{ __('custom.forms4.text48') }}</h6>
            <p>{{ __('custom.forms4.text49') }}</p>
            @include('form_partials.textarea', ['name' => "changes[$a][criteria5]", 'class' => 'mb-3'])

            <h6 class="mt-3">3. {{ __('custom.forms4.text50') }}</h6>
            <p>{{ __('custom.forms4.text51') }}</p>
            <p><i>{{ __('custom.forms4.text52') }}:</i></p>
            @include('form_partials.array_textarea', ['name' => "changes[$a][questions][]", 'buttonLabel' => 'forms.question', 'class' => 'mb-3'])
            <p>{{ __('custom.forms4.text53') }}:</p>
        </td>
    </tr>
    @endfor
</table>

@include('form_partials.add_array_button', ['name' => $nameSimple, 'buttonLabel' => 'forms.change'])
