@php
$target = '';

if( $form == 'add'){
$target = 'store';
}else{
$target = 'update';
}

@endphp

@section('title', 'Tugas')
<div class="container py-3">
    <div class="row justify-content-md-center">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item active">Tugas</li>
            </ol>
        </div>


        @if ($form)
        <div class="col-md-12 mt-3">
            @if ($form === 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_tugas }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="row form-group mb-0">
                                <div class="col-md-{{ $form != 'add' ? '3' : '5' }}">
                                    <label for="matkul-id" class="mb-1">Mata Kuliah</label>
                                    <select class="form-control mb-2 @error('matkul')is-invalid @enderror"
                                        wire:model="matkul" id="matkul-id">
                                        <option value="" disabled>--Pilih Mata Kuliah--</option>
                                        @foreach ($matkuls as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('matkul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-{{ $form != 'add' ? '3' : '4' }}">
                                    <label for="batas_waktu" class="mb-1">Batas Waktu</label>
                                    <input type="datetime-local" id="batas_waktu"
                                        class="form-control mb-2  @error('batas_waktu')is-invalid @enderror"
                                        wire:model="batas_waktu" id="batas_waktu">
                                    @error('batas_waktu') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="pertemuan_ke" class="mb-1">Pertemuan Ke</label>
                                    <input type="number"
                                        class="form-control mb-2  @error('pertemuan_ke')is-invalid @enderror"
                                        wire:model="pertemuan_ke" placeholder="Pertemuan Ke" id="pertemuan_ke" min="1"
                                        max="18">
                                    @error('pertemuan_ke') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                @if ($form != 'add')
                                <div class="col-md-3">
                                    <label for="selesai" class="mb-1">Selesai Pada</label>
                                    <input type="datetime-local" id="selesai"
                                        class="form-control mb-2  @error('selesai')is-invalid @enderror"
                                        wire:model="selesai" placeholder="{{ $selesai }}">
                                    @error('selesai') <span class="text-danger mt-0 mb-5">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            {{-- end of row group --}}

                            <div class="row mt-0">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="deskripsi" class="mb-1">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi')is-invalid @enderror"
                                            wire:model="deskripsi" placeholder="Deskripsi" id="deskripsi" rows="3"
                                            aria-setsize="false"></textarea>
                                        @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-2 mt-0">
                                    <x-button-submit target="{{ $target }}">
                                    </x-button-submit>
                                </div>
                            </div>
                        </div>
                    </div> {{-- end of row form-group--}}
                </form>
        </div>
        {{-- end of --}}
        @endif

        {{-- table --}}
        <div class="col-md-12">
            {{-- button create --}}
            <div class="row my-2">
                <div class="col-md-10 mb-2">
                    <h5 class="card-title mb-0">Tugas</h5>
                    <small> <b>Tanggal Sekarang : {{ date('d F Y') }}</b> </small>
                </div>
                <div class="col-md-2 justify-content-end mb-1">
                    <x-button-create></x-button-create>
                </div>
            </div>

            <x-search-input></x-search-input>


            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mata Kuliah</th>
                            <th>Deskripsi</th>
                            <th>Batas Waktu</th>
                            <th>Sisa Waktu</th>
                            <th>Selesai</th>
                            <th>Pertemuan Ke</th>
                            <th>Dibuat Pada</th>
                            <th>Terkahir Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($all_tugas as $key => $tgs)
                        @php
                        $batasWaktu = new DateTime("$tgs->batas_waktu");
                        $today = new DateTime(date('Y-m-d'));

                        $batasWaktuCount = date('YmdHi', strtotime($tgs->batas_waktu));

                        $todayCount = date('YmdHi');

                        if($todayCount > $batasWaktuCount){
                        // jika waktu telah habis
                        $sisa = 0;
                        $selisih = 'Batas waktu telah habis!';

                        }elseif($today->diff($batasWaktu)->days == 0){
                        // jika sisa beberapa jam
                        $sisa = 1;
                        $selisih = '<p class="text-danger">Tugas akan segera berakhir!</p>';

                        }else{
                        $sisa = 1;
                        $selisih = $today->diff($batasWaktu)->days . ' hari lagi!';
                        }

                        @endphp
                        <tr class="table-active">
                            <td>{{ $all_tugas->firstItem() + $key }}
                            </td>
                            <td>{{ $tgs['matkul']->name }}</td>
                            <td>{{ nl2br($tgs->deskripsi) }}</td>
                            <td>{{ date('d F Y - H:i ', strtotime($tgs->batas_waktu)) }}</td>
                            <td>
                                {!! $selisih !!}
                            </td>
                            <td>
                                @php
                                if($tgs->selesai && $sisa){
                                // tugas selesai dan waktu masih ada
                                echo date('d F Y - H:i ',
                                strtotime($tgs->selesai)).'<i class="fas fa-check text-success ml-2"></i>';

                                }elseif($tgs->selesai && !$sisa){
                                // tugas selesai dan waktu habis
                                echo date('d F Y - H:i ',
                                strtotime($tgs->selesai)).'<i class="fas fa-check text-success ml-2"></i>';

                                }elseif(!$tgs->selesai && !$sisa){
                                // tugas gak selesai dan waktu habis
                                echo '<i class="fas fa-times text-danger"></i>';

                                }elseif(!$tgs->selesai && $sisa){
                                // tugas gak selesai dan waktu masih ada
                                echo '<i class="fas fa-question text-info"></i>';
                                }
                                @endphp
                            </td>
                            <td>{{ $tgs->pertemuan_ke }}</td>
                            <td>{{ $tgs->created_at->diffForHumans() }}</td>
                            <td>{{ $tgs->updated_at->diffForHumans() }}</td>
                            <td>
                                <button
                                    class="mb-2 btn btn-outline-{{ $selisih == 'Batas waktu telah habis!' && !$tgs->selesai ? 'warning' : 'info' }} btn-sm mb-2"
                                    wire:click="show('{{ $tgs->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="mb-2 btn btn-outline-danger btn-sm"
                                    wire:click="destroy('{{ $tgs->id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @php
                        $data_yg_ditampilkan = $loop->index+1;
                        @endphp
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Data tidak ada/ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- end of col--}}
    </div>
    {{-- end of row--}}

    <div class="d-none d-md-block">
        <div class="d-flex justify-content-between text-muted">
            <div>
                @if ($all_tugas->total())
                Menampilkan
                {{  $all_tugas->firstItem() .' sampai '. $all_tugas->lastItem()  .' dari total '. $all_tugas->total() }}
                data
                @endif
            </div>
            <div>
                {{ $all_tugas->links() }}
            </div>
        </div>
    </div>
    {{-- d-none d-md-block --}}

    <div class="d-sm-block d-md-none">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-2 text-center text-muted">
                @if ($all_tugas->total())
                Menampilkan
                {{  $all_tugas->firstItem() .' sampai '. $all_tugas->lastItem()  .' dari total '. $all_tugas->total() }}
                data
                @endif
            </div>
            <div class="col-sm-12">
                <div class="d-flex justify-content-center m-0">
                    {{ $all_tugas->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- d-sm-block d-md-none --}}


    {{-- tugas yg ga dikerjain --}}
    @php
    $count=0;
    @endphp
    <div class="row">
        <div class="col-md-12 mt-2">
            <h4 class="text-center mt-4 mb-0">Tugas yang belum/tidak kamu dikerjakan</h4>
            <h6 class="mb-4 mt-1 text-center">(Semester sekarang)</h6>
            <div class="row">
                @foreach ($tugas_yg_ga_selesai as $tgs)

                @if ($tgs['semester'])

                @foreach ($tgs['tugas'] as $tg)

                @php
                $count++;
                @endphp
                <div class="col-md-4">
                    <div class="card card-tugas mb-3">
                        <div class="card-body">
                            <p class="m-0 matkul">Mata Kuliah :
                                <b>{{ $tgs->name }}</b>
                            </p>
                            <p class="m-0">Pertemuan Ke :
                                <b>{{ $tg->pertemuan_ke }}</b>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach

                @endif

                @endforeach

            </div>
            <p class="text-center mt-3"><strong>Total : {{ $count }} Tugas</strong></p>
        </div>
    </div>


</div>
{{-- end of container--}}
