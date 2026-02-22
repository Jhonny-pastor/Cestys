<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Asistencia', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('cursoId');

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('cursoId')->references('id')->on('Curso')->cascadeOnDelete();

            $table->unique(['usuarioId', 'cursoId', 'fecha']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('Asistencia');
    }
};