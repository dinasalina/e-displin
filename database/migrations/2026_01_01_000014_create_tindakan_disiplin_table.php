<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindakan_disiplin', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('rekod_disiplin_id')->constrained('rekod_disiplin')->cascadeOnDelete();
            $table->foreignId('tetap_oleh_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('jenis_tindakan', 100);
            $table->text('keterangan_tindakan');
            $table->date('tarikh_mula')->nullable();
            $table->date('tarikh_tamat')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindakan_disiplin');
    }
};
