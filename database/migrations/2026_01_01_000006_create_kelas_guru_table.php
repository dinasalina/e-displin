<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->string('peranan', 50)->default('GURU_UTAMA');
            $table->date('tarikh_mula');
            $table->date('tarikh_tamat')->nullable();
            $table->timestamps();

            $table->index(['kelas_id', 'tahun_akademik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_guru');
    }
};
