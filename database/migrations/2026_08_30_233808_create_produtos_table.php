<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->text('descricao');
            $table->string('foto', 255)->nullable();
            $table->decimal('preco', 10, 2);
            $table->integer('quantidade');
            $table->foreignId('UsuarioId')->constrained('Usuarios')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('categoria_id')->constrained('Categorias')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('Produtos');
    }
};