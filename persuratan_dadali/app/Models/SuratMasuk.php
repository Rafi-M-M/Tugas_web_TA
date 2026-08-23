<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}