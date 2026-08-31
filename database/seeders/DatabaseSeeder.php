<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Usuario;
use App\Models\Produto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // 1. Criar Categorias fixas
        $categorias = Categoria::factory(5)->create();

        // 2. Criar Usuários
        $usuarios = Usuario::factory(10)->create();

        // 3. Criar 20 Produtos sorteando aleatoriamente entre os Usuários e Categorias criados
        Produto::factory(20)->create([
            'categoria_id' => fn () => $categorias->random()->id,
            'UsuarioId' => fn () => $usuarios->random()->id,
        ]);
    }
}
