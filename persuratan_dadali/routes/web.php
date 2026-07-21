<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'username' => ['required', 'string', 'max:255', 'unique:users,name'],
        'role' => ['required', 'string', 'in:admin,petugas,pimpinan'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    User::create([
        'name' => $validated['username'],
        'role' => $validated['role'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return back()->with('status', 'Akun berhasil didaftarkan.');
})->name('register.store');

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

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');
