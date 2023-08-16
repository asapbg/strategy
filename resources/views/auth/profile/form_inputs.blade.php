<div>
    @foreach ($formInputs as $fi)
    <div class="row">
        <div class="col-sm-8">
            <a href="{{ route('impact_assessment.form', ['form' => $fi->form, 'inputId' => $fi->id]) }}">
                {{ __('forms.' . $fi->form) }} -
                @if(Arr::has($fi->dataParsed, 'regulatory_act'))
                    &quot;{{ $fi->dataParsed['regulatory_act'] }}&quot;
                @endif
                - {{ $fi->created_at }}
                {{ $fi->dataParsed['step'] }}
            </a>
        </div>
        <div class="col-sm-4">
            @php
                $steps = \App\Http\Controllers\ImpactAssessmentController::getSteps($fi->form);
            @endphp
            {{ $fi->dataParsed['step'] < $steps ? __('custom.draft') : __('custom.completed') }}
        </div>
    </div>
    @endforeach
</div>