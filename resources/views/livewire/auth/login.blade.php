@section('title', 'Login')
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Login</li>
                </ol>
            </div>

            <div class="col-md-6 my-3">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent="login" autocomplete="off" novalidate>
                            <div class="form-group">
                                <label for="username">{{ __('Username') }}</label>

                                <input wire:model="username" id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" required placeholder="BrunoBuccirati" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>

                                <input wire:model="password" id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    placeholder="••••••••••" />

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="d-none d-md-block">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                        wire:target="login">
                                        {{ __('Login') }}
                                        <x-loading target="{{ 'login' }}"></x-loading>
                                    </button>
                                </div>

                                <div class=" d-md-none d-lg-none d-xl-none">
                                    <button type="submit" class="btn btn-primary btn-block" wire:loading.attr="disabled"
                                        wire:target="login">
                                        {{ __('Login') }}
                                        <x-loading target="{{ 'login' }}"></x-loading>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mt-2 mb-0">
                                Belum puya akun? daftar <a href="{{ route('register') }}">Disini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
