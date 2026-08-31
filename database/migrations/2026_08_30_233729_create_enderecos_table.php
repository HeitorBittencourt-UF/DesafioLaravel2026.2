<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Enderecos', function (Blueprint $table) {
            $table->id();
            $table->char('cep', 8);
            $table->string('logradouro', 150);
            $table->string('numero', 10);
            $table->string('complemento', 100)->nullable();
            $table->string('bairro', 100);
            $table->string('cidade', 100);
            $table->char('estado', 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Enderecos');
    }
};