<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Pago', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2);
            $table->string('metodo')->nullable();
            $table->string('referencia')->nullable();
            $table->string('estado');
            $table->unsignedBigInteger('matriculaId');

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('matriculaId')->references('id')->on('Matricula')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Pago');
    }
};