<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sejarah_status_kes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekod_disiplin_id')->constrained('rekod_disiplin')->cascadeOnDelete();
            $table->foreignId('dikemaskini_oleh_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('status_asal', 50)->nullable();
            $table->string('status_baharu', 50);
            $table->text('nota_perubahan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sejarah_status_kes');
    }
};
