<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Services\NotifikasiService;
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

    public function store(Request $request, NotifikasiService $notifikasiService)
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
        $disposisi->load(['suratMasuk', 'pembuat']);

        $notifikasiService->notifyRolesAndUsers(['pimpinan'], [], [
            'disposisi_id' => $disposisi->id,
            'tipe' => 'disposisi_baru',
            'judul' => 'Disposisi baru menunggu tinjauan',
            'pesan' => sprintf(
                'Disposisi surat %s bersifat %s ditujukan kepada %s, dibuat oleh %s.',
                $disposisi->suratMasuk?->nomor_surat ?? '-',
                $disposisi->sifat,
                $disposisi->ditujukan_kepada,
                $disposisi->pembuat?->name ?? '-'
            ),
            'url' => route('disposisi.show', $disposisi->id, false),
        ]);

        return redirect()->route('disposisi.index')
            ->with('success', "Disposisi untuk surat {$disposisi->suratMasuk->nomor_surat} berhasil dibuat.");
    }

    public function show($id)
    {
        $disposisi = Disposisi::with(['suratMasuk', 'pembuat'])->findOrFail($id);

        return view('Persuratan.disposisi_show', [
            'disposisi' => $disposisi,
            'sifatOptions' => self::SIFAT,
            'statusOptions' => self::STATUS,
        ]);
    }

    public function tinjau(Request $request, $id, NotifikasiService $notifikasiService)
    {
        $validated = $request->validate([
            'sifat' => 'required|in:' . implode(',', self::SIFAT),
            'status' => 'required|in:' . implode(',', self::STATUS),
            'catatan_pimpinan' => 'nullable|string',
        ]);

        $disposisi = Disposisi::with(['suratMasuk', 'pembuat'])->findOrFail($id);
        $disposisi->update(array_merge($validated, [
            'ditinjau_oleh' => $request->user()->id,
            'ditinjau_pada' => now(),
        ]));

        $notifikasiService->notifyRolesAndUsers(['admin'], [$disposisi->user_id], [
            'disposisi_id' => $disposisi->id,
            'tipe' => 'disposisi_ditinjau',
            'judul' => 'Disposisi telah ditinjau pimpinan',
            'pesan' => sprintf(
                '%s telah meninjau disposisi surat %s. Sifat: %s, status: %s.',
                $request->user()->name,
                $disposisi->suratMasuk?->nomor_surat ?? '-',
                $disposisi->sifat,
                $disposisi->status
            ),
            'url' => route('disposisi.show', $disposisi->id, false),
        ]);

        return redirect()->back()->with('success', 'Disposisi berhasil ditinjau.');
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