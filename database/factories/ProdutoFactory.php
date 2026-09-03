<?php

namespace Database\Factories;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Bezhanov\Faker\Provider\Commerce;
use Bezhanov\Faker\Provider\Device;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        // Adiciona os geradores de produto e dispositivos ao Faker
        $this->faker->addProvider(new Commerce($this->faker));
        $this->faker->addProvider(new Device($this->faker));

        return [
            'nome' => ucfirst($this->faker->productName()), // Nome real de produto 
            'descricao' => $this->faker->realText(150),
            'preco' => $this->faker->randomFloat(2, 50, 2500), // Numero aleatorio de 50 ate 2500
            'quantidade' => $this->faker->numberBetween(1, 50), //n aleatorio de 1 a 50
            'foto' => 'https://picsum.photos/640/480?random=' . rand(1, 1000), //Pega imaegns aleatorias de 640 por 480 px / usando o Lorem Picsum / com numeros aleatorios de 1 a 1000 para aleatorizar
            'categoria_id' => Categoria::factory(),
            'UsuarioId' => Usuario::factory(),
        ];
    }
}