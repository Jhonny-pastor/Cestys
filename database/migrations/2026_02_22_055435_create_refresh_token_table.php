<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('RefreshToken', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->unsignedBigInteger('usuarioId');
            $table->dateTime('expiresAt');
            $table->dateTime('createdAt')->useCurrent();

            $table->foreign('usuarioId')->references('id')->on('Usuario')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('RefreshToken');
    }
};