<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'pengirim',
        'perihal',
        'isi_ringkas',
        'lampiran_path',
        'status',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];
    public function disposisis(): HasMany
    {
    return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

}