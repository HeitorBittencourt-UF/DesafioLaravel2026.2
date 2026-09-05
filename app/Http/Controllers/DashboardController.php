<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $graficoProdutos = null;

        if ($request->user()->tipo === 'administrador') { // checa se é admin
            $inicioDoPeriodo = now()->startOfMonth()->subMonths(11); // faz uma janela de 11 meses atras
            $fimDoPeriodo = now()->endOfMonth(); // define o 12 mes como o atual

            $produtosPorMes = Produto::query()
                ->whereBetween('created_at', [$inicioDoPeriodo, $fimDoPeriodo]) //consulta o banco para pegar os created at dentro do intervalo
                ->get(['created_at'])
                ->countBy(fn (Produto $produto) => $produto->created_at->format('Y-m')); // faz um count

            $nomesDosMeses = [
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
            ]; //nomeia os meses

            $labels = [];
            $valores = [];

            for ($mes = 0; $mes < 12; $mes++) {
                $data = $inicioDoPeriodo->copy()->addMonths($mes); // copy para nao sobrescrever
                $chaveDoMes = $data->format('Y-m'); // trata so como ano e mes

                $labels[] = $nomesDosMeses[(int) $data->format('n')];
                $valores[] = $produtosPorMes->get($chaveDoMes, 0); // caso n haja vira 0
            }

            $graficoProdutos = [
                'labels' => $labels, // pega os meses (nome)
                'valores' => $valores, // pega as inserções de valores
            ];
        }

        return view('dashboard', compact('graficoProdutos')); // envia pra view 
    }
}
