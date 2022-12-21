<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tugas yang belum/tidak kamu dikerjakan</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        #tugas {
            border-collapse: collapse;
            width: 100%;
        }

        #tugas td,
        #tugas th {
            border: 1px solid #dddd;
            padding: 8px;
        }

        #tugas tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        #tugas th {
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
            <h2 style="margin-bottom: 0px">Daftar tugas yang belum/tidak kamu dikerjakan</h2>
        </center>

        <hr style="margin-bottom: 15px">

        <table id="tugas">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mata Kuliah</th>
                    <th>Pertemuan ke</th>
                    <th>Batas Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tugas_yg_ga_dikerjain as $tugas)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tugas->name }}</td>
                        <td>{{ $tugas->pertemuan_ke }}</td>
                        @php
                            $batasWaktu = new DateTime("$tugas->batas_waktu");
                            $today = new DateTime(date('Y-m-d'));
                        @endphp

                        @if ($batasWaktu > $today)
                            <td>
                                {{-- jika sudah pada hari yang sama hanya beda jam --}}
                                @if ($today->diff($batasWaktu)->days == 0)
                                    Tugas akan segera berakhir!
                                @else
                                    {{ date('d F Y - H:i', strtotime($tugas->batas_waktu)) }}
                                @endif
                            </td>
                        @else
                            <td>Telah habis!</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center">Data tidak ada/ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
