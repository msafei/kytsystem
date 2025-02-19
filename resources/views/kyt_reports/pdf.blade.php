<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KYT Report</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 9.5pt; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="center">PT. NIPPON SHOKUBAI INDONESIA</h2>
    <h3 class="center">KYT REPORT</h3>

    <table>
        <tr>
            <th>DEPARTMENT</th>
            <td>{{ $department_id }}</td>
            <th>DATE</th>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <th>SECTION / SHIFT</th>
            <td>{{ $shift }}</td>
            <th>TIME</th>
            <td>{{ $workingStart_workingEnd }}</td>
        </tr>
    </table>

    <h4>Potential Dangerous Point:</h4>
    <p>{{ $potentialDangerous }}</p>

    <h4>The Most Danger Point:</h4>
    <p>{{ $mostDanger }}</p>

    <h4>Countermeasures:</h4>
    <p>{{ $countermeasures }}</p>

    <h4>Key Word (Say Together lead by Instructor):</h4>
    <p>{{ $keyWord }}</p>

    <h4>Instructor / Sign:</h4>
    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
        </tr>
        <tr>
            <td>1</td>
            <td>{{ $instructors['instructors 1'] ?? 'Null' }}</td>
        </tr>
        <tr>
            <td>2</td>
            <td>{{ $instructors['instructors 2'] ?? 'Null' }}</td>
        </tr>
    </table>

    <h4>Attendants / Sign:</h4>
    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
        </tr>
        @for ($i = 1; $i <= 16; $i++)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $attendants["attendants $i"] ?? '-' }}</td>
            </tr>
        @endfor
    </table>

    <h4>Approval:</h4>
    <table>
        <tr>
            <th>Prepared By</th>
            <td>{{ $preparedBy }}</td>
            <th>Checked By</th>
            <td>{{ $CheckedBy }}</td>
        </tr>
        <tr>
            <th>Reviewed By</th>
            <td>{{ $reviewedBy }}</td>
            <th>Approved 1 By</th>
            <td>{{ $approvedBy1 }}</td>
        </tr>
        <tr>
            <th>Approved 2 By</th>
            <td colspan="3">{{ $approvedBy2 }}</td>
        </tr>
    </table>
</body>
</html>
