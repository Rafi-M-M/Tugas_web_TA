<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role', 'created_at']);

        return view('Pengelola.akun', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($request->routeIs('akun.store')) {
            $rules['role'] = ['required', 'string', 'in:admin,pimpinan'];
        }

        $validated = $request->validate($rules);

        User::create([
            'name' => $validated['username'],
            'role' => $validated['role'] ?? 'admin',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->routeIs('register.store')) {
            return redirect()
                ->route('login')
                ->with('status', 'Akun berhasil dibuat. Silakan login.');
        }

        return redirect()
            ->route('akun.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
            ->route('akun.index')
            ->with('success', 'Password akun ' . $user->name . ' berhasil direset.');
    }
}
