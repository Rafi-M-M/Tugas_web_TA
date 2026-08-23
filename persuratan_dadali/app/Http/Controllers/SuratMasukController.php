<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan halaman form dan riwayat surat masuk.
     */
    public function index()
    {
        $suratMasukItems = SuratMasuk::where('status', 'Masuk')->latest()->get();
        return view('Persuratan.surat_masuk', compact('suratMasukItems'));
    }

    /**
     * Menyimpan surat masuk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_masuks,nomor_surat',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Maks 2MB
        ]);

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran-surat-masuk', 'public');
        } elseif ($request->hasFile('gambarSurat')) {
            // Jika dari tab AI, simpan gambar suratnya sebagai lampiran
            $path = $request->file('gambarSurat')->store('lampiran-surat-masuk', 'public');
        }

        SuratMasuk::create([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'pengirim' => $validated['pengirim'],
            'perihal' => $validated['perihal'],
            'isi_ringkas' => $validated['isi_ringkas'],
            'lampiran_path' => $path,
            'status' => 'Arsip',
        ]);

        return redirect()->route('arsip.index')->with('status', 'Surat masuk berhasil diarsipkan!');
    }

    /**
     * Menampilkan halaman detail surat.
     */
    public function show($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('Persuratan.surat_masuk_detail', compact('surat'));
    }

    /**
     * Mengunduh lampiran surat.
     */
    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Pastikan path lampiran ada dan file-nya benar-benar ada di storage
        if ($surat->lampiran_path && Storage::disk('public')->exists($surat->lampiran_path)) {
            return Storage::disk('public')->download($surat->lampiran_path);
        }

        // Jika tidak ada file, kembali ke halaman detail dengan pesan error
        return redirect()->route('surat.masuk.show', $id)->with('error', 'Lampiran tidak ditemukan atau rusak.');
    }

    /**
     * Mengarsipkan surat masuk.
     */
    public function archive($id)
    {
        $surat = SuratMasuk::where('status', 'Masuk')->findOrFail($id);
        $surat->update(['status' => 'Arsip']);

        return redirect()->route('surat.masuk.index')->with('status', 'Surat berhasil diarsipkan.');
    }

    /**
     * Menghapus satu surat masuk.
     */
    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        if ($surat->lampiran_path) {
            Storage::disk('public')->delete($surat->lampiran_path);
        }
        $surat->delete();

        return redirect()->route('surat.masuk.index')->with('status', 'Surat berhasil dihapus.');
    }

    /**
     * Menghapus semua riwayat surat masuk.
     */
    public function clear()
    {
        $allSurat = SuratMasuk::all();
        foreach ($allSurat as $surat) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
        }
        SuratMasuk::truncate();

        return redirect()->route('surat.masuk.index')->with('status', 'Semua riwayat surat masuk telah dihapus.');
    }
}