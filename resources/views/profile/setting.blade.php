@extends('layouts.app')
@section('title', 'Akun')
@section('content')
<div class="container py-3">
    <div class="row">
        {{-- breadcumb --}}
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Setting</li>
            </ol>
        </div>
        {{-- end of breadcumb --}}

        <div class="col-md-12 mt-3 text-center">
            {{-- <img src="https://avatars.githubusercontent.com/u/62506582?s=400&u=ba159f8a0037ea86e54a208efff8aa47ef8e9ba0&v=4"
                class="img-fluid rounded-circle p-0" width="25%" alt="Profile Picture"> --}}

            <h4 class="mb-3">{{ Auth::user()->name }}</h4>
        </div>
        {{-- end of col --}}
    </div>
    {{-- end of row --}}

    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm text-center">
                    <tr class="table-active">
                        <td>
                            <a href="{{ route('user-profile-information.edit') }}" class="text-decoration-none">
                                <h6 class="m-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                                        fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    </svg>
                                    &nbsp;Profile</h6>
                            </a>
                        </td>
                    </tr>
                    <tr class="table-active">
                        <td>
                            <a href="{{ route('password.edit') }}" class="text-decoration-none">
                                <h6 class="m-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                                        fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                                        <path
                                            d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
                                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                    </svg>
                                    &nbsp;Ganti Password</h6>
                            </a>
                        </td>
                    </tr>
                    <tr class="table-active">
                        <td>
                            <a href="{{ route('logout') }}" class="text-decoration-none"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <h6 class="m-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                                        fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z" />
                                        <path fill-rule="evenodd"
                                            d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                    </svg>
                                    &nbsp;Logout</h6>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @endsection
