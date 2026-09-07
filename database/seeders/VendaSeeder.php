<?php

namespace Database\Seeders;

use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Usuario;
use App\Models\Venda;
use Illuminate\Database\Seeder;

class VendaSeeder extends Seeder
{
    public function run(): void
    {
        $vendasSeed = Venda::query()
            ->where('codigo_transacao', 'like', 'SEED-%')
            ->pluck('id');

        if ($vendasSeed->isNotEmpty()) {
            ItemVenda::query()
                ->whereIn('VendasId', $vendasSeed)
                ->delete();

            Venda::query()
                ->whereIn('id', $vendasSeed)
                ->delete();
        }

        $vendedor = Usuario::query()
            ->where('email', 'fernando_abreu@exemplo.com')
            ->where('tipo', 'usuario')
            ->first();

        if (! $vendedor) {
            $vendedor = Usuario::query()
                ->where('tipo', 'usuario')
                ->whereHas('produtos')
                ->first();
        }

        if (! $vendedor) {
            $this->command?->error('Nenhum vendedor com produtos foi encontrado.');
            return;
        }

        $produtosDoVendedor = Produto::query()
            ->where('UsuarioId', $vendedor->getKey())
            ->where('quantidade', '>', 0)
            ->get();

        if ($produtosDoVendedor->isEmpty()) {
            $this->command?->error("O vendedor {$vendedor->email} não possui produtos.");
            return;
        }

        $compradores = Usuario::query()
            ->where('tipo', 'usuario')
            ->where('id', '!=', $vendedor->getKey())
            ->get();

        if ($compradores->isEmpty()) {
            $this->command?->error('É necessário pelo menos mais um usuário para ser comprador.');
            return;
        }

        $quantidadesPorMes = [1, 3, 2, 5, 4, 2, 6, 3, 5, 2, 4, 7];

        foreach ($quantidadesPorMes as $indice => $quantidadeVendas) {
            $mesesAtras = 11 - $indice;

            for ($numeroVenda = 1; $numeroVenda <= $quantidadeVendas; $numeroVenda++) {
                $comprador = $compradores->random();
                $produto = $produtosDoVendedor->random();

                $dataVenda = now()
                    ->startOfMonth()
                    ->subMonths($mesesAtras)
                    ->addDays(random_int(0, 20))
                    ->setTime(random_int(8, 20), random_int(0, 59));

                $quantidade = random_int(
                    1,
                    min(3, max(1, (int) $produto->quantidade))
                );

                $valorUnitario = (float) $produto->preco;
                $subtotal = round($valorUnitario * $quantidade, 2);

                $venda = Venda::factory()->create([
                    'CompradorId' => $comprador->getKey(),
                    'ValorTotal' => $subtotal,
                    'StatusPagamento' => 'pago',
                    'LocalPagamento' => 'mercadopago',
                    'codigo_transacao' => 'SEED-' . now()->format('YmdHis') . '-' . $indice . '-' . $numeroVenda . '-' . uniqid(),
                    'created_at' => $dataVenda,
                    'updated_at' => $dataVenda,
                ]);

                $item = $venda->itens()->make([
                    'ProdutoId' => $produto->getKey(),
                    'VendedorId' => $vendedor->getKey(),
                    'quantidade' => $quantidade,
                    'ValorUnitario' => $valorUnitario,
                    'subtotal' => $subtotal,
                ]);

                $item->created_at = $dataVenda;
                $item->updated_at = $dataVenda;
                $item->save();
            }
        }

        $this->command?->info('Vendas de teste criadas com sucesso.');
        $this->command?->info('Vendedor do gráfico: ' . $vendedor->email);
        $this->command?->info('Padrão mensal: ' . implode(', ', $quantidadesPorMes));
    }
}