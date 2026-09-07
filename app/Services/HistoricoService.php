<?php

namespace App\Services;

use App\Models\ItemVenda;
use App\Models\Usuario;
use App\Models\Venda;
use Illuminate\Support\Collection;

class HistoricoService
{
    public function compras(Usuario $usuario, ?string $inicio = null, ?string $fim = null): Collection
    {
        $query = Venda::query()
            ->where('CompradorId', $usuario->getKey())
            ->with([
                'comprador',
                'itens.produto.categoria',
                'itens.vendedor',
            ])
            ->latest();

        $this->aplicarPeriodo($query, $inicio, $fim);

        return $query
            ->get()
            ->flatMap(function (Venda $venda): Collection {
                return $venda->itens->map(function (ItemVenda $item) use ($venda): array {
                    return $this->formatarRegistro($item, $venda);
                });
            })
            ->values();
    }

    public function vendas(Usuario $usuario, ?string $inicio = null, ?string $fim = null): Collection
    {
        $query = ItemVenda::query()
            ->with([
                'venda.comprador',
                'produto.categoria',
                'vendedor',
            ]);

        if ($usuario->tipo !== 'administrador') {
            $query->where('VendedorId', $usuario->getKey());
        }

        if ($inicio) {
            $query->whereHas('venda', function ($consulta) use ($inicio): void {
                $consulta->whereDate('created_at', '>=', $inicio);
            });
        }

        if ($fim) {
            $query->whereHas('venda', function ($consulta) use ($fim): void {
                $consulta->whereDate('created_at', '<=', $fim);
            });
        }

        return $query
            ->get()
            ->sortByDesc(fn (ItemVenda $item) => $item->venda->created_at)
            ->map(function (ItemVenda $item): array {
                return $this->formatarRegistro($item, $item->venda, true);
            })
            ->values();
    }

    public function graficoVendas(Usuario $usuario): array
    {
        abort_if(
            $usuario->tipo === 'administrador',
            403,
            'O gráfico de vendas está disponível apenas para usuários.'
        );

        $inicio = now()->startOfMonth()->subMonths(11);
        $fim = now()->endOfMonth();

        $vendasPorMes = ItemVenda::query()
            ->where('VendedorId', $usuario->getKey())
            ->whereHas('venda', function ($query) use ($inicio, $fim): void {
                $query->whereBetween('created_at', [$inicio, $fim]);
            })
            ->with('venda')
            ->get()
            ->groupBy(fn (ItemVenda $item): string => $item->venda->created_at->format('Y-m'))
            ->map(fn (Collection $itens): int => $itens->pluck('VendasId')->unique()->count());

        $nomesMeses = [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez',
        ];

        $labels = [];
        $values = [];

        for ($indice = 11; $indice >= 0; $indice--) {
            $mes = now()->startOfMonth()->subMonths($indice);
            $chaveDoMes = $mes->format('Y-m');
            $numeroDoMes = (int) $mes->format('n');

            $labels[] = $nomesMeses[$numeroDoMes];
            $values[] = (int) ($vendasPorMes[$chaveDoMes] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function aplicarPeriodo($query, ?string $inicio, ?string $fim): void
    {
        if ($inicio) {
            $query->whereDate('created_at', '>=', $inicio);
        }

        if ($fim) {
            $query->whereDate('created_at', '<=', $fim);
        }
    }

    private function formatarRegistro(
        ItemVenda $item,
        Venda $venda,
        bool $visualizacaoDeVendas = false
    ): array {
        $outraPessoa = $visualizacaoDeVendas
            ? $venda->comprador?->nome
            : $item->vendedor?->nome;

        return [
            'produto' => $item->produto?->nome ?? 'Produto indisponível',
            'foto' => $item->produto?->foto,
            'category' => $item->produto?->categoria?->nome ?? 'Sem categoria',
            'date' => $venda->created_at->format('d/m/Y'),
            'other' => $outraPessoa ?? 'Não informado',
            'value' => (float) $item->subtotal,
            'quantidade' => (int) $item->quantidade,
            'comprador' => $venda->comprador?->nome ?? 'Não informado',
            'vendedor' => $item->vendedor?->nome ?? 'Não informado',
            'status' => $venda->StatusPagamento,
        ];
    }
}