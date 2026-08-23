@extends('layouts.auth')

@section('title', 'Login - Persuratan Digital')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container page-shell d-flex align-items-center py-5">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="text-center mb-4">
                    <div class="brand-badge mx-auto mb-3">PD</div>
                    <h1 class="h3 fw-bold mb-2">Persuratan Digital</h1>
                    <p class="text-muted mb-0">Pondok Pesantren Dadali Dinillah</p>
                </div>
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4 align-items-center">
                            <div class="col-12 col-md-5">
                                <h2 class="h4 fw-bold mb-3">Login Akun</h2>
                                <p class="text-muted mb-4">
                                    Masuk ke sistem persuratan digital untuk melanjutkan pengelolaan surat.
                                </p>
                                <div class="login-note p-3 p-md-4">
                                    <div class="fw-semibold mb-2">Petunjuk</div>
                                    <div class="small text-muted">
                                        Gunakan username atau email yang sudah terdaftar beserta password Anda.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-7">
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('status') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('login') ?? 'Terjadi kesalahan pada data login.' }}
                                    </div>
                                @endif
                                <form action="{{ route('login.store') }}" method="POST" novalidate autocomplete="off">
                                    @csrf
                                    <input type="text" name="fake_username" class="d-none" tabindex="-1" autocomplete="username">
                                    <input type="password" name="fake_password" class="d-none" tabindex="-1" autocomplete="new-password">
                                    <div class="mb-3">
                                        <label for="login" class="form-label">Username atau Email</label>
                                        <input
                                            type="text"
                                            class="form-control @error('login') is-invalid @enderror"
                                            id="login"
                                            name="login"
                                            placeholder="Masukkan username atau email"
                                            autocomplete="off"
                                            readonly
                                            required
                                        >
                                        @error('login')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password"
                                                name="password"
                                                placeholder="Masukkan password"
                                                autocomplete="off"
                                                readonly
                                                required
                                            >
                                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Ingat saya</label>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" style="background: var(--brand); border-color: var(--brand);">
                                            Masuk
                                        </button>
                                    </div>

                                    <div class="text-center mt-3 small">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: var(--brand);">
                                            Daftar di sini
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection