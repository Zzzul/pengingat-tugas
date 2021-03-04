@php
$target = '';

if( $form == 'add'){
$target = 'store';
}else{
$target = 'update';
}
@endphp
@section('title', 'Mata Kuliah')
<div class="container py-3">
    <div class="row justify-content-md-center">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Mata Kuliah</li>
            </ol>
        </div>

        @if ($form)
        <div class="col-md-12 my-2">
            @role('admin')
            @if ($milik_user)
            <div class="alert alert-info" role="alert">
                Mata Kuliah ini milik : <span
                    class="font-weight bold">{{ '@'. $milik_user->username .' - '. $milik_user->name  }}</span>
            </div>
            @endif
            @endrole


            @if ($form == 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_matkul }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-10">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="name" class="mb-1">Mata Kuliah</label>
                                    <input type="text" class="form-control mb-2 @error('name')is-invalid @enderror"
                                        wire:model="name" placeholder="Nama Mata Kuliah" id="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="sks" class="mb-1">SKS</label>
                                    <input type="number" class="form-control mb-2 @error('sks')is-invalid @enderror"
                                        wire:model="sks" placeholder="SKS" id="sks" min="1" max="6">
                                    @error('sks') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="semester-id" class="mb-1">Semester</label>
                                    <select
                                        class="form-control mb-2 @error('semester_id')is-invalid @enderror{{ $semesters->isEmpty() ? 'is-invalid' : '' }}"
                                        wire:model="semester_id" id="semester-id">
                                        <option value="" disabled>--Pilih Semester--</option>
                                        @forelse ($semesters as $sms)
                                        <option value="{{ $sms->id }}">{{ $sms->semester_ke }}</option>
                                        @empty
                                        <option value="" disabled>Semester masih kosong!</option>
                                        @endforelse
                                    </select>
                                    @error('semester_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <x-button-submit target="{{ $target }}">
                            </x-button-submit>
                        </div>
                    </div> {{-- end of row form-group--}}
                </form>
        </div>
        {{-- end of --}}
        @endif

        <div class="col-md-12">
            {{-- button create --}}
            <div class="row my-2">
                <div class="col-md-10 mb-2">
                    <h5 class="card-title mb-0 pt-2">Mata Kuliah</h5>
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
                            <th>SKS</th>
                            <th>Semester</th>
                            <th>Dibuat Pada</th>
                            <th>Terakhir Diubah</th>
                            <th>Aksi
                                <img wire:loading wire:target="show"
                                    src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1" alt="Loading..">

                                <img wire:loading wire:target="triggerConfirm"
                                    src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" class="mb-1" alt="Loading..">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($matkuls as $key => $mk)

                        <tr class="table-active">
                            <td>{{ $matkuls->firstItem() + $key }}</td>
                            @role('admin')
                            <td>{{ $mk['user']->name }}
                                {!! $mk['user']->id == auth()->id() ? '<i class="fas fa-check-circle"></i>' : '' !!}
                            </td>
                            @endrole
                            <td>{{ $mk->name }}</td>
                            <td>{{ $mk->sks }}</td>
                            <td>{{ $mk['semester']->semester_ke }}</td>
                            <td>{{ $mk->created_at->diffForHumans()  }}</td>
                            <td>{{ $mk->updated_at->diffForHumans() }}</td>
                            <td>
                                <button class="mb-2 btn btn-outline-info btn-sm mr-1" wire:loading.attr="disabled"
                                    wire:click="show('{{ $mk->id }}')">
                                    <i class="fas fa-edit"></i></button>
                                <button class="mb-2 btn btn-outline-danger btn-sm" wire:loading.attr="disabled"
                                    wire:click="triggerConfirm('{{ $mk->id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @php
                        $data_yg_ditampilkan = $loop->index+1;
                        @endphp
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ada/ditemukan.</td>
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
                @if ($matkuls->total())
                Menampilkan
                {{  $matkuls->firstItem() .' sampai '. $matkuls->lastItem()  .' dari total '. $matkuls->total() }}
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
                {{  $matkuls->firstItem() .' sampai '. $matkuls->lastItem()  .' dari total '. $matkuls->total() }}
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

</div>
{{-- end of container--}}
