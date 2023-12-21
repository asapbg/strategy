<div class="row">
    <div class="text-danger" id="global-err"></div>
</div>
@if(Session::has('old') && sizeof(Session::get('old')))
    @php($old = Session::get('old'))
    {{--    @dd($old, isset($old) && isset($old['diskont']), $old['diskont'])--}}
@endif
<div class="row">
    <form class="col-12" id="form" method="POST" action="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::MULTICRITERIA->value]) }}">
        @method('POST')
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <span class="input-group-text">Степени за оценка <i class="ms-2">(<span class="text-primary">1 = (-1,0,1); 2 = (-2, -1, 0, 1, 2)</span>)</i></span>
                    <input type="number" name="step" class="form-control" value="@if(isset($old) && $old['step']){{ $old['step'] }}@else{{ '1' }}@endif">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <input type="text" id="new-variant-name" class="form-control" placeholder="Наименование" aria-label="Наименование">
                    <button class="btn btn-outline-secondary" type="button" id="add-variant">Добави Вариант</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <input type="text" id="new-criteria-name" class="form-control" placeholder="Наименование" aria-label="Наименование">
                    <button class="btn btn-outline-secondary" type="button" id="add-criteria">Добави Критерий</button>
                </div>
            </div>
        </div>
        <div id="matrix">
            <table class="table">
                <tr id="variants">
                    <th>Критерии</th>
                    <th>Тегла (%)</th>
                    @if(isset($old) && isset($old['variants']) && sizeof($old['variants']))
                        @foreach($old['variants'] as $x => $xName)
                            <th>
                                <input type="text" name="variants[]" class="form-control form-control-sm" value="{{ $name }}">
                            </th>
                        @endforeach
                    @endif
                </tr>
                @if(isset($old) && isset($old['criteria']) && sizeof($old['criteria']))
                    @foreach($old['criteria'] as $y => $yName)
                        <tr class="row-criteria">
                            <th>
                                <input type="text" name="criteria[]" class="form-control form-control-sm" value="{{ $name }}">
                            </th>
                            <td class="weight">
                                <input type="text" name="weight[]" class="form-control form-control-sm" value="{{ $old['weight'][$y] }}">
                            </td>
                            @if(isset($old) && isset($old['variants']) && sizeof($old['variants']))
                                @foreach($old['variants'] as $x => $xName)
                                    <td class="variants" data-v="{{ $x }}">
                                        <input type="text" name="evaluation[]" class="form-control form-control-sm" value="{{ $old['evaluation'][$y][$x] ?? 0 }}">
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-12">
        <button class="btn btn-primary text-primary w-auto" id="calculate" onclick="$('#form').submit();">
            <i class="fas fa-calculator  me-1"></i>{{ __('site.calculate') }}
        </button>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        let globalErrDiv = $('#global-err');
        let formDom = $('#form');
        let matrixDom = $('#matrix');

        function addCriteria(name = '', variantName = ''){
            let variants = $('table tr#variants .variants').length;
            let variantsHtml = '';
            for(let i = 0; i < variants; i ++){
                variantsHtml += '<td class="variants" data-v="' + i + '">' +
                    '<input type="text" name="evaluation[]" class="form-control form-control-sm" value="">' +
                    '</td>';
            }
            matrixDom.find('table').append('' +
                '<tr class="row-criteria">' +
                    '<th>' +
                        '<i class="remove-criteria fas fa-times-circle text-danger me-2" role="button"></i>' +
                        '<input type="text" name="criteria[]" class="" value="' +name+ '">' +
                    '</th>' +
                    '<td class="weight">' +
                        '<input type="text" name="weight[]" class="form-control form-control-sm" value="">' +
                    '</td>' + variantsHtml +
                '</tr>');
        }

        function addVariant(name = ''){
            let titleVariantInx = $('table tr#variants .variants').length;
            matrixDom.find('table tr#variants').append('<th class="variants" data-v="' + (titleVariantInx) + '"><i class="remove-variant fas fa-times-circle text-danger me-2" data-v="' + titleVariantInx + '" role="button"></i><input type="text" name="variants[]" value="' + name + '"></th>');
            matrixDom.find('table tr.row-criteria').each(function (){
                let variantInx = $(this).find('.variants').length;
                $(this).append('<td class="variants" data-v="' + (variantInx) + '">' +
                    '<input type="text" name="evaluation[]" class="form-control form-control-sm" value="">' +
                    '</td>');
            });
        }

        function initMatrix(){
            //add first variant
            addVariant('Вариант №1');
            // matrixDom.find('tr').append('<th><input type="text" name="variants[]" class="form-control form-control-sm variants" value="Вариант №1"></th>');
            //add first criteria
            addCriteria('Критерий №1')
        }

        $(document).ready(function (){
            @if(!Session::has('old') || !sizeof(Session::get('old')))
                if($('.variants').length == 0) {
                    initMatrix();
                }
            @endif

            $('#add-criteria').on('click', function (){
                addCriteria($('#new-criteria-name').val());
                $('#new-criteria-name').val('');
            });
            $('#add-variant').on('click', function (){
                addVariant($('#new-variant-name').val());
                $('#new-variant-name').val('');
            });

            $(document).on('click', '.remove-criteria', function (){
                $(this).closest('tr').remove();
            });

            $(document).on('click', '.remove-variant', function (){
                let colInx = $(this).data('v');
                $('th[data-v="' + colInx + '"], td[data-v="' + colInx + '"]').remove();
            });
            {{--$(document).on('click', '.remove-year', function (){--}}
            {{--    globalErrDiv.html('');--}}
            {{--    if($('.year').length > 1) {--}}
            {{--        $(this).closest('.year').remove();--}}
            {{--        formDom.submit();--}}
            {{--    } else {--}}
            {{--        globalErrDiv.html('{{ __('site.calc.msg.at_least_one_year') }}');--}}
            {{--    }--}}
            {{--});--}}
        });
    </script>
@endpush
