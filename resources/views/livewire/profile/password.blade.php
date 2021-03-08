@section('title', 'Ganti Password')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-none d-md-block">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Ganti Password</li>
                </ol>
            </div>

            <div class=" d-md-none d-lg-none d-xl-none">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lainnya') }}">Lainnya</a></li>
                    <li class="breadcrumb-item active">Ganti Password</li>
                </ol>
            </div>
        </div>

        <div class="col-md-6 my-3">
            @role('demo')
            <div class="alert alert-danger mb-3">
                Akun demo tidak dapat mengganti password!
            </div>
            @endrole

            <form wire:submit.prevent="update">
                <div class="form-group">
                    <label for="current_password">{{ __('Password Sekarang') }}</label>

                    <input id="current_password" type="password" wire:model="current_password"
                        class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••••"
                        required name="current_password">

                    @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password Baru') }}</label>

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        wire:model="password" name="password" placeholder="••••••••••" required
                        autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('Ulangi Password Baru') }}</label>

                    <input id="password-confirm" type="password" class="form-control" wire:model="password_confirmation"
                        placeholder="••••••••••" name="password_confirmation" required autocomplete="new-password">
                </div>

                @can('change password')
                <div class="form-groupmb-0">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Ganti Password') }}
                            <x-loading target="{{ 'update' }}"></x-loading>
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Ganti Password') }}
                            <x-loading target="{{ 'update' }}"></x-loading>
                        </button>
                    </div>
                </div>
                @endcan

            </form>
        </div>
    </div>
</div>
