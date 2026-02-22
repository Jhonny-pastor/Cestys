<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Administrador', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarioId')->unique();
            $table->text('permisos')->nullable();
            $table->boolean('puedeAsignarRol')->default(false);
            $table->unsignedBigInteger('asignadoPor')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
            $table->foreign('asignadoPor')->references('id')->on('Usuario')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Administrador');
    }
};