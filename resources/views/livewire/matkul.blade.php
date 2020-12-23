<div class="container py-3">
    <div class="row justify-content-md-center">
        @if (session()->has('message'))
        <div class="col-md-8 mb-2">
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        </div>
        @endif

        @if ($form)
        <div class="col-md-8 mb-3">
            @if ($form == 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_matkul }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-10">
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="name">Mata Kuliah</label>
                                    <input type="text" class="form-control @error('name')is-invalid @enderror"
                                        wire:model="name" placeholder="Nama Mata Kuliah" id="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-3">
                                    <label for="sks">SKS</label>
                                    <input type="number" class="form-control @error('sks')is-invalid @enderror"
                                        wire:model="sks" placeholder="SKS" id="sks" min="1" max="3">
                                    @error('sks') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-3">
                                    <label for="semester-id">Semester</label>
                                    <select class="form-control @error('semester_id')is-invalid @enderror"
                                        wire:model="semester_id" id="semester-id">
                                        <option value="" disabled>--Pilih Semester--</option>
                                        @foreach ($semesters as $sms)
                                        <option value="{{ $sms->id }}">{{ $sms->semester_ke }}</option>
                                        @endforeach
                                    </select>
                                    @error('semester_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-dark btn-block mr-2" wire:click="hideForm()">
                                <i class="fas fa-times mr-1"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-1"></i>
                                @if ($form == 'add')
                                Submit
                                @else
                                Update
                                @endif
                            </button>
                        </div>
                    </div> {{-- end of row form-group--}}
                </form>
        </div>
        {{-- end of --}}
        @endif

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Mata Kuliah</h5>
                        </div>
                        <div>
                            <button class="btn btn-primary" wire:click="showForm('add')">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Data
                            </button>
                        </div>
                    </div>
                </div>
                {{-- end of card-header --}}
                <div class="card-body">
                    <table class="table table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matkuls as $mk)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $mk->name }}</td>
                                <td>{{ $mk->sks }}</td>
                                <td>{{ $mk['semester']->semester_ke }}</td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm mr-1"
                                        wire:click="show('{{ $mk->id }}')">
                                        <i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger btn-sm" wire:click="destroy('{{ $mk->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end m-0">
                        {{ $matkuls->links() }}
                    </div>
                </div>
                {{-- end of card-body--}}
            </div>
            {{-- end of card--}}
        </div>
        {{-- end of col--}}
    </div>
    {{-- end of row--}}
</div>
{{-- end of container--}}
