<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        static $indice = 0;

        $categorias = [
            'Tv',
            'Pc',
            'Games',
            'Hardware',
            'Relogio',
            'Celular',
            'Audio',
            'Perifericos',
            'GiftCard',
            'Cameras',
            'Casa',
            'Eletrodomestico',
            'Outros',
        ];

        $categoria = $categorias[$indice];

        $indice++;

        return [
            'nome' => $categoria,
        ];
    }
}