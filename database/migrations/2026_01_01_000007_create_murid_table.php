<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('murid', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('nisn_nis', 30)->nullable()->index();
            $table->string('no_kp', 20);
            $table->string('nama_penuh', 255)->index();
            $table->enum('jantina', ['LELAKI', 'PEREMPUAN']);
            $table->date('tarikh_lahir');
            $table->enum('status_murid', ['AKTIF', 'ALUMNI', 'PINDAH', 'GANTUNG', 'BUANG'])->default('AKTIF')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['sekolah_id', 'no_kp'], 'unique_sekolah_murid_nokp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('murid');
    }
};
