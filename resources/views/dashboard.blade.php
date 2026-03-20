@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    Selamat Datang, <strong>{{ Auth::user()->name }}</strong>! 
                    Anda login sebagai role: {{ Auth::user()->getRoleNames()->first() }}
                </div>
            </div>
        </div>
    </div>
@endsection