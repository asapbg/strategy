<ul>
    @foreach ($formInputs as $fi)
    <li>
        <a href="{{ route('impact_assessment.form', ['form' => $fi->form, 'inputId' => $fi->id]) }}">
            {{ __('forms.' . $fi->form) }} -
            &quot;{{ $fi->dataParsed['regulatory_act'] }}&quot;
            - {{ $fi->created_at }}
        </a>
    </li>
    @endforeach
</ul>