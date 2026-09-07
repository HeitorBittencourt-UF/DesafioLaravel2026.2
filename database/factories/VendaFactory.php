<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendaFactory extends Factory
{
    protected $model = Venda::class;

    public function definition(): array
    {
        return [
            'CompradorId' => Usuario::factory(),

            // Será recalculado pelo Seeder depois que os itens forem criados.
            'ValorTotal' => 0,

            // Para nossos dados de teste contarem como vendas concluídas.
            'StatusPagamento' => 'pago',

            'LocalPagamento' => $this->faker->randomElement([
                'mercadopago',
                'pagseguro',
            ]),

            'codigo_transacao' => 'SEED-' . $this->faker->unique()->uuid(),
        ];
    }
}