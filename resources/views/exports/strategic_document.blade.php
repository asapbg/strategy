<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $strategicDocument->title }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 22px;
            }
            table, th, td {
                border: 2px solid black;
                border-collapse: collapse;
                padding: 10px 15px;
            }
            .myp {
                font-weight: bold;
            }
            .myp span {
                font-weight: normal !important;
            }
        </style>
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div>
            <h2 style="text-align: center; font-size: 35px !important;">
                {{ trans_choice('custom.strategic_documents', 1) }}<br>
                {{ $strategicDocument->title }}
            </h2>
            @if($strategicDocument->description)
                <div style="margin:10px 30px;">
                    {{ htmlToText($strategicDocument->description) }}
                </div>
            @endif
            <table style="width: 100%;">
                <tr>
                    <td class="myp">
                        @if($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value)
                            {{ trans_choice('custom.field_of_actions', 1) }}:
                        @elseif($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::AREA->value)
                            {{ trans_choice('site.strategic_document.areas', 1) }}:
                        @elseif($strategicDocument->strategic_document_level_id == \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value)
                            {{ trans_choice('custom.municipalities', 1) }}:
                        @endif
                    </td>
                    <td>
                        {{ $strategicDocument->policyArea?->name }}
                    </td>
                </tr>
                @if($strategicDocument->strategic_document_level_id)
                    <tr>
                        <td class="myp">
                            {{ __('site.strategic_document.level') }}
                        </td>
                        <td>
                            {{ $strategicDocument->strategic_document_level_id ? __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($strategicDocument->strategic_document_level_id)) : '---' }}
                        </td>
                    </tr>
                @endif
                @if($strategicDocument->documentType)
                    <tr>
                        <td class="myp">
                            {{ trans_choice('custom.strategic_document_type', 1) }}
                        </td>
                        <td>
                            {{ $strategicDocument->documentType->name ?? __('custom.unidentified') }}
                        </td>
                    </tr>
                @endif
                @if($strategicDocument->parentDocument)
                    <tr>
                        <td class="myp">
                            {{ trans_choice('custom.document_to', 1) }}
                        </td>
                        <td>
                            @if($strategicDocument->parent_document_id)
                                {{ $strategicDocument->parentDocument?->title }}
                            @else
                                {{ trans_choice('custom.strategic_document_link_missing', 1) }}
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="myp">
                        {{ __('custom.effective_at') }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($strategicDocument->document_date_accepted)->format('d.m.Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="myp">
                        {{ trans_choice('custom.date_expiring', 1) }}
                    </td>
                    <td>
                        @if($strategicDocument->document_date_expiring)
                            {{ \Carbon\Carbon::parse($strategicDocument->document_date_expiring)->format('d.m.Y') }}
                        @else
                            {{ trans_choice('custom.date_indefinite_name', 1) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="myp">
                        {{ trans_choice('custom.acceptment_act', 1) }}
                    </td>
                    <td>
                        @if ($strategicDocument->pris?->doc_num && $strategicDocument->pris?->published_at)
                            {{ $strategicDocument->pris?->actType?->name . ' №/' . $strategicDocument->pris?->doc_num . '/' . $strategicDocument->pris?->doc_date }}
                        @else
                            {{ $strategicDocument->strategic_act_number }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="myp">
                        {{ trans_choice('custom.public_consultation_link', 1) }}
                    </td>
                    <td>
                        {{ $strategicDocument->publicConsultation?->title }}
                    </td>
                </tr>
                @isset($strategicDocument->link_to_monitorstat)
                    <tr>
                        <td class="myp">
                            {{ trans_choice('custom.link_to_monitorstrat', 1) }}
                        </td>
                        <td>
                            {!! htmlspecialchars_decode($strategicDocument->description) !!}
                        </td>
                    </tr>
                @endisset
                @if($strategicDocument->filesByLocale->count())
                    <tr>
                        <td class="myp">
                            {{ trans_choice('custom.files', 2) }}
                        </td>
                        <td>
                            @foreach ($strategicDocument->filesByLocale as $f)
                                <a style="display: block;" href="{{ route('strategy-document.download-file', $f) }}" title="{{ __('custom.download') }}">
                                    {{ $f->description ?? $f->filename }} - {{ displayDate($f->created_at) }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>

            @if(isset($documents) && sizeof($documents))
                <h3 style="text-align: center; font-size: 28px !important; margin-top: 30px;">{{ trans_choice('custom.strategic_documents.documents', 2) }}</h3>
                @foreach($documents as $d)
                    @include('exports.strategic_document_tree_element')
                @endforeach
            @endif

        </div>
    </body>
</html>
