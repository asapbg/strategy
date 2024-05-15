<div class="row">
    <div class="text-danger" id="global-err"></div>
</div>
@if(Session::has('old') && sizeof(Session::get('old')))
    @php($old = Session::get('old'))
{{--    @dd($old, isset($old) && isset($old['diskont']), $old['diskont'])--}}
@endif
<div class="row">
    <form class="col-12" id="form" method="POST" action="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::COSTS_AND_BENEFITS->value]) }}">
        @method('POST')
        @csrf
        <div class="mb-3">
            <label for="diskont" class="form-label">{{ __('validation.attributes.diskont') }}</label>
            <input type="number" class="form-control @error('diskont') is-invalid @enderror" name="diskont" value="{{ isset($old) && isset($old['diskont']) ? $old['diskont'] : ''}}">
            @error('diskont')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-5">
            <label for="investment_costs" class="form-label">{{ __('validation.attributes.investment_costs') }}</label>
            <input type="number" class="form-control @error('investment_costs') is-invalid @enderror" name="investment_costs" value="{{ isset($old) && isset($old['investment_costs']) ? $old['investment_costs'] : 0}}">
            @error('investment_costs')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <hr class="mt-3">
        </div>
        <div class="rows">
            @if(isset($old) && sizeof($old))
{{--                @dd($old)--}}
                @foreach($old['year'] as $k => $num)
                    @php($oldInputs = array(
                            'year' => ['key' => $k, 'val' => $num],
                            'incoming' => ['key' => $k, 'val' => $old['incoming'][$k]],
                            'costs' => ['key' => $k, 'val' => $old['costs'][$k]],
                            ))
                    @include('impact_assessment.calcs.'.$type.'.year_block', ['oldInputs' => $oldInputs])
                @endforeach
            @endif
        </div>
    </form>
</div>
<div class="row">
    <div class="col-12">
        <button class="btn btn-success text-success w-auto" id="add-year">
            <i class="fas fa-circle-plus me-1"></i>{{ __('custom.add') }} {{ __('site.year') }}
        </button>
        <button class="btn btn-primary text-primary w-auto" id="calculate" onclick="$('#form').submit();">
            <i class="fas fa-calculator  me-1"></i>{{ __('site.calculate') }}
        </button>
    </div>
</div>
@if(isset($old) && sizeof($old) && isset($old['results']))
    <div class="row">
        <div class="col-12"><span class="w-bold bg-primary text-white d-block px-3 py-2">{{ __('site.calc.results') }}:</span></div>
        <div class="col-12">
            <span class="fw-bold d-block px-3 py-2">#1 {{ __('site.calc.nvp') }}</span>
            <span class="px-3">NVP</span>
            <span class="fw-bold text-primary px-3">{{ number_format($old['results']['nvp'], 0, '.', '') }} лв.</span>
            <span class="px-3">
                <span class="px-3 fw-normal badge bg-{{ $old['results']['nvp_result_class'] }}">{{ __('site.calc.the_project_is') }} {{ $old['results']['nvp_result'] }}</span>
            </span>
        </div>
        <div class="col-12">
            <span class="fw-bold d-block px-3 py-2">#2 {{ __('site.calc.bcr') }}</span>
            <span class="px-3">BCR:</span>
            <span class="fw-bold text-primary px-3">{{ number_format($old['results']['bcr'], 2, '.', '') }}</span>
            <span class="px-3">
                <span class="px-3 fw-normal badge bg-{{ $old['results']['bcr_result_class'] }}">{{ __('site.calc.the_project_is') }} {{ $old['results']['bcr_result'] }}</span>
            </span>
        </div>
        <div class="col-12">
            <span class="fw-bold d-block px-3 py-2">#3 {{ __('site.calc.avc_avb_compare') }}</span>
            <span class="px-3"><i class="fas fa-info-circle text-primary me-2 fs-6" title="{{ __('site.calc.avc_tooltip') }}"></i>AVC:</span>
            <span class="fw-bold text-primary px-3">{{ number_format($old['results']['avc'], 0, '.', '') }} лв.</span>
            <span class="px-3"><i class="fas fa-info-circle text-primary me-2 fs-6" title="{{ __('site.calc.avb_tooltip') }}"></i>AVB:</span>
            <span class="fw-bold text-primary px-3">{{ number_format($old['results']['avb'], 0, '.', '') }} лв.</span>
            <span class="px-3">
                <span class="px-3 fw-normal badge bg-{{ $old['results']['compare_result_class'] }}">{{ __('site.calc.the_project_is') }} {{ $old['results']['compare_result'] }}</span>
            </span>
        </div>
    </div>
@endif

@push('scripts')
    <script type="text/javascript">

        let globalErrDiv = $('#global-err');
        let formDom = $('#form');
        let rowsDom = $('#form .rows');
        function addRow(){
            $.ajax({
                url: '{{ route('impact_assessment.tools.templates', ['type' => \App\Enums\CalcTypesEnum::COSTS_AND_BENEFITS->value]) }}'
            }).then(result => {
                rowsDom.append(typeof result.html != 'undefined' ? result.html : '---');
            });
        }

        $(document).ready(function (){
            @if(!Session::has('old') || !sizeof(Session::get('old')))
            if($('.year').length == 0) {
                addRow();
            }
            @endif
            $('#add-year').on('click', function (){
                addRow();
            });
            $(document).on('click', '.remove-year', function (){
                globalErrDiv.html('');
                if($('.year').length > 1) {
                    $(this).closest('.year').remove();
                    formDom.submit();
                } else {
                    globalErrDiv.html('{{ __('site.calc.msg.at_least_one_year') }}');
                }
            });
        });
    </script>
@endpush
