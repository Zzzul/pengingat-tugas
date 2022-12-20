@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Login</li>
            </ol>
        </div>

        <div class="col-md-6 my-3">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>

                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" placeholder="bruno@gmail.com"
                        autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="••••••••••" />

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="form-check p-0">
                        <label class="form-check-label ml-2" for="remember">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Login') }}
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Login') }}
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
@endsection
