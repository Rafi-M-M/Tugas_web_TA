<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMasuk = SuratMasuk::where('status', 'Masuk')->count();
        $totalKeluar = SuratKeluar::where('status', 'Keluar')->count();
        $totalArsipMasuk = SuratMasuk::where('status', 'Arsip')->count();
        $totalArsipKeluar = SuratKeluar::where('status', 'Arsip')->count();

        $masukHariIni = SuratMasuk::where('status', 'Masuk')
            ->whereDate('created_at', Carbon::today())
            ->count();
        $keluarHariIni = SuratKeluar::where('status', 'Keluar')
            ->whereDate('created_at', Carbon::today())
            ->count();
        $suratHariIni = $masukHariIni + $keluarHariIni;

        $suratTerbaru = $this->getSuratTerbaru();
        $aktivitasTerakhir = $this->getAktivitasTerakhir();

        return view('dashboard', compact(
            'totalMasuk',
            'totalKeluar',
            'totalArsipMasuk',
            'totalArsipKeluar',
            'masukHariIni',
            'keluarHariIni',
            'suratHariIni',
            'suratTerbaru',
            'aktivitasTerakhir'
        ));
    }

    private function getSuratTerbaru()
    {
        $masuk = SuratMasuk::latest()->get()->map(function ($surat) {
            return [
                'nomor_surat' => $surat->nomor_surat,
                'perihal' => $surat->perihal,
                'status' => $surat->status,
                'tanggal' => $surat->tanggal_surat,
                'created_at' => $surat->created_at,
            ];
        });

        $keluar = SuratKeluar::latest()->get()->map(function ($surat) {
            return [
                'nomor_surat' => $surat->nomor_surat,
                'perihal' => $surat->perihal,
                'status' => $surat->status,
                'tanggal' => $surat->tanggal_surat,
                'created_at' => $surat->created_at,
            ];
        });

        return $masuk->concat($keluar)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
    }

    private function getAktivitasTerakhir()
    {
        $masuk = SuratMasuk::latest('updated_at')->limit(5)->get()->map(function ($surat) {
            return [
                'icon' => $surat->status === 'Arsip' ? 'fa-archive' : 'fa-inbox',
                'text' => $surat->status === 'Arsip'
                    ? "Surat masuk <span>{$surat->nomor_surat}</span> diarsipkan"
                    : "Surat masuk <span>{$surat->nomor_surat}</span> ditambahkan",
                'time' => $surat->updated_at,
            ];
        });

        $keluar = SuratKeluar::latest('updated_at')->limit(5)->get()->map(function ($surat) {
            return [
                'icon' => $surat->status === 'Arsip' ? 'fa-archive' : 'fa-paper-plane',
                'text' => $surat->status === 'Arsip'
                    ? "Surat keluar <span>{$surat->nomor_surat}</span> diarsipkan"
                    : "Surat keluar <span>{$surat->nomor_surat}</span> ditambahkan",
                'time' => $surat->updated_at,
            ];
        });

        return $masuk->concat($keluar)
            ->sortByDesc('time')
            ->take(5)
            ->values();
    }
}
