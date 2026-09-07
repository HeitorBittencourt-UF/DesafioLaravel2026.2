<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categorias
        |--------------------------------------------------------------------------
        */

        $categorias =
            Categoria::factory(13)
            ->create();


        /*
        |--------------------------------------------------------------------------
        | Usuários
        |--------------------------------------------------------------------------
        */

        $usuarios =
            Usuario::factory(8)
            ->create([
                'tipo' => 'usuario',
            ]);

            Usuario::factory(2)
            ->create([
                'tipo' => 'administrador',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Produtos
        |--------------------------------------------------------------------------
        */

        Produto::factory(100)->create([
            'categoria_id' =>
            fn() =>
            $categorias
                ->random()
                ->id,

            'UsuarioId' =>
            fn() =>
            $usuarios
                ->random()
                ->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vendas
        |--------------------------------------------------------------------------
        */

        $this->call(
            VendaSeeder::class
        );
    }
}
