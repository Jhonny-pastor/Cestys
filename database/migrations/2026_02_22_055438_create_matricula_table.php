<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Matricula', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('cursoId');
            $table->string('estado'); // ACTIVE | CANCELLED
            $table->decimal('progreso', 5, 2)->default(0);

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('cursoId')->references('id')->on('Curso')->cascadeOnDelete();

            $table->unique(['usuarioId', 'cursoId']); // RESTRICCIÓN RECOMENDADA
        });
    }

    public function down(): void {
        Schema::dropIfExists('Matricula');
    }
};