<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('murid_penjaga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->constrained('murid')->cascadeOnDelete();
            $table->foreignId('penjaga_id')->constrained('penjaga')->cascadeOnDelete();
            $table->boolean('is_penjaga_utama')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('murid_penjaga');
    }
};
