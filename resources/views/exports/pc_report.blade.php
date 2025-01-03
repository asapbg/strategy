@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $data['title'] }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            @page {
                margin: 20px 0 !important;
                /*padding: 20px 20px !important;*/
            }
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 10pt;
            }
            table, th, td {
                border: 2px solid black;
                border-collapse: collapse;
                padding: 4px 7px;
            }
        </style>
    </head>
    <body style="text-align: left;padding-left: 50px; padding-right: 50px;">
        <div>
@endif
            <table style="border-collapse:collapse;">
                @php($colspan = 9)
                <tr>
                    <td colspan="{{ $colspan }}" style="background: #d9d7d7; font-weight: bold;"><b>{{ mb_strtoupper($data['title']) }}</b></td>
                </tr>
                <tr>
                    <th>{{ __('custom.name') }}</th>
                    <th>{{ trans_choice('custom.field_of_actions', 1) }}</th>
                    <th>{{ __('custom.status') }}</th>
                    <th>{{ trans_choice('custom.institution', 1) }}</th>
                    <th>{{ trans_choice('custom.act_type', 1) }}</th>
                    <th>Срок (дни)</th>
                    <th>{{ __('site.public_consultation.short_term_motive_label') }}</th>
                    <td>{{ __('custom.pc_reports.missing_documents') }}</td>
                    <th>{{ trans_choice('custom.comment', 2) }}</th>
                    <th>{{ __('custom.pc_reports.standard.comment_report') }}</th>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        @php($existDocTypes = json_decode($row->doc_types))
                        <tr>
                            <td>@if(isset($isPdf) && $isPdf)<a href="{{ route('public_consultation.view', $row->id) }}" target="_blank">{{ $row->title }}</a>@else {{ $row->title }}@endif</td>
                            <td>{{ $row->fieldOfAction?->name }}</td>
                            <td>{{ $row->inPeriod }}</td>
                            <td>@if($row->importer_institution_id == env('DEFAULT_INSTITUTION_ID')){{ '' }}@else{{ $row->importerInstitution?->name }}@endif</td>
                            <td>{{ $row->actType?->name }}</td>
                            <td>{{ $row->daysCnt }}</td>
                            <td>{!! $row->short_term_reason !!}</td>
                            <td>
                                @php($requiredDocs = \App\Enums\DocTypesEnum::pcRequiredDocTypesByActType($row->act_type_id))
                                @if(sizeof($requiredDocs))
                                    @foreach($requiredDocs as $rd)
                                        @if(empty($existDocTypes) || !in_array($rd, $existDocTypes))
                                            {{ ' '.__('custom.public_consultation.doc_type.'.$rd) }};
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $row->comments->count() }}</td>
                            <td>@if($row->proposalReport->count()){{ 'Да' }}@else{{ 'Не' }}@endif</td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
