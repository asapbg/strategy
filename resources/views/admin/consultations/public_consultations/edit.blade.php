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
                        <li class="nav-item">
                            <a class="nav-link" id="ct-kd-tab" data-toggle="pill" href="#ct-kd" role="tab" aria-controls="ct-kd" aria-selected="false">Консултационен документ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ct-contacts-tab" data-toggle="pill" href="#ct-contacts" role="tab" aria-controls="ct-contacts" aria-selected="false">Лица за контакт</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                            @include('admin.consultations.public_consultations.general')
                        </div>
                        <div class="tab-pane fade" id="ct-kd" role="tabpanel" aria-labelledby="ct-kd-tab">
                            @include('admin.consultations.public_consultations.kd')
                        </div>
                        <div class="tab-pane fade" id="ct-contacts" role="tabpanel" aria-labelledby="ct-contacts-tab">
                            @include('admin.consultations.public_consultations.contact_persons')
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
            mainSelectId: 'consultation_level_id',
            childSelectClass: 'cl-child',
            childSelectData: 'cl',
            anyValue: 'cl',
        });

        let consultationLevel = $('#consultation_level_id');
        let actType = $('#act_type');
        //Normative acts sections
        let prisNormativeActs = $('#normative_act_pris_section');
        let normativeActs = $('#normative_act_section');
        //Consultation level
        let centralConsultationLevel = parseInt(<?php echo \App\Models\ConsultationLevel::CENTRAL_LEVEL; ?>);
        //Acts
        let actLaw = parseInt(<?php echo \App\Models\ActType::ACT_LAW; ?>);
        let actMinistry = parseInt(<?php echo \App\Models\ActType::ACT_COUNCIL_OF_MINISTERS; ?>);
        //Programs
        let legislativePrograms = $('#legislative_programs');
        let operationalPrograms = $('#operational_programs');

        function hideActSelects()
        {
            //hide $regulatoryActs and $pris acts and deselect all
            normativeActs.addClass('d-none');
            normativeActs.find('option').each(function(){
                $(this).prop('selected', false);
            });
            prisNormativeActs.addClass('d-none');
            prisNormativeActs.find('option').each(function(){
                $(this).prop('selected', false);
            });
        }

        function hideProgramSelects()
        {
            //hide programs selects and deselect all
            operationalPrograms.addClass('d-none');
            operationalPrograms.find('option').each(function(){
                $(this).prop('selected', false);
            });
            legislativePrograms.addClass('d-none');
            legislativePrograms.find('option').each(function(){
                $(this).prop('selected', false);
            });
        }

        function onDateChange() {
            let durationErrorHolder = $('#duration-err');
            durationErrorHolder.html('');

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
            if( parseInt(consultationLevel.val()) === centralConsultationLevel ) {
                //Depending on act type
                if( parseInt(actType.val()) == actLaw ){
                    //show $pris normative act select and deselect and hide $regulatoryActs
                    prisNormativeActs.removeClass('d-none');
                    normativeActs.addClass('d-none');
                    normativeActs.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    //show $zp autocomplete select and checkbox 'Законопроектът не е включен в ЗП'. Submit one of them.
                    legislativePrograms.removeClass('d-none');
                    operationalPrograms.addClass('d-none');
                    operationalPrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    legislative_programs
                } else if( parseInt(actType.val()) == actMinistry ){
                    //show $regulatoryActs normative act select and deselect and hide $regulatoryActs
                    normativeActs.removeClass('d-none');
                    prisNormativeActs.addClass('d-none');
                    prisNormativeActs.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                    //show $op autocomplete select and checkbox 'Проектът на акт на МС не е включен в ОП'. Submit one of them.
                    operationalPrograms.removeClass('d-none');
                    legislativePrograms.addClass('d-none');
                    legislativePrograms.find('option').each(function(){
                        $(this).prop('selected', false);
                    });
                } else {
                    hideActSelects();
                    hideProgramSelects();
                }
            } else {
                hideActSelects();
                hideProgramSelects();
            }
        }

        //Calculate consultation duration
        $('#open_from, #open_to').on('change', onDateChange);

        $('#consultation_level_id, #act_type').on('change', function (){
            controlForm();
        });

        //Init and preset form
        onDateChange();
        controlForm();
    });

</script>
@endpush
