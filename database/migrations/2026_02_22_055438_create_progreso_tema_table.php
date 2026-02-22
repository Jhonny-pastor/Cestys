<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ProgresoTema', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('temaId');
            $table->boolean('completado')->default(false);
            $table->integer('tiempoVisto')->default(0);
            $table->dateTime('ultimoAcceso')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('temaId')->references('id')->on('Tema')->cascadeOnDelete();

            $table->unique(['usuarioId', 'temaId']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('ProgresoTema');
    }
};