<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Tiket
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('nama_kategori');
            $table->decimal('harga', 15, 2)->default(0);
            $table->integer('kuota')->default(0);
            $table->timestamps();
        });

        // Lineup Artis/Pembicara
        Schema::create('event_lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('nama');
            $table->boolean('is_headliner')->default(false);
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // FAQ Event
        Schema::create('event_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->timestamps();
        });

        // Orders (Pemesanan)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->integer('total_qty')->default(0);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('metode_bayar')->nullable();
            $table->text('ticket_summary')->nullable();
            $table->timestamps();
        });

        // Order Items (Detail tiket per order)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('ticket_category_id')->constrained('ticket_categories')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('event_faqs');
        Schema::dropIfExists('event_lineups');
        Schema::dropIfExists('ticket_categories');
    }
};
