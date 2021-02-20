@section('title', 'Setting')
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
                            <a href="{{ route('user-profile') }}" class="text-decoration-none">
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
                            {{-- {{ route('password.edit') }} --}}
                            <a href="/" class="text-decoration-none">
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
                            <livewire:auth.logout />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
