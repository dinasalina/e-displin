<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekod_disiplin', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->string('no_kes', 50)->unique()->index();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->foreignId('murid_id')->constrained('murid')->cascadeOnDelete();
            $table->foreignId('pelapor_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('kategori_disiplin_id')->constrained('kategori_disiplin')->cascadeOnDelete();
            $table->enum('tahap_kes', ['RINGAN', 'SEDERHANA', 'BERAT'])->index();
            $table->enum('status_kes', ['DILAPORKAN', 'DALAM_SEMAKAN', 'DALAM_TINDAKAN', 'MENUNGGU_KELULUSAN', 'DITUTUP'])->default('DILAPORKAN')->index();
            $table->dateTime('tarikh_kejadian')->index();
            $table->string('lokasi_kejadian', 255);
            $table->longText('keterangan_kes');
            $table->text('ringkasan_ai')->nullable();
            $table->boolean('is_void')->default(false)->index();
            $table->text('void_reason')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('voided_at')->nullable();
            $table->dateTime('tarikh_ditutup')->nullable();
            $table->timestamps();

            $table->index(['sekolah_id', 'status_kes', 'tahap_kes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekod_disiplin');
    }
};
