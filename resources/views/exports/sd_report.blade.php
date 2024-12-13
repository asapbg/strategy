@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $data['title'] }}</title>
        <style>body { font-family: DejaVu Sans, sans-serif !important; }</style>
        <style>
            @page {
                margin: 0 !important;
                padding: 5px 0 !important;
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
                <tr>
                    <th colspan="5" style="background: #d9d7d7; font-weight: bold; text-align: center;"><b>{{ mb_strtoupper($data['title']) }}</b></th>
                </tr>
                <tr>
                    <th><b>{{ __('custom.title') }}</b></th>
                    <th><b>{{ __('site.strategic_document.level') }}</b></th>
                    <th><b>{{ trans_choice('custom.field_of_actions', 1) }}</b></th>
                    <th><b>{{ trans_choice('custom.authority_accepting_strategic', 1) }}</b></th>
                    <th><b>{{ __('custom.validity') }}</b></th>
                </tr>
                @if(isset($data['rows']) && $data['rows']->count())
                    @foreach($data['rows'] as $row)
                        <tr>
                            <td><a href="{{ route('strategy-document.view', $row->id) }}">{{ $row->title }}</a></td>
                            <td>{{ $row->strategic_document_level_id ? __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($row->strategic_document_level_id)) : '---' }}</td>
                            <td>{{ $row->policyArea ? $row->policyArea->name : '---' }}</td>
                            <td>{{ $row->acceptActInstitution ? $row->acceptActInstitution->name : '---' }}</td>
                            <td>
                                @if($row->document_date_accepted && $row->document_date_expiring)
                                    {{ displayDate($row->document_date_accepted) .' - '. displayDate($row->document_date_expiring) }}
                                @elseif($row->document_date_accepted || $row->document_date_expiring)
                                    @if($row->document_date_accepted)
                                        {{ __('custom.from') .' '.displayDate($row->document_date_accepted) }}
                                    @else
                                        {{ __('custom.to') .' '.displayDate($row->document_date_expiring) }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
@if(isset($isPdf) && $isPdf)
        </div>
    </body>
</html>
@endif
