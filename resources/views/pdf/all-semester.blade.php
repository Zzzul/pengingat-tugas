<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar semua semester</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td,
        table th {
            border: 1px solid #dddd;
            padding: 8px;
        }

        table tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #158CBA;
            color: white;
        }

    </style>
</head>

<body>
    <div class="container py-3">
        <center>
            <h2 style="margin-bottom: 0px">Daftar Semester</h2>
        </center>

        <hr style="margin-bottom: 15px">

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semesters as $semester)
                    <tr>
                        <td>{{ $loop->iteration }}
                        <td>{{ $semester->semester_ke }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center">Data tidak ada/ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
