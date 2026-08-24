<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_disposisi');
            $table->string('ditujukan_kepada');
            $table->string('sifat')->default('Biasa');
            $table->text('instruksi');
            $table->text('catatan')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->string('status')->default('Diproses');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disposisis');
    }
};