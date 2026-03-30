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
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('juz');
            $table->string('nama_surat');
            $table->integer('ayat_mulai');
            $table->integer('ayat_selesai');
            $table->integer('jumlah_baris')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('kehadiran', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->integer('nilai_kelancaran')->default(100);
            $table->enum('jenis_setoran', ['sabaq', 'sabqi', 'manzil']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
