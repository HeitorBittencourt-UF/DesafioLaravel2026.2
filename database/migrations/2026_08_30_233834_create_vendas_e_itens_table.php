<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CompradorId')->constrained('Usuarios')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('ValorTotal', 10, 2);
            $table->enum('StatusPagamento', ['pendente', 'pago', 'cancelado', 'reembolsado'])->default('pendente');
            $table->enum('LocalPagamento', ['mercadopago', 'pagseguro']);
            $table->string('codigo_transacao', 255);
            $table->timestamps();
        });

        Schema::create('ItensVendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('VendasId')->constrained('Vendas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ProdutoId')->constrained('Produtos')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('VendedorId')->constrained('Usuarios')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('quantidade');
            $table->decimal('ValorUnitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ItensVendas');
        Schema::dropIfExists('Vendas');
    }
};