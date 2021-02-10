@php
$target = '';

if( $form == 'add'){
$target = 'store';
}else{
$target = 'update';
}
@endphp
@section('title', 'Semester')
<div class="container py-3">
    <div class="row justify-content-md-center">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item active">Semester</li>
            </ol>
        </div>

        @if ($form)
        <div class="col-md-12 mt-3">
            @if ($form == 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_semester }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label for="semester-ke">Semester</label>
                            <input type="number" id="semester-ke"
                                class="form-control @error('semester_ke')is-invalid @enderror" placeholder="Semester"
                                wire:model="semester_ke" aria-describedby="semester-ke" {{ $form ? 'autofocus' : '' }}>
                            @error('semester_ke') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-2">
                            <x-button-submit target="{{ $target }}"></x-button-submit>
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
                    <h5 class="card-title mb-0" for="semester-aktif">Semester</h5>
                    @if ($aktif_smt)
                    <small>Semester Sekarang : <strong>{{ $aktif_smt['semester_ke'] }}</strong></small>
                    @endif
                </div>
                <div class="col-md-2 justify-content-end mb-3">
                    <x-button-create></x-button-create>
                </div>
            </div>

            <div class="form-group">
                <input type="text" class="form-control my-2" placeholder="Cari...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Semester</th>
                            <th>Dibuat Pada</th>
                            <th>Terakhir Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semesters as $key => $sms)
                        <tr class="table-active">
                            <td>{{ $semesters->firstItem() + $key }}
                            </td>
                            <td>{{ $sms->semester_ke }}</td>
                            <td>{{ $sms->created_at->diffForHumans()  }}</td>
                            <td>{{ $sms->updated_at->diffForHumans() }}</td>
                            <td>
                                <button class="mb-2 btn btn-outline-info btn-sm mr-1"
                                    wire:click="show('{{ $sms->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="mb-2 btn btn-outline-danger btn-sm"
                                    wire:click="destroy('{{ $sms->id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button class="mb-2 btn btn-outline-success btn-sm"
                                    wire:click="setAktifSmt('{{ $sms->id }}')">
                                    {!! $sms->aktif_smt ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'
                                    !!}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end m-0">
                {{ $semesters->links() }}
            </div>
        </div>
        {{-- end of col--}}
    </div>
    {{-- end of row--}}
</div>
{{-- end of container--}}
