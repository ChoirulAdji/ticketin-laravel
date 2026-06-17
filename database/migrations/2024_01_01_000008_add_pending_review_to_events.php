<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum status untuk tambah 'pending_review'
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft','pending_review','published','cancelled') NOT NULL DEFAULT 'pending_review'");

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('catatan_admin')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft','published','cancelled') NOT NULL DEFAULT 'draft'");
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'catatan_admin']);
        });
    }
};
