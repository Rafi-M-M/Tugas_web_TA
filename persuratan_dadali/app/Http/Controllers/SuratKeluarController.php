<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Pastikan Anda sudah membuat model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratKeluarController extends Controller
{
    /**
     * Menampilkan halaman form dan riwayat surat keluar.
     */
    public function index()
    {
        // Mengambil semua item surat keluar, diurutkan dari yang terbaru
        $suratKeluarItems = SuratKeluar::where('status', 'Keluar')->latest()->get();

        // Membuat nomor surat otomatis
        $nomorSuratOtomatis = $this->generateNomorSurat();

        return view('Persuratan.surat_keluar', compact('suratKeluarItems', 'nomorSuratOtomatis'));
    }

    /**
     * Menyimpan surat keluar baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_keluars,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'template_surat' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'format_surat' => 'nullable|json',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Maks 2MB
        ]);

        $path = null;
        if ($request->hasFile('lampiran')) {
            // Simpan file lampiran dan dapatkan path-nya
            $path = $request->file('lampiran')->store('lampiran-surat-keluar', 'public');
        }

        // Buat record baru di database
        SuratKeluar::create([
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['template_surat'],
            'tujuan' => $validated['tujuan'],
            'template_surat' => $validated['template_surat'],
            'isi_surat' => $validated['isi_surat'],
            'format_surat' => !empty($validated['format_surat']) ? json_decode($validated['format_surat'], true) : null,
            'status' => 'Keluar',
            'lampiran_path' => $path,
        ]);

        return redirect()->route('arsip.index')->with('status', 'Surat keluar berhasil diarsipkan!');
    }

    /**
     * Menampilkan detail surat (jika diperlukan).
     */
    public function show($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('Persuratan.surat_keluar_show', compact('surat'));
    }

    /**
     * Menampilkan form edit surat (jika diperlukan).
     */
    public function edit($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $suratKeluarItems = SuratKeluar::where('status', 'Keluar')->latest()->get();
        $nomorSuratOtomatis = $surat->nomor_surat;

        return view('Persuratan.surat_keluar', compact('surat', 'suratKeluarItems', 'nomorSuratOtomatis'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_keluars,nomor_surat,' . $surat->id,
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'template_surat' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'format_surat' => 'nullable|json',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $data = collect($validated)->except('lampiran')->toArray();
        $data['perihal'] = $validated['template_surat'];
        $data['format_surat'] = !empty($data['format_surat']) ? json_decode($data['format_surat'], true) : null;
        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
            $data['lampiran_path'] = $request->file('lampiran')->store('lampiran-surat-keluar', 'public');
        }

        $surat->update($data);
        return redirect()->route('surat.keluar.show', $surat->id)->with('status', 'Surat berhasil diperbarui.');
    }

    /**
     * Mengarsipkan surat keluar.
     */
    public function archive($id)
    {
        $surat = SuratKeluar::where('status', 'Keluar')->findOrFail($id);
        $surat->update(['status' => 'Arsip']);

        return redirect()->route('surat.keluar.index')->with('status', 'Surat berhasil diarsipkan.');
    }

    /**
     * Menghapus satu surat keluar.
     */
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        // Hapus juga file lampiran jika ada
        if ($surat->lampiran_path) {
            Storage::disk('public')->delete($surat->lampiran_path);
        }
        $surat->delete();

        return redirect()->route('surat.keluar.index')->with('status', 'Surat berhasil dihapus.');
    }

    /**
     * Menghapus semua riwayat surat keluar.
     */
    public function clear()
    {
        // Hapus semua file lampiran dari storage
        $allSurat = SuratKeluar::all();
        foreach ($allSurat as $surat) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
        }
        // Hapus semua record
        SuratKeluar::truncate();

        return redirect()->route('surat.keluar.index')->with('status', 'Semua riwayat surat keluar telah dihapus.');
    }

    /**
     * Fungsi helper untuk membuat nomor surat otomatis.
     * Format: 001/SK/DADALI/VII/2026
     */
    private function generateNomorSurat()
    {
        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulanSekarang = (int)date('m');
        $tahunSekarang = date('Y');

        // Cari surat terakhir di bulan dan tahun ini untuk menentukan nomor urut
        $suratTerakhir = SuratKeluar::whereYear('tanggal_surat', $tahunSekarang)
            ->whereMonth('tanggal_surat', $bulanSekarang)
            ->latest('id')
            ->first();

        $nomorUrut = 1;
        if ($suratTerakhir) {
            // Ambil nomor urut dari nomor surat terakhir
            $nomorTerakhir = explode('/', $suratTerakhir->nomor_surat)[0];
            $nomorUrut = (int)$nomorTerakhir + 1;
        }

        // Format nomor urut menjadi 3 digit (e.g., 001, 012)
        $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        return sprintf('%s/SK/DADALI/%s/%s', $nomorUrutFormatted, $bulanRomawi[$bulanSekarang - 1], $tahunSekarang);
    }
}