<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eo_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_organisasi');
            $table->string('jenis_entitas')->nullable(); // perorangan, cv, pt, yayasan, komunitas
            $table->string('skala_event')->nullable();   // kecil, menengah, besar
            $table->string('alamat_organisasi')->nullable();
            $table->string('website')->nullable();
            $table->string('npwp')->nullable();
            $table->string('dokumen_legalitas')->nullable(); // path file
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('no_hp_bisnis')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable(); // alasan reject
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // Tambah kolom status verifikasi ke users
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_akun', ['active', 'suspended'])->default('active')->after('role');
            $table->boolean('eo_verified')->default(false)->after('status_akun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eo_applications');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_akun', 'eo_verified']);
        });
    }
};
