<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_masuk_id', 'user_id', 'tanggal_disposisi', 'ditujukan_kepada',
        'sifat', 'instruksi', 'catatan', 'batas_waktu', 'status',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'date',
        'batas_waktu' => 'date',
    ];

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}