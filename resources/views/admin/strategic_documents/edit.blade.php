@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">Основна информация</a>
                        </li>
                        @if($item->id)
                            <li class="nav-item">
                                <a class="nav-link" id="files-tab" data-toggle="pill" href="#files" role="tab" aria-controls="files" aria-selected="false">Файлове</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                            @include('admin.strategic_documents.general')
                        </div>

                        @if($item->id)
                            <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                                @include('admin.strategic_documents.files')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            let strategicActType = $('#strategic_act_type_id');
            let acceptActInstitutionType = $('#accept_act_institution_type_id');
            let prisActContainer = $('#pris-act');

            function controlCustomActFields()
            {
                let strategicActTypeVal = parseInt(strategicActType.val());
                let acceptActInstitutionTypeVal = parseInt(acceptActInstitutionType.val());

                if( [1].indexOf(acceptActInstitutionTypeVal)  != -1 ) {
                    $('.act-custom-fields').addClass('d-none');
                    prisActContainer.removeClass('d-none');
                } else if( acceptActInstitutionTypeVal > 0 && [1].indexOf(acceptActInstitutionTypeVal)  == -1 ) {
                    prisActContainer.addClass('d-none');
                    $('.act-custom-fields').removeClass('d-none');
                } else{
                    $('.act-custom-fields').addClass('d-none');
                    prisActContainer.addClass('d-none');
                }
            }

            [strategicActType, acceptActInstitutionType].forEach(function (){
                $(this).on('change', function (){
                    controlCustomActFields();
                });
            });

            controlCustomActFields();

        });
    </script>

@endpush
