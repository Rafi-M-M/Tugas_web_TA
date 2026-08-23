<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->text('isi_surat')->nullable()->after('template_surat');
            $table->json('format_surat')->nullable()->after('isi_surat');
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn(['isi_surat', 'format_surat']);
        });
    }
};
