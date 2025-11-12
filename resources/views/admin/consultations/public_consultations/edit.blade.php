@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">Основна информация</a>
                        </li>
                        @if($item->id)
                            <li class="nav-item">
                                <a class="nav-link" id="ct-doc-tab" data-toggle="pill" href="#ct-doc" role="tab" aria-controls="ct-doc" aria-selected="false">Документи</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-kd-tab" data-toggle="pill" href="#ct-kd" role="tab" aria-controls="ct-kd" aria-selected="false">Консултационен документ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-contacts-tab" data-toggle="pill" href="#ct-contacts" role="tab" aria-controls="ct-contacts" aria-selected="false">Контактна информация</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-polls-tab" data-toggle="pill" href="#ct-polls" role="tab" aria-controls="ct-polls" aria-selected="false">Анкети</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-comments-tab" data-toggle="pill" href="#ct-comments" role="tab" aria-controls="ct-comments" aria-selected="false">Становище предложения</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                            @include('admin.consultations.public_consultations.general')
                        </div>
                        @if($item->id)
                            <div class="tab-pane fade" id="ct-doc" role="tabpanel" aria-labelledby="ct-doc-tab">
                                @include('admin.consultations.public_consultations.doc')
                            </div>
                            <div class="tab-pane fade" id="ct-kd" role="tabpanel" aria-labelledby="ct-kd-tab">
                                @include('admin.consultations.public_consultations.kd')
                            </div>
                            <div class="tab-pane fade" id="ct-contacts" role="tabpanel" aria-labelledby="ct-contacts-tab">
                                @include('admin.consultations.public_consultations.contact_persons')
                            </div>
                            <div class="tab-pane fade" id="ct-polls" role="tabpanel" aria-labelledby="ct-polls-tab">
                                @include('admin.consultations.public_consultations.polls')
                            </div>
                            <div class="tab-pane fade" id="ct-comments" role="tabpanel" aria-labelledby="ct-comments-tab">
                                @include('admin.consultations.public_consultations.comments')
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.person_contacts', 1)])

            @php
                $have_request_param = $have_request_param ?? false;
            @endphp
            <div class="modal fade" id="modal-delete-poll-resource" role="dialog" aria-hidden=" true">
                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header bg-danger text-white">
                            <h4 class="modal-title">
                                <i class="fas fa-exclamation"></i>
                                {{__('custom.remove')}}  {{ trans_choice('custom.polls', 1) }}
                                <span class="resource-name d-none"></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            {{ __('custom.are_you_sure_to_delete') }} {{ mb_strtolower(trans_choice('custom.polls', 1)) }}
                            <b><span id="resource_label" class="resource-name"></span></b> ?
                        </div>

                        <div class="modal-footer">
                            <form method="POST" action="" class="pull-left mr-4">
                                @csrf
                                <input name="id" value="" id="resource_id" type="hidden">

                                @if($have_request_param)
                                    @method('DELETE')
                                    <input type="hidden" name="deleted" value="1"/>
                                @endif

                                <button type="submit" class="btn btn-danger js-delete-resource">
                                    <i class="fas fa-ban"></i>&nbsp; {{__('custom.deletion')." ".__('custom.of')}} {{capitalize(trans_choice('custom.polls', 1))}}
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-secondary pull-left" data-dismiss="modal">
                                {{__('custom.cancel')}}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        new cainSelect({
            mainSelectId: 'legislative_program_id',
            childSelectClass: 'cl-child-lp',
            childSelectData: 'cl',
            anyValue: 'cl',
        });

        new cainSelect({
            mainSelectId: 'operational_program_id',
            childSelectClass: 'cl-child-op',
            childSelectData: 'cl',
            anyValue: 'cl',
        });

        let consultationLevel = parseInt(<?php echo ($item->id ? $item->consultation_level_id : $userInstitutionLevel) ?>);
        let actType = $('#act_type_id');

        //Consultation level
        let centralConsultationLevel = parseInt('<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>');
        //Acts
        let actLaw = parseInt('<?php echo \App\Models\ActType::ACT_LAW; ?>');
        let actMinistry = parseInt('<?php echo \App\Models\ActType::ACT_COUNCIL_OF_MINISTERS; ?>');
        //Programs
        let legislativePrograms = $('#legislative_programs');
        let legislativeProgramRows = $('#legislative_program_row_id');
        let legislativeProgramSelect = $('#legislative_program_id');
        let operationalPrograms = $('#operational_programs');
        let operationalProgramsRows = $('#operational_program_row_id');
        let operationalProgramsSelect = $('#operational_program_id');
        //Pris
        let prisSection = $('#pris_section');
        //Law
        let lawSection = $('#law_section');

        $('#legislative_program_id').on('change', function (){

        });

        function hideProgramSelects()
        {
            //hide programs selects and deselect all
            operationalProgramsRows.parent().addClass('d-none');
            operationalPrograms.addClass('d-none');
            //
            operationalProgramsSelect.addClass('d-none');
            operationalPrograms.find('option').each(function(){
                $(this).prop('selected', false);
            });
            legislativeProgramSelect.val('');
            operationalProgramsSelect.val('');
            legislativeProgramRows.val('');
            operationalProgramsRows.val('');

            legislativeProgramRows.parent().addClass('d-none');
            //
            legislativeProgramSelect.addClass('d-none');
            legislativePrograms.addClass('d-none');
            legislativePrograms.find('option').each(function(){
                $(this).prop('selected', false);
            });
        }

        function hideLawAndPris()
        {
            lawSection.find('select').val('0');
            prisSection.find('select').val('0');
            lawSection.addClass('d-none');
            prisSection.addClass('d-none');
        }

        function onDateChange() {
            let durationErrorHolder = $('#duration-err');
            durationErrorHolder.html('');

            if( $(this).attr('id') == 'open_from' ) {
                var toDate = addSubDays($(this).val(), 30, true, true);
                    $('#open_to').datepicker("setDate", new Date(toDate.getFullYear(),toDate.getMonth(),toDate.getDate()) );
            }

            let diffDays = null;
            const date1 = $('#open_from').datepicker('getDate');
            const date2 = $('#open_to').val() ? addSubDays($('#open_to').val(), 0, true, true) : null
            // const date2 = $('#open_to').datepicker('getDate');

            if( date1 && date2 ) {
                let diffTime = Math.abs(date2 - date1);
                diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }
            $('#period-total').text(diffDays ? diffDays : 0);

            let minDuration = parseInt(<?php echo \App\Models\Consultations\PublicConsultation::MIN_DURATION_DAYS; ?>);
            let shortDuration = parseInt(<?php echo \App\Models\Consultations\PublicConsultation::SHORT_DURATION_DAYS; ?>);
            if( diffDays && diffDays < minDuration) {
                durationErrorHolder.html('Минималната продължителност е '+ minDuration +' дни');
            }

            let shortDurationReason = $('#shortTermReason_section');
            if( diffDays && diffDays <= shortDuration ) {
                shortDurationReason.removeClass('d-none');
            } else{
                shortDurationReason.val('');
                shortDurationReason.addClass('d-none');
            }
        }

        function controlForm()
        {
            //If central level consultation
            if( consultationLevel == centralConsultationLevel ) {
                //Depending on act type
                if( parseInt(actType.val()) == actLaw ){
                    //show $zp autocomplete select and checkbox 'Законопроектът не е включен в ЗП'. Submit one of them.
                    if($('#no_legislative_program').is(':checked')) {
                        legislativeProgramRows.parent().addClass('d-none');
                        legislativeProgramSelect.addClass('d-none');
                    } else {
                        legislativeProgramRows.parent().removeClass('d-none');
                        legislativeProgramSelect.removeClass('d-none');
                    }
                    legislativePrograms.removeClass('d-none');
                    operationalPrograms.addClass('d-none');
                    operationalProgramsSelect.addClass('d-none');
                    operationalPrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    legislativePrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    operationalProgramsSelect.val('');
                    operationalProgramsRows.find('option').each(function(){
                        $(this).remove();
                    });

                    lawSection.removeClass('d-none');
                    prisSection.find('select').val('0');
                    prisSection.addClass('d-none');

                } else if( parseInt(actType.val()) == actMinistry ){
                    //show $op autocomplete select and checkbox 'Проектът на акт на МС не е включен в ОП'. Submit one of them.
                    if($('#no_operational_program').is(':checked')) {
                        operationalProgramsRows.parent().addClass('d-none');
                        operationalProgramsSelect.addClass('d-none');
                    } else {
                        operationalProgramsRows.parent().removeClass('d-none');
                        operationalProgramsSelect.removeClass('d-none');
                    }
                    operationalPrograms.removeClass('d-none');
                    legislativePrograms.addClass('d-none');
                    legislativeProgramSelect.addClass('d-none');
                    legislativePrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    operationalPrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    legislativeProgramSelect.val('');
                    legislativeProgramRows.find('option').each(function(){
                        $(this).remove();
                    });

                    prisSection.removeClass('d-none');
                    lawSection.find('select').val('0');
                    lawSection.addClass('d-none');
                } else {
                    hideProgramSelects();
                    hideLawAndPris();
                }
            } else {
                hideProgramSelects();
                hideLawAndPris();
            }
        }

        //Calculate consultation duration
        $('#open_from, #open_to').on('change', onDateChange);

        $('#act_type_id').on('change', function (){
            controlForm();
        });

        $('#no_legislative_program, #no_operational_program').on('change', function (){
            if($(this).is(':checked')) {
                $('#' + $(this).data('list')).val('').change();
                legislativeProgramRows.parent().addClass('d-none');
                legislativeProgramSelect.addClass('d-none');
                operationalProgramsRows.parent().addClass('d-none');
                operationalProgramsSelect.addClass('d-none');
            } else {
                controlForm();
            }
        });

        $('#legislative_program_id, #operational_program_id').on('change', function (){
            if(!(parseInt($(this).val()) > 0)) {
                operationalProgramsRows.find('option').each(function(){
                    $(this).remove();
                });
                legislativeProgramRows.find('option').each(function(){
                    $(this).remove();
                });
            } else {
                controlForm();
            }
        });


        @if($isAdmin)
            let levelsLabel = <?php echo json_encode(\App\Enums\InstitutionCategoryLevelEnum::keyToLabel());?>

            $('#institution_id').on('change', function (){
                let selectedOpt = $(this).find('option:selected');
                let foa  = selectedOpt.data('foa');
                let level  = parseInt(selectedOpt.data('level'));
                consultationLevel = level;
                $('#nomenclature_level').val(level);
                $('#levelLabel').html(levelsLabel[level]);

                $('#act_type_id').val('').trigger('change');
                $('#act_type_id option').each(function (){
                    if(parseInt($(this).data('level')) == level) {
                        $(this).attr('disabled', false);
                    } else{
                        $(this).attr('disabled', true);
                    }
                });

                $('#field_of_actions_id').val('').trigger('change');
                $('#field_of_actions_id option').each(function (){
                    if (typeof foa != 'undefined') {
                        if (foa.indexOf(parseInt($(this).attr('value'))) != -1) {
                            $(this).attr('disabled', false);
                        } else {
                            $(this).attr('disabled', true);
                        }
                    } else {
                        $(this).attr('disabled', true);
                    }
                });
            });
        @endif

        //Init and preset form
        onDateChange();
        controlForm();
    });

</script>
@endpush
