<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Transaccion', function (Blueprint $table) {
            $table->id();
            $table->string('producto'); // texto (por ahora)
            $table->decimal('monto', 10, 2);
            $table->string('estado'); // PENDING | PAID | FAILED | CANCELLED
            $table->string('paymentId')->nullable();
            $table->string('preferenceId')->nullable();
            $table->unsignedBigInteger('usuarioId');
            $table->json('items')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Transaccion');
    }
};