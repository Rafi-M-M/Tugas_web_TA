<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'tujuan',
        'template_surat',
        'isi_surat',
        'format_surat',
        'status',
        'lampiran_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_surat' => 'date',
        'format_surat' => 'array',
    ];
}