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
                            <div class="row form-group">
                                <div class="col-3">
                                    <label for="matkul-id">Mata Kuliah</label>
                                    <select class="form-control @error('matkul')is-invalid @enderror"
                                        wire:model="matkul" id="matkul-id">
                                        <option value="" disabled>--Pilih Mata Kuliah--</option>
                                        @foreach ($matkuls as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('matkul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-3">
                                    <label for="batas_waktu">Batas Waktu</label>
                                    <input type="datetime-local" id="datetimepicker"
                                        class="form-control @error('batas_waktu')is-invalid @enderror"
                                        wire:model="batas_waktu" id="batas_waktu">
                                    @error('batas_waktu') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-3">
                                    <label for="pertemuan_ke">Pertemuan Ke</label>
                                    <input type="number" class="form-control @error('pertemuan_ke')is-invalid @enderror"
                                        wire:model="pertemuan_ke" placeholder="Pertemuan Ke" id="pertemuan_ke" min="1"
                                        max="16">
                                    @error('pertemuan_ke') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-3">
                                    <label for="selesai">Selesai Pada</label>
                                    <input type="{{ $form == 'add' ? 'text' : 'datetime-local' }}"
                                        class="form-control @error('selesai')is-invalid @enderror" wire:model="selesai"
                                        placeholder="{{ $form == 'add' ? 'Terbuka ketika edit' : $selesai }}"
                                        {{ $form == 'add' ? 'readonly' : '' }}>
                                    @error('selesai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            {{-- end of row group --}}

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi')is-invalid @enderror"
                                            wire:model="deskripsi" placeholder="Deskripsi" id="deskripsi" rows="3"
                                            aria-setsize="false"></textarea>
                                        @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
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
                    <small>Tanggal Sekarang : {{ date('d-F-Y') }}</small>
                </div>
                <div class="col-md-2 justify-content-end mb-3">
                    <x-button-create></x-button-create>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mata Kuliah</th>
                            <th>Deskripsi</th>
                            <th>Batas Waktu</th>
                            <th>Sisa Hari</th>
                            <th>Selesai</th>
                            <th>Pertemuan</th>
                            <th>Dibuat Pada</th>
                            <th>Terkahir Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_tugas as $key => $tgs)
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
                        $selisih = '<p class="text-danger">Tersisa beberapa jam!</p>';

                        }else{
                        $sisa = 1;
                        $selisih = $today->diff($batasWaktu)->days . ' hari lagi!';
                        }

                        @endphp

                        <tr class="table-active">
                            <td>{{ $all_tugas->firstItem() + $key }}
                            </td>
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
                            <td>{{ $tgs->created_at->diffForHumans()  }}</td>
                            <td>{{ $tgs->updated_at->diffForHumans() }}</td>
                            <td>
                                @php if($selisih != 'Batas waktu telah habis!') : @endphp
                                <button class="mb-2 btn btn-outline-info btn-sm mb-2"
                                    wire:click="show('{{ $tgs->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @php endif @endphp
                                <button class="mb-2 btn btn-outline-danger btn-sm"
                                    wire:click="destroy('{{ $tgs->id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end m-0">
                {{ $all_tugas->links() }}
            </div>
        </div>
        {{-- end of col--}}

        {{-- tugas yg ga dikerjain --}}
        @php
        $count=0;
        @endphp
        <div class="col-md-12 mt-4">
            <h4 class="text-center my-4">Tugas yang tidak kamu dikerjakan</h4>
            <div class="row">
                @foreach ($tugas_yg_ga_selesai as $tgs)
                @foreach ($tgs['tugas'] as $tg)
                @php
                $count++;
                @endphp
                <div class="col-md-4">
                    <div class="card card-tugas mb-4">
                        <div class="card-body">
                            <p class="m-0 matkul">Mata Kuliah :
                                <strong>{{ $tgs->name }}</strong>
                            </p>
                            <p class="m-0">Pertemuan Ke :
                                <strong>{{ $tg->pertemuan_ke }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
            <p class="text-center mt-3"><strong>Total : {{ $count }} Tugas</strong></p>
        </div>
    </div>
    {{-- end of row--}}
</div>
{{-- end of container--}}
