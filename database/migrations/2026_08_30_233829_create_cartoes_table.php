<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Cartoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UsuarioId')->constrained('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->string('token', 255)->unique();
            $table->string('bandeira', 50);
            $table->char('ultimos_digitos', 4);
            $table->tinyInteger('mes_expiracao');
            $table->smallInteger('ano_expiracao');
            $table->boolean('principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Cartoes');
    }
};