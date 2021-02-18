@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Ganti Password</li>
            </ol>
        </div>

        <div class="col-md-6 my-3">
            @if (session('status') == 'password-updated')
            <div class="alert alert-success">
                Password updated successfully.
            </div>
            @endif

            <form method="POST" action="{{ route('user-password.update') }}">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password">{{ __('Password Sekarang') }}</label>

                    <input id="current_password" type="password"
                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                        placeholder="••••••••••" name="current_password">

                    @error('current_password', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password Baru') }}</label>

                    <input id="password" type="password"
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password"
                        placeholder="••••••••••" required autocomplete="new-password">

                    @error('password', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('Ulangi Password Baru') }}</label>

                    <input id="password-confirm" type="password" class="form-control" placeholder="••••••••••"
                        name="password_confirmation" required autocomplete="new-password">
                </div>


                <div class="form-groupmb-0">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Update Password') }}
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Update Password') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
