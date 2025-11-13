@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Dashboard Admin</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">

        {{-- Total Petugas --}}
        <div class="col-lg-3 col-6">
            <div class="card info-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Petugas</h5>
                    <h3>{{ $totalPetugas }}</h3>
                </div>
            </div>
        </div>

        {{-- Total Slot Parkir --}}
        <div class="col-lg-3 col-6">
            <div class="card info-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Slot Parkir</h5>
                    <h3>{{ $totalSlots }}</h3>
                </div>
            </div>
        </div>

        {{-- Kendaraan Saat Ini
        <div class="col-lg-3 col-6">
            <div class="card info-card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Kendaraan Parkir</h5>
                    <h3>{{ $vehiclesParked }}</h3>
                </div>
            </div>
        </div>

        {{-- Kendaraan Keluar Hari Ini --}}
        {{-- <div class="col-lg-3 col-6">
            <div class="card info-card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Keluar Hari Ini</h5>
                    <h3>{{ $vehiclesExitedToday }}</h3>
                </div>
            </div>
        </div> --}} 

    </div>

    {{-- Info User Login --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Login</h5>
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ $user->role->name }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
