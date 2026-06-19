<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekap_kecamatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
            $table->year('tahun');

            $table->integer('jumlah_stunting')->default(0);
            $table->integer('jumlah_balita_diukur')->default(0);
            $table->decimal('prevalensi_stunting', 8, 2)->default(0);

            $table->timestamps();

            $table->unique(['kecamatan_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_kecamatans');
    }
};