<div class="px-md-5" style="min-height: 300px;">
    @foreach ($data as $fi)
        @php
            $steps = \App\Http\Controllers\ImpactAssessmentController::getSteps($fi->form);
            $parsedData = $fi->dataParsed;
            $isDraft = !(isset($parsedData['submit']) && (int)$parsedData['submit'])
        @endphp
{{--    @dd($parsedData)--}}
        <div class="row @if(!$loop->last) mb-3 @endif border-start border-4  @if($isDraft) border-warning @else border-success @endif">
            <div class="col-sm-8">
                @php($route = $isDraft ? route('impact_assessment.form', ['form' => $fi->form, 'inputId' => $fi->id, 'step' => $parsedData['step']]) : route('impact_assessment.show', ['form' => $fi->form, 'inputId' => $fi->id]))
                <a href="{{ $route }}">
                    {{ __('forms.' . $fi->form) }} ({{ displayDate($fi->created_at) }})
                </a>
                @if(isset($parsedData['institution']) && !empty($parsedData['institution']))
                    <i class="d-block">
                        {{ $parsedData['institution'] }}
                    </i>
                @endif
                @if(isset($parsedData['regulatory_act']) && !empty($parsedData['regulatory_act']))
                    <i class="d-block">
                        {{ $parsedData['regulatory_act'] }}
                    </i>
                @endif
            </div>
            <div class="col-sm-4">
                {{--                {{ (int)$parsedData['step'] < (int)$steps ? __('custom.draft') : __('custom.completed') }}--}}
                <span>{{ !$isDraft ? __('custom.completed_f') : __('custom.draft') }}</span>
                <div class="">
                    <a href="{{ route('impact_assessment.pdf', ['form' => $fi->form, 'inputId' => $fi]) }}">
                        {{ __('forms.print_pdf') }}
                    </a><br>
                    <a href="{{ route('impact_assessment.show', ['form' => $fi->form, 'inputId' => $fi]) }}">
                        {{ __('forms.show_form_input') }}
                    </a>
{{--                    <a href="{{ route('impact_assessment.form', ['form' => $fi->form]) }}" class="btn btn-primary">--}}
{{--                        {{ __('forms.fill_again') }}--}}
{{--                    </a>--}}
                </div>
            </div>
        </div>
    @endforeach
</div>
