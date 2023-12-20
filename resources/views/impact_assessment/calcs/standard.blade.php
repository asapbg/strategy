<div class="row">
    <div class="text-danger" id="global-err"></div>
</div>
<div class="row">
    <form class="col-12" id="form" method="POST" action="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::STANDARD_COST->value]) }}">
        @method('POST')
        @csrf
        @if(Session::has('old') && sizeof(Session::get('old')))
            @php($old = Session::get('old'))
            @foreach($old['items'] as $k => $name)
                @php($oldInputs = array(
                        'item' => ['key' => $k, 'val' => $name],
                        'hours' => ['key' => $k, 'val' => $old['hours'][$k]],
                        'salary' => ['key' => $k, 'val' => $old['salary'][$k]],
                        'firms' => ['key' => $k, 'val' => $old['firms'][$k]],
                        'per_year' => ['key' => $k, 'val' => $old['per_year'][$k]],
                        'result' => ['key' => $k, 'val' => isset($old['results']) && isset($old['results'][$k]) ? $old['results'][$k]['full'] : null]
                        ))
                @include('impact_assessment.calcs.standard.activity_block', ['oldInputs' => $oldInputs])
            @endforeach
        @endif
    </form>
</div>
@if(isset($old) && sizeof($old) && isset($old['results']))
    <div class="row">
        <div class="col-12">
            <span class="fw-bold">{{ __('site.calc.standard.total') }}:</span>
            <span class="fw-bold text-primary">{{ number_format(array_sum(array_column($old['results'], 'pure_num')), 2, '.', '') }} лв.</span>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <button class="btn btn-success text-success w-auto" id="add-activity">
            <i class="fas fa-circle-plus me-1"></i>{{ __('custom.add') }} {{ __('site.activity') }}
        </button>
        <button class="btn btn-primary text-primary w-auto" id="calculate" onclick="$('#form').submit();">
            <i class="fas fa-calculator  me-1"></i>{{ __('site.calculate') }}
        </button>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">

        let globalErrDiv = $('#global-err');
        let formDom = $('#form');
        function addRow(){
            $.ajax({
                url: '{{ route('impact_assessment.tools.templates', ['type' => \App\Enums\CalcTypesEnum::STANDARD_COST->value]) }}'
            }).then(result => {
                $('form').append(typeof result.html != 'undefined' ? result.html : '---');
            });
        }

        $(document).ready(function (){
            @if(!Session::has('old') || !sizeof(Session::get('old')))
                if($('.activity').length == 0) {
                    addRow();
                }
            @endif
            $('#add-activity').on('click', function (){
                addRow();
            });
            $(document).on('click', '.remove-activity', function (){
                globalErrDiv.html('');
                if($('.activity').length > 1) {
                    $(this).closest('.activity').remove();
                    formDom.submit();
                } else {
                    globalErrDiv.html('{{ __('site.calc.msg.at_least_one_activity') }}');
                }
            });
        });
    </script>
@endpush
