<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->string('kod_sekolah', 20)->unique();
            $table->string('nama_sekolah', 255);
            $table->string('kod_ppd', 20)->nullable();
            $table->string('kod_jpn', 20)->nullable();
            $table->enum('jenis_sekolah', ['RENDAH', 'MENENGAH']);
            $table->string('telefon', 20)->nullable();
            $table->string('emel', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
