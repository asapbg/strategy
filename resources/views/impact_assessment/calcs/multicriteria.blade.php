<div class="row">
    <div class="text-danger" id="global-err"></div>
</div>
@if(Session::has('old') && sizeof(Session::get('old')))
    @php($old = Session::get('old'))
@endif
<div class="row">
    <form class="col-12" id="form" method="POST" action="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::MULTICRITERIA->value]) }}">
        @method('POST')
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <span class="input-group-text">Степени за оценка <i class="ms-2">(<span class="text-primary">1 = (-1,0,1); 2 = (-2, -1, 0, 1, 2)</span>)</i></span>
                    <input type="number" name="step" id="step" class="form-control" value="@if(isset($old) && $old['step']){{ $old['step'] }}@else{{ '1' }}@endif">
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
        <div id="matrix" style="overflow-x: scroll;">
            <table class="table table-responsive">
                <tr id="variants">
                    <th>Критерии</th>
                    <th>Тегла (%) <span id="weight-total"></span></th>
                    @if(isset($old) && isset($old['variants']) && sizeof($old['variants']))
                        @foreach($old['variants'] as $x => $xName)
                            <th>
                                <i class="remove-variant fas fa-times-circle text-danger me-2" data-v="{{ $x }}" role="button"></i>
                                <input type="text" name="variants[]" class="form-control form-control-sm" value="{{ $xName }}">
                            </th>
                        @endforeach
                    @endif
                </tr>
                @if(isset($old) && isset($old['criteria']) && sizeof($old['criteria']))
                    @foreach($old['criteria'] as $y => $yName)
                        <tr class="row-criteria">
                            <th>
                                <i class="remove-criteria fas fa-times-circle text-danger me-2" role="button"></i>
                                <input type="text" name="criteria[]" class="form-control form-control-sm @error('criteria.'.$y) is-invalid @enderror" value="{{ $yName }}">
                                @error('criteria.'.$y)
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </th>
                            <td class="weight">
                                <input type="text" name="weight[]" class="form-control form-control-sm weight-val @if(isset($old['weight'])) @error('weight.'.$y) is-invalid @enderror @endif" value="{{ $old['weight'][$y] }}">
                                @if(isset($old['weight']))
                                    @error('weight.'.$y)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                @endif
                            </td>
                            @if(isset($old) && isset($old['variants']) && sizeof($old['variants']))
                                @foreach($old['variants'] as $x => $xName)
                                    <td class="variants" data-v="{{ $x }}">
                                        <input type="number" name="evaluation[{{ $y }}][{{ $x }}]" step="1" min="{{ (0 - (int)$old['step']) }}" max="{{ (int)$old['step'] }}" class="form-control form-control-sm evaluation-val" value="{{ $old['evaluation'][$y][$x] ?? 0 }}">
                                        @if(isset($old['evaluation']))
                                            @error('evaluation.'.$y.'.'.$x)
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                @endif
                @if(isset($old) && sizeof($old) && isset($old['results']) && sizeof($old['results']))
                    <tr class="total">
                        <th colspan="2">Общо:</th>
                        @foreach($old['results'] as $r)
                            <th>{{ $r }}</th>
                        @endforeach
                    </tr>
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
        let stepDom = $('#step');
        let stepMin = -1;
        let stepMax = 1;

        function addCriteria(name = '', countCurrentVariant = 1){
            let variantsHtml = '';
            let criteriaInx = $('.row-criteria').length;
            for(let i = 0; i < countCurrentVariant; i ++){
                variantsHtml += '<td class="variants" data-v="' + i + '">' +
                    '<input type="number" name="evaluation['+ criteriaInx +']['+i+']" step="1" min="'+ stepMin +'" min="'+ stepMax +'" class="form-control form-control-sm evaluation-val" value="0">' +
                    '</td>';
            }
            matrixDom.find('table').append('' +
                '<tr class="row-criteria">' +
                    '<th>' +
                        '<i class="remove-criteria fas fa-times-circle text-danger me-2" role="button"></i>' +
                        '<input type="text" name="criteria[]" class="" value="' +name+ '">' +
                    '</th>' +
                    '<td class="weight">' +
                        '<input type="text" name="weight[]" class="form-control form-control-sm weight-val" value="">' +
                    '</td>' + variantsHtml +
                '</tr>');
        }

        function addVariant(name = '', y = 0, x = 0){
            let titleVariantInx = $('table tr#variants .variants').length;
            matrixDom.find('table tr#variants').append('<th class="variants" data-v="' + (titleVariantInx) + '"><i class="remove-variant fas fa-times-circle text-danger me-2" data-v="' + titleVariantInx + '" role="button"></i><input type="text" name="variants[]" value="' + name + '"></th>');
            matrixDom.find('table tr.row-criteria').each(function (inx, el){
                console.log(el, inx);
                let variantInx = $(this).find('.variants').length;
                $(this).append('<td class="variants" data-v="' + (variantInx) + '">' +
                    '<input type="number" name="evaluation['+ inx +']['+ titleVariantInx +']" step="1" min="'+ stepMin +'" min="'+ stepMax +'" class="form-control form-control-sm evaluation-val" value="0">' +
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
                if($('.row-criteria').length > 1) {
                    $(this).closest('tr').remove();
                } else{
                    globalErrDiv.html('Необходим е поне един критерий за анализа.');
                }
            });

            $(document).on('click', '.remove-variant', function (){
                if($('th.variants').length > 1){
                    let colInx = $(this).data('v');
                    $('th[data-v="' + colInx + '"], td[data-v="' + colInx + '"]').remove();
                } else{
                    globalErrDiv.html('Необходим е поне един вариант за анализа.');
                }

            });

            $(document).on('change keyup paste', '#step', function (){
                if(parseInt($(this).val()) < 1) {
                    $(this).val(1);
                }
                let min = 0 - parseInt($(this).val());
                let max = parseInt($(this).val());
                $('.evaluation-val').each(function (){
                   $(this).attr('min', min);
                   $(this).attr('max', max);
                   if($(this).val() < min || $(this).val() > max){
                       $(this).val(0);
                   }
                });
            });

            $(document).on('change keyup paste', '.weight-val', function (e){
                let currentWeightInput = e.currentTarget;
                let fullWeight = 0;
                $('.weight-val').each(function (){
                    fullWeight = fullWeight + parseFloat($(this).val() > 0 ? $(this).val() : 0);
                });
                if(fullWeight > 100) {
                    $(currentWeightInput).val(0);
                } else{
                    $('#weight-total').html(fullWeight);
                }
            });
        });
    </script>
@endpush
