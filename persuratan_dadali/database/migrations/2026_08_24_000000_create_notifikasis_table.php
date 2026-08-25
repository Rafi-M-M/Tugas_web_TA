<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('disposisi_id')->nullable()->constrained('disposisis')->cascadeOnDelete();
            $table->string('tipe');
            $table->string('judul');
            $table->text('pesan');
            $table->string('url')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'dibaca_pada']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasis');
    }
};