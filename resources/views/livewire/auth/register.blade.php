@section('title', 'Register')
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>

            <div class="col-md-6 my-3">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent="register" novalidate autocomplete="off">
                            <div class="form-group">
                                <label for="name">{{ __('Nama') }}</label>
                                <input wire:model="name" id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Bruno Bucciarati" value="{{ old('name') }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="username">{{ __('Username') }}</label>
                                <input wire:model="username" id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    placeholder="brunoBucciarati" value="{{ old('username') }}" required autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('E-Mail') }}</label>

                                <input wire:model="email" id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="bruno@gmail.com"
                                    name="email" value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>

                                <input wire:model="password" id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="••••••••••"
                                    name="password" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">{{ __('Ulangi Password') }}</label>

                                <input wire:model="password_confirmation" id="password-confirm" type="password"
                                    class="form-control" name="password_confirmation" placeholder="••••••••••" required
                                    password">
                            </div>

                            <div class="form-group">
                                <div class="d-none d-md-block">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                        wire:target="register">
                                        {{ __('Register') }}
                                        <x-loading target="{{ 'register' }}"></x-loading>
                                    </button>
                                </div>

                                <div class=" d-md-none d-lg-none d-xl-none">
                                    <button type="submit" class="btn btn-primary btn-block" wire:loading.attr="disabled"
                                        wire:target="register">
                                        {{ __('Register') }}
                                        <x-loading target="{{ 'register' }}"></x-loading>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mt-2 mb-0">
                                Sudah puya akun? login <a href="{{ route('login') }}">Disini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
