<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage;

class ArsipSuratController extends Controller
{
    public function index()
    {
        $arsipMasuk = SuratMasuk::where('status', 'Arsip')->latest()->get();
        $arsipKeluar = SuratKeluar::where('status', 'Arsip')->latest()->get();

        return view('Persuratan.arsip_surat', compact('arsipMasuk', 'arsipKeluar'));
    }

    public function restoreMasuk($id)
    {
        $surat = SuratMasuk::where('status', 'Arsip')->findOrFail($id);
        $surat->update(['status' => 'Masuk']);

        return redirect()->route('arsip.index')->with('success', "Surat {$surat->nomor_surat} dikembalikan ke Surat Masuk.");
    }

    public function restoreKeluar($id)
    {
        $surat = SuratKeluar::where('status', 'Arsip')->findOrFail($id);
        $surat->update(['status' => 'Keluar']);

        return redirect()->route('arsip.index')->with('success', "Surat {$surat->nomor_surat} dikembalikan ke Surat Keluar.");
    }

    public function destroyMasuk($id)
    {
        $surat = SuratMasuk::where('status', 'Arsip')->findOrFail($id);

        if ($surat->lampiran_path) {
            Storage::disk('public')->delete($surat->lampiran_path);
        }

        $surat->delete();

        return redirect()->route('arsip.index')->with('success', 'Arsip surat masuk dihapus permanen.');
    }

    public function destroyKeluar($id)
    {
        $surat = SuratKeluar::where('status', 'Arsip')->findOrFail($id);

        if ($surat->lampiran_path) {
            Storage::disk('public')->delete($surat->lampiran_path);
        }

        $surat->delete();

        return redirect()->route('arsip.index')->with('success', 'Arsip surat keluar dihapus permanen.');
    }

    public function clear()
    {
        $arsipMasuk = SuratMasuk::where('status', 'Arsip')->get();
        foreach ($arsipMasuk as $surat) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
            $surat->delete();
        }

        $arsipKeluar = SuratKeluar::where('status', 'Arsip')->get();
        foreach ($arsipKeluar as $surat) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
            $surat->delete();
        }

        return redirect()->route('arsip.index')->with('success', 'Semua arsip surat telah dihapus.');
    }
}
