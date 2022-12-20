@php
$target = '';

if ($form == 'add') {
    $target = 'store';
} else {
    $target = 'update';
}
@endphp
@section('title', 'Mata Kuliah')
    <div class="container py-3">
        <div class="row justify-content-md-center">

            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Mata Kuliah</li>
                </ol>
            </div>
        </div>

        @if (!$jadwal_hari_ini->isEmpty())
            <div class="row justify-content-md-center mt-2">
                <div class="col-md-12">
                    <h4 class="text-center mb-3">Jadwal Mata Kuliah Hari Ini</h4>
                </div>

                @foreach ($jadwal_hari_ini as $jdwl)
                    <div class="col-md-4">
                        <div class="card card-hover shadow mb-3">
                            <div class="card-body p-3">
                                <p class="m-0">Mata Kuliah : {{ $jdwl->name }}</p>
                                <p class="m-0">Waktu :
                                    {{ ucfirst($jdwl->hari) . ' ' . date('H:i', strtotime($jdwl->jam_mulai)) . ' - ' . date('H:i', strtotime($jdwl->jam_selesai)) }}
                                </p>
                                <p class="m-0">SKS : {{ $jdwl->sks }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row justify-content-md-center mt-2 mb-0">

            <div class="col-md-12 mt-0">
                {{-- button create --}}
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <h5 class="card-title mb-0 pt-1 mr-2">Mata Kuliah</h5>
                    </div>

                    <div class="col-md-2 pl-5 pr-0">
                        <div class="btn-group ml-5" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" target="blank" href="{{ route('pdf.matkul.all') }}">Semua mata
                                    kuliah</a>

                                <a class="dropdown-item" target="blank" href="{{ route('pdf.matkul.aktif') }}">Mata kuliah
                                    semester
                                    sekarang</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-button-create></x-button-create>
                    </div>
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-body">
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
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th>SKS</th>
                                        <th>Semester</th>
                                        <th>Dibuat Pada</th>
                                        <th>Terakhir Diubah</th>
                                        <th>Aksi
                                            <img wire:loading wire:target="show"
                                                src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1"
                                                alt="Loading..">

                                            <img wire:loading wire:target="triggerConfirm"
                                                src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1"
                                                alt="Loading..">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($matkuls as $key => $mk)

                                        <tr>
                                            <td>{{ $matkuls->firstItem() + $key }}</td>
                                            @role('admin')
                                            <td>{{ $mk['user']->name }}
                                                {!! $mk['user']->id == auth()->id() ? '<i class="fas fa-check-circle"></i>' : '' !!}
                                            </td>
                                            @endrole
                                            <td>{{ $mk->name }}</td>
                                            <td>{{ Str::ucfirst($mk->hari) }}</td>
                                            <td>{{ date('H:i', strtotime($mk->jam_mulai)) . ' - ' . date('H:i', strtotime($mk->jam_selesai)) }}
                                            </td>
                                            <td>{{ $mk->sks }}</td>
                                            <td>{{ $mk['semester']->semester_ke }}</td>
                                            <td>{{ $mk->created_at->diffForHumans() }}</td>
                                            <td>{{ $mk->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <button class="mb-1 btn btn-outline-primary btn-sm mr-1"
                                                    wire:loading.attr="disabled" wire:click="show('{{ $mk->id }}')"
                                                    data-toggle="modal" data-target="#exampleModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button class="mb-1 btn btn-outline-danger btn-sm"
                                                    wire:loading.attr="disabled"
                                                    wire:click="triggerConfirm('{{ $mk->id }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Data tidak ada/ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end of col --}}
        </div>
        {{-- end of row --}}

        <div class="d-none d-md-block">
            <div class="d-flex justify-content-between text-muted">
                <div>
                    @if ($matkuls->total())
                        Menampilkan
                        {{ $matkuls->firstItem() . ' sampai ' . $matkuls->lastItem() . ' dari total ' . $matkuls->total() }}
                        data
                    @endif
                </div>
                <div>
                    {{ $matkuls->links() }}
                </div>
            </div>
        </div>
        {{-- d-none d-md-block --}}

        <div class="d-sm-block d-md-none">
            <div class="row justify-content-center">
                <div class="col-sm-12 mb-2 text-center text-muted">
                    @if ($matkuls->total())
                        Menampilkan
                        {{ $matkuls->firstItem() . ' sampai ' . $matkuls->lastItem() . ' dari total ' . $matkuls->total() }}
                        data
                    @endif
                </div>
                <div class="col-sm-12">
                    <div class="d-flex justify-content-center m-0">
                        {{ $matkuls->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- d-sm-block d-md-none --}}

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ $form === 'add' ? 'Tambah Data Mata Kuliah' : 'Edit Mata Kuliah' }}
                            <div wire:loading="form">
                                <img src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" alt="Loading..." width="23px">
                            </div>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:loading.attr="disabled" wire:click="hideForm()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($form)
                            @role('admin')
                            @if ($milik_user)
                                <div class="alert alert-primary" role="alert">
                                    Mata Kuliah ini milik : <span
                                        class="font-weight bold">{{ '@' . $milik_user->username . ' - ' . $milik_user->name }}</span>
                                </div>
                            @endif
                            @endrole

                            @if ($form == 'add')
                                <form wire:submit.prevent="store" autocomplete="off" class="mb-0">
                                @else
                                    <form wire:submit.prevent="update('{{ $id_matkul }}')" autocomplete="off"
                                        class="mb-0">
                            @endif
                            <div class="row form-group mb-0">
                                <div class="col-md-10 mb-0">
                                    <div class="row form-group">
                                        {{-- matkul --}}
                                        <div class="col-md-6 mb-2">
                                            <label for="name" class="mb-1">Mata Kuliah</label>
                                            <input type="text" class="form-control @error('name')is-invalid @enderror"
                                                wire:model.defer="name" placeholder="Nama Mata Kuliah" id="name">
                                            @error('name') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- semester --}}
                                        <div class="col-md-3 mb-2">
                                            <label for="semester-id" class="mb-1">Semester</label>
                                            <select
                                                class="form-control @error('semester_id')is-invalid @enderror{{ $semesters->isEmpty() ? 'is-invalid' : '' }}"
                                                    wire:model.defer="semester_id" id="semester-id">
                                                    <option value="" disabled>--Pilih Semester--</option>
                                                    @forelse ($semesters as $sms)
                                                        <option value="{{ $sms->id }}">{{ $sms->semester_ke }}
                                                        </option>
                                                    @empty
                                                        <option value="" disabled>Semester masih kosong!</option>
                                                    @endforelse
                                                </select>
                                                @error('semester_id') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- sks --}}
                                            <div class="col-md-3 mb-2">
                                                <label for="sks" class="mb-1">SKS</label>
                                                <input type="number" class="form-control @error('sks')is-invalid @enderror"
                                                    wire:model.defer="sks" placeholder="SKS" id="sks" min="1" max="6">
                                                @error('sks') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- hari --}}
                                            <div class="col-md-4">
                                                <label for="hari" class="mb-1">Hari</label>
                                                <select class="form-control @error('hari')is-invalid @enderror"
                                                    wire:model.defer="hari" id="hari">
                                                    <option value="" disabled>--Pilih Hari--</option>
                                                    <option value="senin">Senin</option>
                                                    <option value="selasa">Selasa</option>
                                                    <option value="rabu">Rabu</option>
                                                    <option value="kamis">Kamis</option>
                                                    <option value="jumat">Jumat</option>
                                                    <option value="sabtu">Sabtu</option>
                                                </select>
                                                @error('hari') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- jam mulai --}}
                                            <div class="col-md-4">
                                                <label for="jam-mulai" class="mb-1">Jam Mulai</label>
                                                <input type="time" class="form-control @error('jam_mulai')is-invalid @enderror"
                                                    wire:model.defer="jam_mulai" placeholder="jam_mulai" id="jam-mulai">
                                                @error('jam_mulai') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- jam selesai --}}
                                            <div class="col-md-4">
                                                <label for="jam-selesai" class="mb-1">Jam selesai</label>
                                                <input type="time"
                                                    class="form-control @error('jam_selesai')is-invalid @enderror"
                                                    wire:model.defer="jam_selesai" placeholder="jam_selesai" id="jam-selesai">
                                                @error('jam_selesai') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <x-button-submit target="{{ $target }}">
                                        </x-button-submit>
                                    </div>
                                </div> {{-- end of row form-group --}}
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end of container --}}
