<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_foto', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->index();
            $table->string('kategori')->index();
            $table->boolean('pengembalian')->default(false)->index();
            $table->string('path')->nullable();
            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();

            // Unik per pengajuan + kategori + status pengembalian (agar tidak perlu kategori *_kembali)
            $table->unique(['kode_pengajuan', 'kategori', 'pengembalian']);

            $table->foreign('kode_pengajuan')
                ->references('kode_pengajuan')
                ->on('pengajuan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_foto');
    }
};
