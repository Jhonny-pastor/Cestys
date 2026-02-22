<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Nota', function (Blueprint $table) {
            $table->id();
            $table->integer('valor')->default(0); // 0-20
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('cursoId');

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('cursoId')->references('id')->on('Curso')->cascadeOnDelete();

            $table->unique(['usuarioId', 'cursoId']); // RESTRICCIÓN RECOMENDADA
        });
    }

    public function down(): void {
        Schema::dropIfExists('Nota');
    }
};