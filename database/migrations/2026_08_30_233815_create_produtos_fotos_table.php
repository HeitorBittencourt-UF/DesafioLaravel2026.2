<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Produtos_Fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ProdutoId')->constrained('Produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->string('foto', 255);
            $table->boolean('principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Produtos_Fotos');
    }
};