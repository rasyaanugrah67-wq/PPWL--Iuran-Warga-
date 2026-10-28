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
       Schema::create('iuran', function (Blueprint $table) {
        $table->id('id_iuran');
        $table->foreignId('id_warga')->constrained('warga', 'id_warga');
        $table->integer('id_periode')->default(1);
        $table->integer('nominal');
        $table->date('jatuh_tempo');
        $table->string('status');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iurans');
    }
};
