@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="container py-3">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>

        <div class="col-md-6 my-3">
            @if (session('status') == 'profile-information-updated')
            <div class="alert alert-success">
                Profile information updated successfully.
            </div>
            @endif
            <form method="POST" action="{{ route('user-profile-information.update') }}">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="name">{{ __('Name') }}</label>
                    <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') ?? auth()->user()->name }}" required autocomplete="name" autofocus>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') ?? auth()->user()->email }}" required autocomplete="email"
                        autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-groupmb-0">
                    <div class="d-none d-md-block">
                        <button type="submit" class="btn btn-info">
                            {{ __('Update Profile') }}
                        </button>
                    </div>

                    <div class=" d-md-none d-lg-none d-xl-none">
                        <button type="submit" class="btn btn-info btn-block">
                            {{ __('Update Profile') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
