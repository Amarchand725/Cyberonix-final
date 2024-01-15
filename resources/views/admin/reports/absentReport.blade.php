<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title ?? 'Absent Report'}}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        td {
            font-size: 13px;
            font-weight: 500
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        th {
            /* background-color: #f2f2f2; */
            width: 20%;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">{{$title ?? ''}}</h2>

    <table align="center">
        <thead>
            <tr>
                <th>Emp Name</th>
                <th>Designation</th>
                <th>Shift</th>
                <th>R.A</th>
                <th>Absent Days</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($users) && !empty($users))
            @foreach($users as $empData)
            <tr>
                <td>{{ $empData['name'] ?? '' }}</td>
                <td>{{ $empData['designation'] ?? '' }}</td>
                <td>{{ $empData['shift'] ?? '' }}</td>
                <td>{{ $empData['r_a'] ?? '' }}</td>
                <td>{{ $empData['absent_days_count']  ?? ''}}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

</body>

</html>