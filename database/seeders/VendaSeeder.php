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
        /*
        |--------------------------------------------------------------------------
        | Remove vendas antigas criadas por este Seeder
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Escolhe o vendedor usado para testar o gráfico
        |--------------------------------------------------------------------------
        */

        $vendedor = Usuario::query()
            ->where('email', 'fernando_abreu@exemplo.com')
            ->where('tipo', 'usuario')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Caso Fernando não exista, usa outro usuário com produtos
        |--------------------------------------------------------------------------
        */

        if (! $vendedor) {
            $vendedor = Usuario::query()
                ->where('tipo', 'usuario')
                ->whereHas('produtos')
                ->first();
        }

        if (! $vendedor) {
            $this->command?->error(
                'Nenhum vendedor com produtos foi encontrado.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Produtos pertencentes ao vendedor
        |--------------------------------------------------------------------------
        */

        $produtosDoVendedor = Produto::query()
            ->where(
                'UsuarioId',
                $vendedor->getKey()
            )
            ->where('quantidade', '>', 0)
            ->get();

        if ($produtosDoVendedor->isEmpty()) {
            $this->command?->error(
                "O vendedor {$vendedor->email} não possui produtos."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Compradores possíveis
        |--------------------------------------------------------------------------
        */

        $compradores = Usuario::query()
            ->where('tipo', 'usuario')
            ->where(
                'id',
                '!=',
                $vendedor->getKey()
            )
            ->get();

        if ($compradores->isEmpty()) {
            $this->command?->error(
                'É necessário pelo menos mais um usuário para ser comprador.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Quantidade de vendas nos últimos 12 meses
        |--------------------------------------------------------------------------
        |
        | Ordem:
        | 11 meses atrás → mês atual
        |
        */

        $quantidadesPorMes = [
            1,
            3,
            2,
            5,
            4,
            2,
            6,
            3,
            5,
            2,
            4,
            7,
        ];


        /*
        |--------------------------------------------------------------------------
        | Cria as vendas
        |--------------------------------------------------------------------------
        */

        foreach (
            $quantidadesPorMes as
            $indice => $quantidadeVendas
        ) {

            /*
             * índice 0 = 11 meses atrás
             * índice 11 = mês atual
             */
            $mesesAtras =
                11 - $indice;


            for (
                $numeroVenda = 1;
                $numeroVenda <= $quantidadeVendas;
                $numeroVenda++
            ) {

                /*
                |--------------------------------------------------------------------------
                | Comprador
                |--------------------------------------------------------------------------
                */

                $comprador =
                    $compradores->random();


                /*
                |--------------------------------------------------------------------------
                | Produto do Fernando / vendedor foco
                |--------------------------------------------------------------------------
                */

                $produto =
                    $produtosDoVendedor->random();


                /*
                |--------------------------------------------------------------------------
                | Data da venda
                |--------------------------------------------------------------------------
                */

                $dataVenda = now()
                    ->startOfMonth()
                    ->subMonths($mesesAtras)
                    ->addDays(
                        random_int(0, 20)
                    )
                    ->setTime(
                        random_int(8, 20),
                        random_int(0, 59)
                    );


                /*
                |--------------------------------------------------------------------------
                | Quantidade vendida
                |--------------------------------------------------------------------------
                */

                $quantidade = random_int(
                    1,
                    min(
                        3,
                        max(
                            1,
                            (int) $produto->quantidade
                        )
                    )
                );


                $valorUnitario =
                    (float) $produto->preco;

                $subtotal = round(
                    $valorUnitario * $quantidade,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Venda
                |--------------------------------------------------------------------------
                */

                $venda = Venda::factory()->create([
                    'CompradorId' =>
                        $comprador->getKey(),

                    'ValorTotal' =>
                        $subtotal,

                    'StatusPagamento' =>
                        'pago',

                    'LocalPagamento' =>
                        'mercadopago',

                    'codigo_transacao' =>
                        'SEED-'
                        . now()->format('YmdHis')
                        . '-'
                        . $indice
                        . '-'
                        . $numeroVenda
                        . '-'
                        . uniqid(),

                    'created_at' =>
                        $dataVenda,

                    'updated_at' =>
                        $dataVenda,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Item da venda
                |--------------------------------------------------------------------------
                */

                $item = $venda
                    ->itens()
                    ->make([
                        'ProdutoId' =>
                            $produto->getKey(),

                        'VendedorId' =>
                            $vendedor->getKey(),

                        'quantidade' =>
                            $quantidade,

                        'ValorUnitario' =>
                            $valorUnitario,

                        'subtotal' =>
                            $subtotal,
                    ]);

                $item->created_at =
                    $dataVenda;

                $item->updated_at =
                    $dataVenda;

                $item->save();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Resultado
        |--------------------------------------------------------------------------
        */

        $this->command?->info(
            'Vendas de teste criadas com sucesso.'
        );

        $this->command?->info(
            'Vendedor do gráfico: '
            . $vendedor->email
        );

        $this->command?->info(
            'Padrão mensal: '
            . implode(
                ', ',
                $quantidadesPorMes
            )
        );
    }
}