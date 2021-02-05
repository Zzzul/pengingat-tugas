@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h4>Hai, Selamat datang!</h4>
                    <h6>Tetap semangat walaupun terbantai tugas :)</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
