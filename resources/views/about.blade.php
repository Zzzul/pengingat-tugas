@extends('layouts.app')
@section('title', 'About')
@section('content')
<div class="container py-3">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">About</li>
            </ol>
        </div>

        <div class="col-md-12 mt-3">
            <h4 class="text-center mb-0">Teknologi Informasi</h4>
            <h4 class="text-center mb-0">Kelompok</h4>
            <h1 class="text-center">8</h1>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NPM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-active">
                                <td>Abdul Hakim Indrawan</td>
                                <td>2018310030</td>
                            </tr>
                            <tr class="table-active">
                                <td>Ahmad Abdul Rohman</td>
                                <td>2018310008</td>
                            </tr>
                            <tr class="table-active">
                                <td>Indra Wahyudi</td>
                                <td>2018310076</td>
                            </tr>
                            <tr class="table-active">
                                <td>Muhammad Ammar Habibi</td>
                                <td>2018310041</td>
                            </tr>
                            <tr class="table-active">
                                <td>Mohammad Zulfahmi</td>
                                <td>2018310009</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- end of row --}}

        </div>
        {{-- end of col --}}
    </div>
    {{-- end of row --}}
</div>
{{-- end of container --}}
@endsection
