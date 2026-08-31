<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Usuarios_Enderecos', function (Blueprint $table) {
            $table->foreignId('UsuarioId')->constrained('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('EnderecoId')->constrained('Enderecos')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['UsuarioId', 'EnderecoId']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('Usuarios_Enderecos');
    }
};