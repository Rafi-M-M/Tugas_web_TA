<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasiItems = Notifikasi::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('notifikasi.index', compact('notifikasiItems'));
    }

    public function baca(Request $request, $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);

        if ($notifikasi->user_id !== $request->user()->id) {
            abort(403);
        }

        $notifikasi->update(['dibaca_pada' => now()]);

        return $notifikasi->url ? redirect($notifikasi->url) : redirect()->back();
    }

    public function bacaSemua(Request $request)
    {
        Notifikasi::query()
            ->where('user_id', $request->user()->id)
            ->belumDibaca()
            ->update(['dibaca_pada' => now()]);

        return redirect()->back();
    }
}