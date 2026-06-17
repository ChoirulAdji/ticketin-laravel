<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengelola_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('kategori');
            $table->string('lokasi_kota');
            $table->string('venue');
            $table->dateTime('tanggal_waktu');
            $table->text('deskripsi')->nullable();
            $table->string('gambar_cover')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
