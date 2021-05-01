@php
$target = '';

if ($form == 'add') {
    $target = 'store';
} else {
    $target = 'update';
}
@endphp
@section('title', 'List User')
    <div class="container py-3">
        <div class="row justify-content-md-center">

            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">User</li>
                </ol>
            </div>

            <div class="col-md-12">
                {{-- button create --}}
                <div class="row my-2">
                    <div class="col-md-10 mb-2">
                        <h4>User</h4>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <x-search-input></x-search-input>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Permissions</th>
                                        <th>Terdaftar Pada</th>
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
                                    @forelse ($users as $key => $user)
                                        <tr>
                                            <td>{{ $users->firstItem() + $key }}
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ count($user->getRoleNames()) > 0 ? ucfirst(print_r($user->getRoleNames()[0], 1)) : '' }}
                                            </td>
                                            <td>
                                                <ul class="m-0 pl-3">
                                                    @foreach ($user->permissions as $user_permis)
                                                        <li>{{ Str::ucfirst($user_permis->name) }} </li>
                                                    @endforeach
                                                </ul>

                                            </td>
                                            <td>{{ $user->created_at->diffForHumans() }}</td>
                                            <td>{{ $user->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <button class="mb-2 btn btn-outline-primary btn-sm mr-1"
                                                    wire:loading.attr="disabled" wire:click="show('{{ $user->id }}')"
                                                    data-toggle="modal" data-target="#exampleModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button class="mb-2 btn btn-outline-danger btn-sm"
                                                    wire:loading.attr="disabled"
                                                    wire:click="triggerConfirm('{{ $user->id }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Data tidak ada/ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-none d-md-block">
            <div class="d-flex justify-content-between text-muted">
                <div>
                    @if ($users->total())
                        Menampilkan
                        {{ $users->firstItem() . ' sampai ' . $users->lastItem() . ' dari total ' . $users->total() }}
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
                        {{ $users->firstItem() . ' sampai ' . $users->lastItem() . ' dari total ' . $users->total() }}
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

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Data User
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:loading.attr="disabled" wire:click="hideForm()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($form)
                            <form wire:submit.prevent="update('{{ $id_user }}')">
                                <div class="row form-group">
                                    {{-- Username --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="username">Username</label>
                                        <input type="text" id="username"
                                            class="form-control @error('username')is-invalid @enderror"
                                            placeholder="username" wire:model="username" aria-describedby="username"
                                            disabled style="cursor: not-allowed">
                                        @error('username') <span class="text-danger" class="invalid-feedback"
                                            role="alert">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Name --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="name">Nama</label>
                                        <input type="text" id="name" min="1" max="10"
                                            class="form-control @error('name')is-invalid @enderror" placeholder="Name"
                                            wire:model="name" aria-describedby="name" {{ $form ? 'autofocus' : '' }}>
                                        @error('name') <span class="text-danger" class="invalid-feedback"
                                            role="alert">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-3  mb-2">
                                        <label for="email">Email</label>
                                        <input type="email" id="email"
                                            class="form-control @error('email')is-invalid @enderror" placeholder="Email"
                                            wire:model="email" aria-describedby="email" {{ $form ? 'autofocus' : '' }}>
                                        @error('email') <span class="text-danger" class="invalid-feedback"
                                            role="alert">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- role --}}
                                    <div class="col-md-3  mb-2">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" wire:model="user_roles"
                                            class="form-control @error('role')is-invalid @enderror">
                                            <option value="" disabled>--Pilih Role--</option>
                                            @foreach ($all_roles as $role)
                                                {{-- {{ $user_roles == $role->name ? 'selected' : ''}} --}}
                                                <option value="{{ $role->id }}">
                                                    {{ ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_roles') <span class="text-danger" class="invalid-feedback"
                                            role="alert">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Permissions --}}
                                    <div class="col-md-10 col-sm-12">
                                        <label
                                            class="mb-1 mt-2 @error('permissions.id')text-danger @enderror">Permissions</label>

                                        @foreach ($user_permissions as $permis)
                                            <label class="form-check-label" style="cursor : pointer;">
                                                <input class="form-check-input" type="checkbox" value="{{ $permis->id }}"
                                                    id="permis-{{ $permis->id }}"
                                                    wire:model="permissions.id.{{ $loop->index }}" checked>
                                                {{ ucfirst($permis->name) }}
                                            </label>
                                        @endforeach

                                        @foreach ($not_user_permissions as $not_permis)
                                            <label class="form-check-label" style="cursor : pointer;">
                                                <input class="form-check-input" type="checkbox"
                                                    id="permis-{{ $not_permis->id }}" value="{{ $not_permis->id }}"
                                                    wire:model="permissions.id.{{ $loop->index + count($user_permissions) }}">
                                                {{ ucfirst($not_permis->name) }}
                                            </label>
                                        @endforeach

                                        @error('permissions.id')
                                            <small class="text-danger">
                                                {{-- {{ $message }} --}}
                                                Permissions field is required.
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="col-md-2 mt-0">
                                        <x-button-submit target="{{ $target }}"></x-button-submit>
                                    </div>
                                </div>
                                {{-- end of row form-group --}}
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end of container --}}
