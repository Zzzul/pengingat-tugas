@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>

        <div class="col-md-12 mt-3 text-center">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            @auth
            <h3>Hai, {{ Auth::user()->name }}!</h3>
            @else
            <h3>Hai, Selamat datang!</h3>
            @endauth

            <p class="lead">Tetap semangat walaupun terbantai tugas :)</p>
        </div>
    </div>
</div>
@endsection
