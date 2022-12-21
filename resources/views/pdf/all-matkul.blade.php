<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar semua mata kuliah</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        #matkul {
            border-collapse: collapse;
            width: 100%;
        }

        #matkul td,
        #matkul th {
            border: 1px solid #dddd;
            padding: 8px;
        }

        #matkul tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        #matkul th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #158CBA;
            color: white;
        }

    </style>

    {{-- <link href="{{ asset('css/bootstrap-lumen.min.css') }}" rel="stylesheet"> --}}
</head>

<body>
    <div class="container py-3">
        <center>
            <h2 style="margin-bottom: 0px">Daftar Semua Mata Kuliah</h2>
        </center>

        <hr style="margin-bottom: 15px">

        <table id="matkul" class="table table-sm table-striped">
            <thead>
                <tr class="table-primary">
                    <th>#</th>
                    <th>Mata Kuliah</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>SKS</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matkuls as $matkul)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $matkul->name }}</td>
                        <td>{{ Str::ucfirst($matkul->hari) }}</td>
                        <td>{{ date('H:i', strtotime($matkul->jam_mulai)) . '-' . date('H:i', strtotime($matkul->jam_selesai)) }}
                        </td>
                        <td>{{ $matkul->sks }}</td>
                        <td>{{ $matkul['semester']->semester_ke }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data tidak ada/ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
