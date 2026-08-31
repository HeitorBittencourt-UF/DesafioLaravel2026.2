<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('email', 150)->unique();
            $table->string('senha', 255);
            $table->enum('tipo', ['usuario', 'administrador']);
            $table->char('cpf', 11)->unique();
            $table->date('data_nascimento');
            $table->string('telefone', 20);
            $table->decimal('saldo', 10, 2)->default(0.00);
            $table->string('foto', 255)->nullable();
            
            // Auto-relacionamento (criador_id)
            $table->foreignId('criador_id')
                  ->nullable()
                  ->constrained('Usuarios')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};