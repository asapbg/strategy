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
                                <a class="nav-link" id="ct-contacts-tab" data-toggle="pill" href="#ct-contacts" role="tab" aria-controls="ct-contacts" aria-selected="false">Лица за контакт</a>
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
            legislativeProgramRows.parent().addClass('d-none');
            //
            legislativeProgramSelect.addClass('d-none');
            legislativePrograms.addClass('d-none');
            legislativePrograms.find('option').each(function(){
                $(this).prop('selected', false);
            });
        }

        function onDateChange() {
            let durationErrorHolder = $('#duration-err');
            durationErrorHolder.html('');

            if( $(this).attr('id') == 'open_from' ) {
                var toDate = addSubDays($(this).val(), 14, true, true);
                    $('#open_to').datepicker("setDate", new Date(toDate.getFullYear(),toDate.getMonth(),toDate.getDate()) );
            }

            let diffDays = null;
            const date1 = $('#open_from').datepicker('getDate');
            const date2 = $('#open_to').datepicker('getDate');
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
                } else {
                    hideProgramSelects();
                }
            } else {
                hideProgramSelects();
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

        //Init and preset form
        onDateChange();
        controlForm();
    });

</script>
@endpush
