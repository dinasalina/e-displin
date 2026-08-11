<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran_disiplin', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('rekod_disiplin_id')->constrained('rekod_disiplin')->cascadeOnDelete();
            $table->string('nama_fail_asal', 255);
            $table->string('path_fail', 255);
            $table->string('mime_type', 100);
            $table->bigInteger('saiz_bytes')->unsigned();
            $table->foreignId('muat_naik_oleh_id')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_disiplin');
    }
};
