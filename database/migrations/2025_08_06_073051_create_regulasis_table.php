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
        Schema::create('regulasis', function (Blueprint $table) {
            $table->id();
             $table->foreignId('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
            $table->string('judul');
            $table->string('tanggal');
            $table->string('status');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulasis');
    }
};
