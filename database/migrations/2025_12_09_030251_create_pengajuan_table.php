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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->enum('jenis', ['tower', 'central'])->index();
            $table->string('tower_kode')->nullable()->index();
            $table->string('central_kode')->nullable()->index();
            $table->string('perusahaan_tamu');
            $table->string('perusahaan_pengutus');
            $table->string('jumlah');
            $table->string('kategori');
            $table->text('keperluan');

            $table->string('jam_mulai', 5);
            $table->string('jam_selesai', 5);
            $table->string('lama_berkerja');

            $table->enum('identitas', ['KTP', 'SIM']);
            $table->string('identitas_file')->nullable();
            $table->string('masa_berlaku');
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('status')->default('open')->index();
            $table->string('progress');
            $table->timestamps();
            $table->foreign('tower_kode')
                ->references('kode_aset')
                ->on('list_aset')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign('central_kode')
                ->references('kode_aset')
                ->on('list_aset')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
