<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 14px; /* Adjust the font size for the header cells */
        }
    </style>
</head>
<body>
<h1>{{ trans('custom.strategic_documents') }}</h1>

<table>
    <thead>
    <tr>
        <th>{{ trans('custom.stategic_document_status') }}</th>
        <th>{{ trans('custom.stategic_document_active_period_of_time') }}</th>
        <th>{{ trans('custom.stategic_document_by_category') }}</th>
        <th>{{ trans('custom.strategic_document_accepted_by_national_assemly') }}</th>
        <th>{{ trans('custom.strategic_document_tile') }}</th>
        <th>{{ trans('custom.policy_area') }}</th>
        <th>{{ trans_choice('custom.authority_accepting_strategic', 1) }}</th>
        <th>{{ trans('custom.strategic_document_valid_status') }}</th>
        <th>{{ trans('custom.strategic_documents_total_count_in_report') }}</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row['status'] }}</td>
                <td>{{ $row['period_of_time'] }}</td>
                <td>{{ $row['category'] }}</td>
                <td>{{ $row['strategic_document_type_id'] }}</td>
                <td>{!! $row['title'] !!}</td>
                <td>{{ $row['policy_area'] }}</td>
                <td>{{ $row['accept_institution'] }}</td>
                <td>{{ $row['valid_status'] }}</td>
                <td>{{ $row['count'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
