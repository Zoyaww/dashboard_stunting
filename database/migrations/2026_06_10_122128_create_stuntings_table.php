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
    Schema::create('stuntings', function (Blueprint $table) {

        $table->id();

        $table->string('nama');

        $table->string('desa');

        $table->string('kecamatan');

        $table->double('latitude');

        $table->double('longitude');

        $table->string('status');

        $table->timestamps();

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stuntings');
    }
};
