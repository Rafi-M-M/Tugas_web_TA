<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    private const SIFAT = ['Biasa', 'Penting', 'Segera', 'Sangat Segera', 'Rahasia'];

    private const STATUS = ['Diproses', 'Selesai', 'Ditunda'];

    public function index()
    {
        $disposisiItems = Disposisi::with(['suratMasuk', 'pembuat'])->latest()->get();
        $suratMasukOptions = SuratMasuk::orderBy('tanggal_surat', 'desc')->get();

        return view('Persuratan.disposisi', [
            'disposisiItems' => $disposisiItems,
            'suratMasukOptions' => $suratMasukOptions,
            'sifatOptions' => self::SIFAT,
            'statusOptions' => self::STATUS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuks,id',
            'tanggal_disposisi' => 'required|date',
            'ditujukan_kepada' => 'required|string|max:255',
            'sifat' => 'required|in:' . implode(',', self::SIFAT),
            'instruksi' => 'required|string',
            'catatan' => 'nullable|string',
            'batas_waktu' => 'nullable|date|after_or_equal:tanggal_disposisi',
        ]);

        $validated['user_id'] = $request->user()?->id;
        $validated['status'] = 'Diproses';

        $disposisi = Disposisi::create($validated);

        return redirect()->route('disposisi.index')
            ->with('success', "Disposisi untuk surat {$disposisi->suratMasuk->nomor_surat} berhasil dibuat.");
    }

    public function show($id)
    {
        $disposisi = Disposisi::with(['suratMasuk', 'pembuat'])->findOrFail($id);

        return view('Persuratan.disposisi_show', [
            'disposisi' => $disposisi,
            'statusOptions' => self::STATUS,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUS),
            'catatan' => 'nullable|string',
        ]);

        $disposisi = Disposisi::findOrFail($id);
        $disposisi->update($validated);

        return redirect()->back()->with('success', 'Status disposisi diperbarui.');
    }

    public function destroy($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        $disposisi->delete();

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dihapus.');
    }

    public function clear()
    {
        Disposisi::query()->delete();

        return redirect()->route('disposisi.index')->with('success', 'Semua disposisi telah dihapus.');
    }
}