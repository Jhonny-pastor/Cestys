<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Curso', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('imagenPortada')->nullable(); // storage local
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->integer('horas')->default(0);
            $table->decimal('valoracion', 3, 1)->default(0);
            $table->string('estado'); // DRAFT | PUBLISHED | ARCHIVED
            $table->unsignedBigInteger('categoriaId');

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('categoriaId')->references('id')->on('Categoria')->restrictOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Curso');
    }
};