@php
$target = '';

if( $form == 'add'){
$target = 'store';
}else{
$target = 'update';
}
@endphp
@section('title', 'List User')
<div class="container py-3">
    <div class="row justify-content-md-center">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">User</li>
            </ol>
        </div>


        @if ($form)
        <div class="col-md-12 mt-3">
            @if ($form == 'add')
            <form wire:submit.prevent="store">
                @else
                <form wire:submit.prevent="update('{{ $id_user }}')">
                    @endif

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label for="username">Username</label>
                            <input type="text" id="username" class="form-control @error('username')is-invalid @enderror"
                                placeholder="username" wire:model="username" aria-describedby="username" disabled
                                style="cursor: not-allowed">
                            @error('username') <span class="text-danger" class="invalid-feedback"
                                role="alert">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="name">Name</label>
                            <input type="text" id="name" min="1" max="10"
                                class="form-control @error('name')is-invalid @enderror" placeholder="Name"
                                wire:model="name" aria-describedby="name" {{ $form ? 'autofocus' : '' }}>
                            @error('name') <span class="text-danger" class="invalid-feedback"
                                role="alert">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control @error('email')is-invalid @enderror"
                                placeholder="Email" wire:model="email" aria-describedby="email"
                                {{ $form ? 'autofocus' : '' }}>
                            @error('email') <span class="text-danger" class="invalid-feedback"
                                role="alert">{{ $message }}</span> @enderror
                        </div>


                        {{--
                        <div class="col-md-7"></div> --}}
                        <div class="col-md-2 mt-0">
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
                    <h5 class="card-title mb-0">User</h5>
                </div>
            </div>

            <x-search-input></x-search-input>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Terdaftar Pada</th>
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
                        @forelse ($users as $key => $usr)
                        <tr class="table-active">
                            <td>{{ $users->firstItem() + $key }}
                            </td>
                            <td>{{ $usr->username }}</td>
                            <td>{{ $usr->name }}</td>
                            <td>{{ $usr->email }}</td>
                            <td>{{ $usr->created_at->diffForHumans()  }}</td>
                            <td>{{ $usr->updated_at->diffForHumans() }}</td>
                            <td>
                                <button class="mb-2 btn btn-outline-info btn-sm mr-1" wire:loading.attr="disabled"
                                    wire:click="show('{{ $usr->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="mb-2 btn btn-outline-danger btn-sm" wire:loading.attr="disabled"
                                    wire:click="triggerConfirm('{{ $usr->id }}')">
                                    <i class="fas fa-trash-alt"></i>
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

    <div class="d-none d-md-block">
        <div class="d-flex justify-content-between text-muted">
            <div>
                @if ($users->total())
                Menampilkan
                {{  $users->firstItem() .' sampai '. $users->lastItem()  .' dari total '. $users->total() }}
                data
                @endif
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
    {{-- d-none d-md-block --}}

    <div class="d-sm-block d-md-none">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-2 text-center text-muted">
                @if ($users->total())
                Menampilkan
                {{  $users->firstItem() .' sampai '. $users->lastItem()  .' dari total '. $users->total() }}
                data
                @endif
            </div>
            <div class="col-sm-12">
                <div class="d-flex justify-content-center m-0">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- d-sm-block d-md-none --}}

</div>
{{-- end of container--}}
