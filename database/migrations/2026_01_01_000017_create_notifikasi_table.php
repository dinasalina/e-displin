<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('tajuk', 255);
            $table->text('mesej');
            $table->string('url_tindakan', 255)->nullable();
            $table->boolean('is_dibaca')->default(false)->index();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['sekolah_id', 'penerima_id', 'is_dibaca']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
