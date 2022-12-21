@php
$target = '';

if ($form == 'add') {
    $target = 'store';
} else {
    $target = 'update';
}
@endphp

@section('title', 'Semester')

    <div class="container py-3">
        <div class="row justify-content-md-center">

            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Semester</li>
                </ol>
            </div>

            <div class="col-md-12">

                {{-- button create --}}
                <div class="row my-2">
                    <div class="col-md-8 mb-2">
                        <h5 class="card-title mb-0 mr-2" for="semester-aktif">Semester</h5>

                        @if ($aktif_smt)
                            <p class="mb-0 mr-2">Semester Sekarang :
                                <span class="font-weight-bold">{{ $aktif_smt['semester_ke'] }}</span>
                            </p>
                        @else
                            <p class="mb-0 mr-2">Semester Sekarang : ?</p>
                            <p class="mt-0 mb-2">Klik ikon bintang yang sesuai dengan semester kamu.</p>
                        @endif

                    </div>

                    <div class="col-md-2 pr-0">
                        <a href="{{ route('pdf.semester.all') }}" class="btn btn-success float-right" target="blank">
                            <i class="fas fa-print mr-1"></i> Print
                        </a>
                    </div>

                    <div class="col-md-2 mb-1">
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

                                            <img wire:loading wire:target="setAktifSmt"
                                                src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1"
                                                alt="Loading..">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($semesters as $key => $sms)
                                        <tr>
                                            <td>{{ $semesters->firstItem() + $key }}
                                            </td>
                                            @role('admin')
                                            <td>{{ $sms['user']->name }}
                                                {!! $sms['user']->id == auth()->id() ? '<i class="fas fa-check-circle"></i>' : '' !!}
                                            </td>
                                            @endrole
                                            <td>{{ $sms->semester_ke }}</td>
                                            <td>{{ $sms->created_at->diffForHumans() }}</td>
                                            <td>{{ $sms->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <button class="mb-1 btn btn-outline-primary btn-sm mr-1"
                                                    wire:loading.attr="disabled" wire:click="show('{{ $sms->id }}')"
                                                    data-toggle="modal" data-target="#exampleModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button class="mb-1 btn btn-outline-danger btn-sm mr-1"
                                                    wire:loading.attr="disabled"
                                                    wire:click="triggerConfirm('{{ $sms->id }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <button class="mb-1 btn btn-outline-success btn-sm"
                                                    wire:loading.attr="disabled"
                                                    wire:click="setAktifSmt('{{ $sms->id }}')">
                                                    {!! $sms->aktif_smt ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>' !!}
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Data tidak ada/ditemukan.</td>
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
                    @if ($semesters->total())
                        Menampilkan
                        {{ $semesters->firstItem() . ' sampai ' . $semesters->lastItem() . ' dari total ' . $semesters->total() }}
                        data
                    @endif
                </div>
                <div>
                    {{ $semesters->links() }}
                </div>
            </div>
        </div>
        {{-- d-none d-md-block --}}

        <div class="d-sm-block d-md-none">
            <div class="row justify-content-center">
                <div class="col-sm-12 mb-2 text-center text-muted">
                    @if ($semesters->total())
                        Menampilkan
                        {{ $semesters->firstItem() . ' sampai ' . $semesters->lastItem() . ' dari total ' . $semesters->total() }}
                        data
                    @endif
                </div>
                <div class="col-sm-12">
                    <div class="d-flex justify-content-center m-0">
                        {{ $semesters->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- d-sm-block d-md-none --}}

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ $form === 'add' ? 'Tambah Data Semester' : 'Edit Data Semester' }}
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
                                    Semester ini milik : <span
                                        class="font-weight bold">{{ '@' . $milik_user->username . ' - ' . $milik_user->name }}</span>
                                </div>
                            @endif
                            @endrole

                            @if ($form == 'add')
                                <form wire:submit.prevent="store">
                                @else
                                    <form wire:submit.prevent="update('{{ $id_semester }}')">
                            @endif

                            <div class="row form-group">
                                <div class="col-md-8">
                                    <label for="semester-ke">Semester</label>
                                    <input type="number" id="semester-ke" min="1" max="8"
                                        class="form-control @error('semester_ke')is-invalid @enderror"
                                        placeholder="Semester ke" wire:model.defer="semester_ke" aria-describedby="semester-ke"
                                        {{ $form ? 'autofocus' : '' }}>
                                    @error('semester_ke') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <x-button-submit target="{{ $target }}"></x-button-submit>
                                </div>
                            </div> {{-- end of row form-group --}}
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
