@section('title', 'Profile')
<div class="container py-3">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="d-none d-md-block">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>

            <div class=" d-md-none d-lg-none d-xl-none">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lainnya') }}">Lainnya</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>

        <div class="col-md-6 my-3">
            <form wire:submit.prevent="update">
                <div class="form-group">
                    <label for="username">{{ __('Username') }}</label>
                    <input wire:model="username" id="username" type="text" class="form-control" disabled
                        style="cursor: not-allowed">
                </div>

                <div class="form-group">
                    <label for="name">{{ __('Name') }}</label>
                    <input wire:model="name" id="name" type="name"
                        class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>

                    <input wire:model="email" id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror" name="email" required>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-groupmb-0">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Ubah Profile') }}
                            <x-loading target="{{ 'update' }}"></x-loading>
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Ubah Profile') }}
                            <x-loading target="{{ 'update' }}"></x-loading>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
