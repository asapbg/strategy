@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">

                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                            @foreach(\App\Http\Controllers\Admin\StrategicDocumentsController::SECTIONS as $s)
                                @if($item->id || $s != \App\Http\Controllers\Admin\StrategicDocumentsController::SECTION_FILES )
                                    <li class="nav-item">
                                        <a class="nav-link @if($section == $s) active @endif" id="{{ $s }}-tab" href="{{ route('admin.strategic_documents.edit', [$item, $s]) }}">{{ __('custom.strategic_documents.sections.'.$s) }}</a>
                                    </li>
                                @endif
                            @endforeach
                            @if($item->id)
                                <li class="nav-item">
                                    <button class="nav-link add_sd_document bg-success" data-url="{{ route('admin.strategic_documents.document.popup', ['sd' => $item]) }}">+ {{ trans_choice('custom.strategic_documents.documents', 1) }}</button>
                                </li>
                            @endif
                            @if($item->documents->count())
                                @foreach($item->documents as $d)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.strategic_documents.document.edit', [$d]) }}">{{ $d->title }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="card-body p-0">
                        <div class="tab-content" id="custom-tabsContent">
                            <div class="tab-pane fade active show pt-3" id="general" role="tabpanel"
                                 aria-labelledby="general-tab">
                                @include('admin.strategic_documents.'.$section)
                            </div>

{{--                            @if($item->id)--}}
{{--                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">--}}
{{--                                    @include('admin.strategic_documents.files')--}}
{{--                                </div>--}}
{{--                            @endif--}}
                        </div>
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

                    if (acceptActInstitutionTypeVal == '{{ \App\Models\AuthorityAcceptingStrategic::NATIONAL_ASSEMBLY }}') {
                        $('#act_number_field').hide();
                    } else {
                        $('#act_number_field').show();
                    }
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
