<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Perfil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarioId')->unique();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('fotografia')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Perfil');
    }
};