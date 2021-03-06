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
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Tugas</li>
            </ol>
        </div>
    </div>

    {{-- tugas yg ga dikerjain --}}
    @if (!$tugas_yg_ga_selesai->isEmpty())
    <div class="row justify-content-md-center mt-2">
        <div class="col-md-12">
            @role('admin')
            <h5 class="text-center mb-3">Tugas yang belum/tidak dikerjakan</h5>
            @endrole

            @role('user|demo')
            <h5 class="text-center mb-0">Tugas yang belum/tidak kamu dikerjakan</h5>
            <h6 class="mt-1 mb-3 text-center">(Semester sekarang)</h6>
            @endrole
        </div>
        @foreach ($tugas_yg_ga_selesai as $tgs)
        <div class="col-md-4">
            <div class="card card-hover mb-3">
                <div class="card-body p-3">
                    @role('admin')
                    <p class="m-0">
                        User :
                        <b>{{ $tgs->user_fullname }}</b> {!! $tgs->id_user
                        == auth()->id() ? '<i class="fas fa-check-circle"></i>' : '' !!}
                    </p>
                    <p class="m-0">Semester :
                        <b>{{ $tgs->semester_ke }}</b>
                    </p>
                    @endrole

                    <p class="m-0">Mata Kuliah :
                        <b>{{ $tgs->name }}</b>
                    </p>
                    <p class="m-0">Pertemuan Ke :
                        <b>{{ $tgs->pertemuan_ke }}</b>
                    </p>

                    @if (date('YmdHi', strtotime($tgs->batas_waktu)) > date('YmdHi'))
                    <p class="m-0">Batas Waktu :
                        <b>{{ date('d F Y - H:i', strtotime($tgs->batas_waktu)) }}</b>
                    </p>
                    @else
                    <p class="m-0">Batas Waktu : Telah habis
                    </p>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="row justify-content-md-center mt-2">
        @if ($form)
        <div class="col-md-12 mt-3 mb-0">
            @role('admin')
            @if ($milik_user)
            <div class="alert alert-info" role="alert">
                Tugas ini milik : <span
                    class="font-weight bold">{{ '@'. $milik_user->username .' - '. $milik_user->name  }}</span>
            </div>
            @endif
            @endrole


            @if ($form === 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_tugas }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="row form-group mb-0">

                                <div class="col-md-{{ $form != 'add' ? '3' : '5' }} mb-1">
                                    <label for="matkul-id" class="mb-1">
                                        Mata Kuliah
                                    </label>
                                    <select
                                        class="form-control @error('matkul')is-invalid @enderror{{ $matkuls->isEmpty() ? 'is-invalid' : '' }}"
                                        wire:model="matkul" id="matkul-id">
                                        <option value="" disabled>--Pilih Mata Kuliah--</option>
                                        @forelse ($matkuls as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->name }}</option>
                                        @empty
                                        <option value="" disabled>Mata kuliah masih kosong!</option>
                                        @endforelse
                                    </select>
                                    @error('matkul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-{{ $form != 'add' ? '3' : '4' }} mb-1">
                                    <label for="batas_waktu" class="mb-1">Batas Waktu</label>
                                    <input type="datetime-local" id="batas_waktu"
                                        class="form-control @error('batas_waktu')is-invalid @enderror"
                                        wire:model="batas_waktu" id="batas_waktu">
                                    @error('batas_waktu') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-3 mb-1">
                                    <label for="pertemuan_ke" class="mb-1">Pertemuan Ke</label>
                                    <input type="number" class="form-control @error('pertemuan_ke')is-invalid @enderror"
                                        wire:model="pertemuan_ke" placeholder="Pertemuan Ke" id="pertemuan_ke" min="1"
                                        max="18">
                                    @error('pertemuan_ke') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                @if ($form != 'add')
                                <div class="col-md-3 mb-1">
                                    <label for="selesai" class="mb-1">Selesai Pada</label>
                                    <input type="datetime-local" id="selesai"
                                        class="form-control @error('selesai')is-invalid @enderror" wire:model="selesai"
                                        placeholder="{{ $selesai }}">
                                    @error('selesai') <span class="text-danger mt-0 mb-5">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            {{-- end of row group --}}

                            <div class="row mt-0">
                                <div class="col-md-10 mb-1">
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
            <div class="row">
                <div class="col-md-10 mb-2">
                    <h5 class="card-title mb-0">Tugas</h5>
                    <p class="mb-0"> <b>Tanggal Sekarang : {{ date('d F Y') }}</b> </p cmlaclass="mb-0">
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
                            @role('admin')
                            <th>User</th>
                            @endrole
                            <th>Mata Kuliah</th>
                            <th>Deskripsi</th>
                            <th>Batas Waktu</th>
                            <th>Sisa Waktu</th>
                            <th>Selesai</th>
                            <th>Pertemuan Ke</th>
                            <th>Dibuat Pada</th>
                            <th>Terkahir Diubah</th>
                            <th>Aksi
                                <img wire:loading wire:target="show"
                                    src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1" alt="Loading..">

                                <img wire:loading wire:target="triggerConfirm"
                                    src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1" alt="Loading..">
                            </th>
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

                            @role('admin')
                            <td>{{ $tgs['user']->name }}
                                {!! $tgs['user']->id == auth()->id() ? '<i class="fas fa-check-circle"></i>' : '' !!}
                            </td>
                            @endrole

                            <td>{{ $tgs['matkul']->name }}</td>
                            <td>{!! nl2br($tgs->deskripsi) !!}</td>
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
                                    wire:loading.attr="disabled" wire:click="show('{{ $tgs->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="mb-2 btn btn-outline-danger btn-sm" wire:loading.attr="disabled"
                                    wire:click="triggerConfirm('{{ $tgs->id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
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

</div>
{{-- end of container--}}
