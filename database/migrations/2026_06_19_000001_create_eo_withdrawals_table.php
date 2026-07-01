<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eo_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengelola_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'processed', 'rejected'])->default('pending');
            $table->string('bank');
            $table->string('nomor_rekening', 50);
            $table->string('nama_rekening');
            $table->text('catatan')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eo_withdrawals');
    }
};
