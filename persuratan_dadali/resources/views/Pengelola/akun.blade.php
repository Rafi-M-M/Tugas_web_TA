@extends('layouts.app')

@section('title', 'Akun - Manajemen Persuratan')

@section('page-title', 'Pengelolaan Akun')
@section('page-subtitle', 'Daftar akun terdaftar, tambah akun, dan reset password')

@section('content')


<div class="card mt-4">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> Tambah Akun</h3>
        <span class="card-header-subtitle"><i class="fas fa-asterisk required-asterisk"></i> wajib diisi</span>
    </div>

    <div class="card-body">
        <form action="{{ route('akun.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required class="@error('username') is-invalid @enderror" autocomplete="off">
                    @error('username')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role"><i class="fas fa-user-tag"></i> Hak Akses <span class="required">*</span></label>
                    <select id="role" name="role" required class="@error('role') is-invalid @enderror">
                        <option value="" disabled selected>Pilih hak akses</option>
                        <option value="admin">Admin</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                    @error('role')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="email"><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="nama@email.com" required class="@error('email') is-invalid @enderror" autocomplete="off">
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required class="@error('password') is-invalid @enderror" autocomplete="off">
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required autocomplete="off">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Akun</button>
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3><i class="fas fa-key"></i> Reset Password Akun</h3>
        <span class="card-header-subtitle"><i class="fas fa-asterisk required-asterisk"></i> wajib diisi</span>
    </div>

    <div class="card-body">
        <form action="{{ route('akun.reset-password') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="user_id"><i class="fas fa-users"></i> Pilih Akun <span class="required">*</span></label>
                    <select id="user_id" name="user_id" required class="@error('user_id') is-invalid @enderror">
                        <option value="" disabled selected>Pilih akun</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }}) - {{ $user->email }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reset_password"><i class="fas fa-lock"></i> Password Baru <span class="required">*</span></label>
                    <input type="password" id="reset_password" name="password" placeholder="Minimal 8 karakter" required class="@error('password') is-invalid @enderror">
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reset_password_confirmation"><i class="fas fa-lock"></i> Konfirmasi Password Baru <span class="required">*</span></label>
                    <input type="password" id="reset_password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-key"></i> Reset Password</button>
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users"></i> Daftar Akun Terdaftar</h3>
        <span class="card-header-subtitle">{{ $users->count() }} akun</span>
    </div>

    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Hak Akses</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="status-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 24px;">Belum ada akun terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
