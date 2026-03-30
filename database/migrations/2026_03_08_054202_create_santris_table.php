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
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('kelas', ['7', '8', '9', '10', '11', '12']);
            $table->string('kelas_halaqah');
            $table->string('nisn')->nullable();
            $table->foreignId('ustadz_id')->nullable()->constrained('ustadzs')->nullOnDelete();
            $table->string('nama_orangtua')->nullable();
            $table->string('wa_orangtua')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
