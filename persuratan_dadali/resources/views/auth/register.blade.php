@extends('layouts.auth')

@section('title', 'Register - Persuratan Digital')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
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

                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4 align-items-center">
                            <div class="col-12 col-md-5">
                                <h2 class="h4 fw-bold mb-3">Buat Akun Baru</h2>
                                <p class="text-muted mb-4">
                                    Silakan isi data berikut untuk mendaftar ke sistem persuratan digital.
                                </p>

                                <div class="register-note p-3 p-md-4">
                                    <div class="fw-semibold mb-2">Informasi</div>
                                    <div class="small text-muted">
                                        Gunakan username yang mudah diingat, email aktif, dan password minimal 8 karakter.
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

                                <form action="{{ route('register.store') }}" method="POST" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input
                                            type="text"
                                            class="form-control @error('username') is-invalid @enderror"
                                            id="username"
                                            name="username"
                                            value="{{ old('username') }}"
                                            placeholder="Masukkan username"
                                            autocomplete="off"
                                            required
                                        >
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Hak Akses</label>
                                        <select
                                            class="form-select @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required
                                        >
                                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih hak akses</option>
                                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                                            <option value="pimpinan" {{ old('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="nama@email.com"
                                            required
                                        >
                                        @error('email')
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
                                                autocomplete="new-password"
                                                required
                                            >
                                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                placeholder="Ulangi password"
                                                autocomplete="new-password"
                                                required
                                            >
                                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan konfirmasi password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" style="background: var(--brand); border-color: var(--brand);">
                                            Daftar Sekarang
                                        </button>
                                    </div>

                                    <div class="text-center mt-3 small">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: var(--brand);">
                                            Login di sini
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3 small text-muted">
                    Sistem Persuratan Digital Pondok Pesantren Dadali Dinillah
                </div>
            </div>
        </div>
    </div>

@endsection