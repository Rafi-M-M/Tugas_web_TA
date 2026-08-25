<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'login' => ['required', 'string', 'max:255'],
        'password' => ['required', 'string'],
    ]);

    $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    if (Auth::attempt([
        $field => $validated['login'],
        'password' => $validated['password'],
    ], $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Login berhasil.');
    }

    return back()
        ->withErrors(['login' => 'Username/email atau password salah.'])
        ->onlyInput('login');
})->name('login.store');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AkunController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::post('/akun/reset-password', [AkunController::class, 'resetPassword'])->name('akun.reset-password');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/template-surat', function () {
        return view('Persuratan.template_surat');
    })->name('template.index');

    // Routes untuk Surat Masuk
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat.masuk.index');
    Route::get('/surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat.masuk.show');
    Route::get('/surat-masuk/{id}/download', [SuratMasukController::class, 'download'])->name('surat.masuk.download');

    // Routes untuk Surat Keluar
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat.keluar.index');
    Route::get('/surat-keluar/{id}', [SuratKeluarController::class, 'show'])->name('surat.keluar.show');

    // Routes untuk Disposisi Surat
    Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
    Route::get('/disposisi/{id}', [DisposisiController::class, 'show'])->name('disposisi.show');
    Route::patch('/disposisi/{id}/tinjau', [DisposisiController::class, 'tinjau'])
        ->middleware('pimpinan')
        ->name('disposisi.tinjau');

    // Routes untuk Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
    Route::patch('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');

    // Routes untuk Arsip Surat
    Route::get('/arsip', [ArsipSuratController::class, 'index'])->name('arsip.index');
    Route::middleware('manage.data')->group(function () {
        Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat.masuk.store');
        Route::delete('/surat-masuk/clear', [SuratMasukController::class, 'clear'])->name('surat.masuk.clear');
        Route::delete('/surat-masuk/{id}', [SuratMasukController::class, 'destroy'])->name('surat.masuk.destroy');
        Route::patch('/surat-masuk/{id}/archive', [SuratMasukController::class, 'archive'])->name('surat.masuk.archive');

        Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])->name('surat.keluar.store');
        Route::get('/surat-keluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('surat.keluar.edit');
        Route::put('/surat-keluar/{id}', [SuratKeluarController::class, 'update'])->name('surat.keluar.update');
        Route::delete('/surat-keluar/clear', [SuratKeluarController::class, 'clear'])->name('surat.keluar.clear');
        Route::delete('/surat-keluar/{id}', [SuratKeluarController::class, 'destroy'])->name('surat.keluar.destroy');
        Route::patch('/surat-keluar/{id}/archive', [SuratKeluarController::class, 'archive'])->name('surat.keluar.archive');

        Route::post('/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');
        Route::patch('/disposisi/{id}/status', [DisposisiController::class, 'updateStatus'])->name('disposisi.status');
        Route::delete('/disposisi/clear', [DisposisiController::class, 'clear'])->name('disposisi.clear');
        Route::delete('/disposisi/{id}', [DisposisiController::class, 'destroy'])->name('disposisi.destroy');

        Route::patch('/arsip/surat-masuk/{id}/restore', [ArsipSuratController::class, 'restoreMasuk'])->name('arsip.masuk.restore');
        Route::patch('/arsip/surat-keluar/{id}/restore', [ArsipSuratController::class, 'restoreKeluar'])->name('arsip.keluar.restore');
        Route::delete('/arsip/surat-masuk/{id}', [ArsipSuratController::class, 'destroyMasuk'])->name('arsip.masuk.destroy');
        Route::delete('/arsip/surat-keluar/{id}', [ArsipSuratController::class, 'destroyKeluar'])->name('arsip.keluar.destroy');
        Route::delete('/arsip/clear', [ArsipSuratController::class, 'clear'])->name('arsip.clear');
    });

});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');