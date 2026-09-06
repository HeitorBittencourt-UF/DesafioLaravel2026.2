<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoricoController extends Controller
{
    public function __construct(private readonly HistoricoService $historico)
    {
    }

    public function purchases(Request $request): View
    {
        $periodo = $this->validarPeriodo($request);
        $registros = $this->historico->compras(
            $this->usuarioAutenticado($request),
            $periodo['inicio'] ?? null,
            $periodo['fim'] ?? null
        );

        return view('historico', [
            'vendas' => false,
            'registros' => $registros,
            'chartLabels' => [],
            'chartValues' => [],
        ]);
    }

    public function sales(Request $request): View
    {
        $usuario = $this->usuarioAutenticado($request);
        $periodo = $this->validarPeriodo($request);
        $registros = $this->historico->vendas(
            $usuario,
            $periodo['inicio'] ?? null,
            $periodo['fim'] ?? null
        );
        $grafico = $this->historico->graficoVendas($usuario);

        return view('historico', [
            'vendas' => true,
            'registros' => $registros,
            'chartLabels' => $grafico['labels'],
            'chartValues' => $grafico['values'],
        ]);
    }

    private function validarPeriodo(Request $request): array
    {
        return $request->validate([
            'inicio' => ['nullable', 'date'],
            'fim' => ['nullable', 'date', 'after_or_equal:inicio'],
        ]);
    }

    private function usuarioAutenticado(Request $request): Usuario
    {
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        return $usuario;
    }
}
