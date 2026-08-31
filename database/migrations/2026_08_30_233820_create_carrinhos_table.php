<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Carrinhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UsuarioId')->unique()->constrained('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Carrinhos');
    }
};