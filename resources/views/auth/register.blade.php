@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Register</li>
            </ol>
        </div>

        <div class="col-md-6 my-3">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="Bruno Bucciarati" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="bruno@gmail.com" name="email" value="{{ old('email') }}" required
                        autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••••" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('Confirm Password') }}</label>

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                        placeholder="••••••••••" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Register') }}
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
@endsection
