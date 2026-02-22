<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Usuario', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password')->nullable();     // para Google OAuth podría venir null inicialmente
            $table->string('googleId')->nullable();
            $table->string('rol');                      // ADMIN | ESTUDIANTE
            $table->boolean('estado')->default(true);

            $table->boolean('emailVerified')->default(false);
            $table->dateTime('tokenExpiresAt')->nullable();
            $table->string('verificationToken')->nullable();

            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Usuario');
    }
};