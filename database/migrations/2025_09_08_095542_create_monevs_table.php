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
        Schema::create('monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
            $table->foreignId('id_renja')->references('id')->on('rencana_kerjas')->onDelete('cascade');
            $table->foreignId('id_subprogram')->references('id')->on('subprograms')->onDelete('cascade');
            $table->unsignedBigInteger('id_opd')->nullable();
            $table->foreign('id_opd')->references('id')->on('opds')->onDelete('set null');


            $table->string('anggaran');
            $table->string('sumberdana');
            $table->json('dokumen_anggaran')->nullable();
            $table->json('realisasi')->nullable();
            $table->json('volumeTarget')->nullable();
            $table->json('satuan_realisasi')->nullable();
            $table->string('pesan')->nullable();
            $table->string('status')->default('Belum divalidasi');
            $table->json('uraian')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->dateTime('edit_open_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monevs');
    }
};
