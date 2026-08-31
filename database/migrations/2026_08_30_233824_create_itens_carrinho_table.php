<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ItensCarrinho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CarrinhoId')->constrained('Carrinhos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ProdutoId')->constrained('Produtos')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
            $table->unique(['CarrinhoId', 'ProdutoId']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('ItensCarrinho');
    }
};