@extends('layouts.auth')

@section('title', 'Dashboard Admin - Persuratan Digital')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="admin-shell py-4 py-lg-5">
        <div class="container position-relative">
            <div class="dashboard-layout d-flex flex-column flex-lg-row gap-4">
                <aside class="sidebar-shell">
                    <div class="card glass-card border-0 rounded-5 h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="brand-mark shadow">
                                    <i class="bi bi-envelope-paper-heart fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-uppercase fw-semibold small">Persuratan Digital</div>
                                    <h1 class="h5 mb-0">Admin {{ auth()->user()->name }}</h1>
                                </div>
                            </div>
                            <nav class="nav flex-column sidebar-nav gap-2 mb-4">
                                <a class="nav-link active" href="#utama"><i class="bi bi-house-door me-2"></i>Dashboard</a>
                                <a class="nav-link" href="#informasi"><i class="bi bi-info-circle me-2"></i>Informasi Sistem</a>
                                <a class="nav-link" href="#aksi"><i class="bi bi-layout-text-window-reverse me-2"></i>Panel Utama</a>
                                <a class="nav-link" href="#surat-masuk"><i class="bi bi-inbox me-2"></i>Surat Masuk</a>
                                <a class="nav-link" href="#surat-keluar"><i class="bi bi-send me-2"></i>Surat Keluar</a>
                                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#arsip-menu" role="button" aria-expanded="false" aria-controls="arsip-menu">
                                    <span><i class="bi bi-archive me-2"></i>Arsipkan Surat</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>
                                <div class="collapse ms-3 ps-3 border-start border-dark-subtle" id="arsip-menu">
                                    <div class="d-flex flex-column gap-2">
                                        <a class="nav-link py-1" href="#surat-masuk"><i class="bi bi-inbox me-2"></i>Surat Masuk</a>
                                        <a class="nav-link py-1" href="#surat-keluar"><i class="bi bi-send me-2"></i>Surat Keluar</a>
                                    </div>
                                </div>
                            </nav>
                            <div class="mt-auto">
                                <div class="rounded-4 border border-dark p-3 mb-3 bg-white">
                                    <div class="small text-uppercase fw-semibold mb-1">Akun Aktif</div>
                                    <div class="fw-semibold">
                                        Login sebagai {{ ucfirst(auth()->user()->role ?? 'petugas') }} | {{ auth()->user()->name }}
                                    </div>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-mono-outline w-100 rounded-4 py-3">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </aside>
                <main class="content-shell flex-grow-1">
                    <div class="card border-0 shadow-lg hero-panel glass-card rounded-5 overflow-hidden main-panel" id="utama">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div>
                                    <span class="badge-mono mb-3">
                                        <i class="bi bi-calendar3 me-1"></i> {{ now()->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    <h2 class="display-6 fw-semibold mb-3">
                                        Selamat datang, {{ auth()->user()->name }}.
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
