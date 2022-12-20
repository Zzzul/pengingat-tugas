<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar semua Tugas</title>

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

    {{-- <link href="{{ asset('css/bootstrap-lumen.min.css') }}" rel="stylesheet"> --}}
</head>

<body>
    <div class="container py-3">
        <center>
            <h2 style="margin-bottom: 0px">Daftar Semua Tugas</h2>
        </center>

        <hr style="margin-bottom: 15px">

        <table id="tugas">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mata Kuliah</th>
                    <th>Deskripsi</th>
                    <th>Batas Waktu</th>
                    <th>Sisa Waktu</th>
                    <th>Selesai</th>
                    <th>Pertemuan Ke</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_tugas as $key => $matkul)
                    @foreach ($matkul->tugas as $tg)
                        @php
                            $batasWaktu = new DateTime("$tg->batas_waktu");
                            $batasWaktuCount = date('YmdHi', strtotime($tg->batas_waktu));
                            $today = new DateTime(date('Y-m-d'));

                            $todayCount = date('YmdHi');

                            if ($todayCount > $batasWaktuCount) {
                                // jika waktu telah habis
                                $sisa = 0;
                                $selisih = 'Sisa waktu telah habis!';
                            } elseif ($today->diff($batasWaktu)->days == 0) {
                                // jika sisa beberapa jam
                                $sisa = 1;
                                $selisih = 'Tugas akan segera berakhir!';
                            } else {
                                $sisa = 1;
                                $selisih = $today->diff($batasWaktu)->days . ' hari lagi!';
                            }

                        @endphp

                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $matkul->name }}</td>
                            <td>{!! nl2br($tg->deskripsi) !!}</td>
                            <td>{{ date('d F Y - H:i ', strtotime($tg->batas_waktu)) }}</td>
                            <td>
                                {!! $selisih !!}
                            </td>
                            <td>
                                @php
                                    if ($tg->selesai && $sisa) {
                                        // tugas selesai dan waktu masih ada
                                        echo date('d F Y - H:i ', strtotime($tg->selesai));
                                    } elseif ($tg->selesai && !$sisa) {
                                        // tugas selesai dan waktu habis
                                        echo date('d F Y - H:i ', strtotime($tg->selesai));
                                    } elseif (!$tg->selesai && !$sisa) {
                                        // tugas gak selesai dan waktu habis
                                        echo '-';
                                    } elseif (!$tg->selesai && $sisa) {
                                        // tugas gak selesai dan waktu masih ada
                                        echo '?';
                                    }
                                @endphp
                            </td>
                            <td>{{ $tg->pertemuan_ke }}</td>
                            <td>{{ $matkul->semester->semester_ke }}</td>
                        </tr>

                    @endforeach
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
