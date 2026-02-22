<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Tema', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('moduloId');
            $table->integer('duracion')->default(0);
            $table->integer('orden')->default(0);
            $table->string('videoUrl')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('moduloId')->references('id')->on('Modulo')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Tema');
    }
};