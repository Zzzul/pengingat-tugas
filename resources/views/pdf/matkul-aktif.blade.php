<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar mata kuliah semester sekarang</title>

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
            <h2 style="margin-bottom: 0px">Daftar Mata Kuliah</h2>
            <h4 style="margin-top: 2px;">Semester Sekarang({{ $jadwal ? $jadwal['0']->semester->semester_ke : '' }})
            </h4>
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
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $jdwl)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jdwl->name }}</td>
                        <td>{{ Str::ucfirst($jdwl->hari) }}</td>
                        <td>{{ date('H:i', strtotime($jdwl->jam_mulai)) . '-' . date('H:i', strtotime($jdwl->jam_selesai)) }}
                        </td>
                        <td>{{ $jdwl->sks }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center">Data tidak ada/ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
