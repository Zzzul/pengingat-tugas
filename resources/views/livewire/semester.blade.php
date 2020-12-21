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
                <form wire:submit.prevent="update('{{ $id_semester }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-10">
                            <div class="d-flex justify-content-start">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="semester-ke">Semester</span>
                                    <input type="text" class="form-control @error('semester_ke')is-invalid @enderror"
                                        placeholder="Antara 2" aria-label="Semester Ke" wire:model="semester_ke"
                                        aria-describedby="semester-ke">
                                </div>
                                @error('semester_ke') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-dark mr-2" wire:click="hideForm()">Batal</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
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
                            <h5 class="card-title">Semester</h5>
                        </div>
                        <div>
                            <button class="btn btn-primary" wire:click="showForm('add')">Tambah Data</button>
                        </div>
                    </div>
                </div>
                {{-- end of card-header --}}
                <div class="card-body">
                    <table class="table table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Semester</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semesters as $sms)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $sms->semester_ke }}</td>
                                <td>
                                    <span wire:click="show('{{ $sms->id }}')" style="cursor: pointer">
                                        <i class="fas fa-edit text-primary"></i></span>
                                    <span wire:click="destroy('{{ $sms->id }}')" style="cursor: pointer">
                                        <i class="fas fa-trash text-danger"></i>
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
