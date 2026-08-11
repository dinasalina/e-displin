<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eskalasi_kes', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('rekod_disiplin_id')->constrained('rekod_disiplin')->cascadeOnDelete();
            $table->foreignId('ditugaskan_oleh_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('jenis_eskalasi', 50);
            $table->string('status', 50)->default('MENUNGGU');
            $table->text('catatan')->nullable();
            $table->string('keputusan', 100)->nullable();
            $table->text('catatan_keputusan')->nullable();
            $table->dateTime('ditugaskan_pada');
            $table->dateTime('diputuskan_pada')->nullable();
            $table->timestamps();

            $table->index(['rekod_disiplin_id', 'penerima_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eskalasi_kes');
    }
};
