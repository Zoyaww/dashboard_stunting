<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekap_puskesmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puskesmas_id')->constrained('puskesmas')->cascadeOnDelete();
            $table->year('tahun');
            $table->dateTime('tanggal_data')->nullable();

            $table->integer('sangat_kurang')->default(0);
            $table->integer('kurang')->default(0);
            $table->integer('berat_badan_normal')->default(0);
            $table->integer('risiko_lebih')->default(0);
            $table->integer('outlier_bbu')->default(0);

            $table->integer('sangat_pendek')->default(0);
            $table->integer('pendek')->default(0);
            $table->integer('normal_tbu')->default(0);
            $table->integer('tinggi')->default(0);
            $table->integer('outlier_tbu')->default(0);

            $table->integer('gizi_buruk')->default(0);
            $table->integer('gizi_kurang')->default(0);
            $table->integer('normal_bbtb')->default(0);
            $table->integer('risiko_gizi_lebih')->default(0);
            $table->integer('gizi_lebih')->default(0);
            $table->integer('obesitas')->default(0);
            $table->integer('outlier_bbtb')->default(0);

            $table->integer('stunting')->default(0);
            $table->integer('wasting')->default(0);
            $table->integer('underweight')->default(0);

            $table->integer('total_balita_ditimbang')->default(0);
            $table->integer('sasaran_riil_balita')->default(0);

            $table->decimal('ds', 8, 2)->default(0);
            $table->decimal('prev_stunted', 8, 2)->default(0);
            $table->decimal('prev_wasting', 8, 2)->default(0);
            $table->decimal('prev_underweight', 8, 2)->default(0);

            $table->timestamps();

            $table->unique(['puskesmas_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_puskesmas');
    }
};