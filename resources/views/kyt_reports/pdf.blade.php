<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYT Report</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 210mm;
            margin: auto;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10mm;
        }
        .company {
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        .section-title {
            font-weight: bold;
            padding: 5px;
        }
        .section-title-center {
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .name{
            min-width: 100px;
        }
        .sign{
            padding-right: 20px;
        }

        .sign-appr{
            padding: 40px 0px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <p class="company"><strong>PT. NIPPON SHOKUBAI INDONESIA</strong></p>
        <p class="title">KYT REPORT</p>

        <table>
            <tr>
                <td class="section-title">DEPARTMENT</td>
                <td>: {{ $department_id }}</td>
                <td class="section-title">DATE</td>
                <td>: {{$date}}</td>
            </tr>
            <tr>
                <td class="section-title">SECTION / SHIFT</td>
                <td>: {{$shift}}</td>
                <td class="section-title">TIME</td>
                <td>: {{$workingStart_workingEnd}}</td>
            </tr>
        </table>

        <table>
            <tr><td class="section-title">TITLE :  {{$title}}</td></tr>
            <tr><td class="section-title">POTENTIAL DANGEROUS POINT :</td></tr>
            <tr><td class="center">{{$potentialDangerous}}</td></tr>
            <tr><td class="section-title">THE MOST DANGER POINT :</td></tr>
            <tr><td class="center">{{$mostDanger}}</td></tr>
            <tr><td class="section-title">STATEMENT / COUNTERMEASURE :</td></tr>
            <tr><td class="center">{{$countermeasures}}</td></tr>
            <tr><td class="section-title">KEY WORD (Say Together lead by Instructor) :</td></tr>
            <tr><td class="center">{{$keyWord}}</td></tr>
        </table>

        <table>
            <tr>
                <td class="section-title-center" rowspan="2">INSTRUCTOR / SIGN :</td>
                <td>1</td>name
                <td class="name" class="name">{{ $instructors_1 ?? '-' }}</td>
                <td class="sign">/</td>
            </tr>
            <tr>
                <td>2</td>
                <td class="name">{{ $instructors_2 ?? '-' }}</td>
                <td class="sign">/</td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="section-title-center" rowspan="8">ATTENDANT / SIGN :</td>
                <td>1</td><td class="name">{{ $attendants_1 ?? '-' }}</td><td class="sign" class="sign">/</td>
                <td>9</td><td class="name">{{ $attendants_9 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>2</td><td class="name">{{ $attendants_2 ?? '-' }}</td><td class="sign">/</td>
                <td>10</td><td class="name">{{ $attendants_10 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>3</td><td class="name">{{ $attendants_3 ?? '-' }}</td><td class="sign">/</td>
                <td>11</td><td class="name">{{ $attendants_11 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>4</td><td class="name">{{ $attendants_4 ?? '-' }}</td><td class="sign">/</td>
                <td>12</td><td class="name">{{ $attendants_12 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>5</td><td class="name">{{ $attendants_5 ?? '-' }}</td><td class="sign">/</td>
                <td>13</td><td class="name">{{ $attendants_13 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>6</td><td class="name">{{ $attendants_6 ?? '-' }}</td><td class="sign">/</td>
                <td>14</td><td class="name">{{ $attendants_14 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>7</td><td class="name">{{ $attendants_7 ?? '-' }}</td><td class="sign">/</td>
                <td>15</td><td class="name">{{ $attendants_15 ?? '-' }}</td><td class="sign">/</td>
            </tr>
            <tr>
                <td>8</td><td class="name">{{ $attendants_8 ?? '-' }}</td><td class="sign">/</td>
                <td>16</td><td class="name">{{ $attendants_16 ?? '-' }}</td><td class="sign">/</td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="section-title">Prepared By</td>
                <td class="section-title">Checked by:</td>
                <td class="section-title">Reviewed by:</td>
                <td class="section-title">APPROVED1 BY :</td>
                <td class="section-title">APPROVED2 BY :</td>
            </tr>
            <tr>
                <td class="center sign-appr">{{$preparedBy}}<br></td>
                <td class="center sign-appr">{{$checkedBy}}</td>
                <td class="center sign-appr">{{$reviewedBy}}</td>
                <td class="center sign-appr">{{$approvedBy1}}</td>
                <td class="center sign-appr">{{$approvedBy2}}</td>
            </tr>
        </table>
    </div>
</body>
</html>