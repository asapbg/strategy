<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your CSS styles here */
        body {
            font-size: 16px; /* Adjust the font size as needed */
        }

        table {
            width: 120%; /* Adjust the width as needed */
        }

        td, th {
            padding: 6px; /* Adjust the padding as needed */
        }

        tr {
            margin-bottom: 6px; /* Adjust the margin as needed */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>{{ trans('custom.strategic_documents') }}</h1>

<table>
    <thead>
    <tr>
        <th>{{ trans('custom.id') }}</th>
        <th>{{ trans('custom.title') }}</th>
        <th>{{ trans('custom.policy_area') }}</th>
        <th>{{ trans('custom.strategic_document_type') }}</th>
        <th>{{ trans('custom.accept_act_institution_type') }}</th>
        <th>{{ trans('custom.pris') }}</th>
        <th>{{ trans('custom.document_date') }}</th>
        <th>{{ trans('custom.public_consultation_link') }}</th>
        <th>{{ trans('custom.public_consultation_link') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $row)
        <tr>
            <td>{{ $row['id'] }}</td>
            <td>{{ $row['title'] }}</td>
            <td>{{ $row['policy_area'] }}</td>
            <td>{{ $row['strategic_document_type_id'] }}</td>
            <td>{{ $row['accept_act_institution_type_id'] }}</td>
            <td>{{ $row['pris_name'] }}</td>
            <td>{{ $row['document_date'] }}</td>
            <td>{{ $row['public_consultation'] }}</td>
            <td>{{ $row['active'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
