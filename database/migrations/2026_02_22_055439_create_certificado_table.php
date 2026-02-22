<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Certificado', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('link')->nullable();
            $table->dateTime('fechaEmision')->nullable();
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('cursoId');
            $table->string('estado'); // VALID | INVALID (o el que uses)
            $table->text('notasAdicionales')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('cursoId')->references('id')->on('Curso')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Certificado');
    }
};