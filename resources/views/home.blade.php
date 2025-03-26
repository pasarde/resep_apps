@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('token'))
                        <div class="alert alert-success">
                            Login berhasil! Simpan token ini untuk chat: <strong>{{ session('token') }}</strong>
                        </div>
                    @endif

                    Selamat datang, {{ Auth::user()->name }}!
                    <br>
                    <a href="/chat">Buka Forum Chat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection