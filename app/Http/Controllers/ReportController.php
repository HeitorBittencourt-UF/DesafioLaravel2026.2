<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly HistoricoService $historico)
    {
    }

    public function show(Request $request, string $tipo): View
    {
        $periodo = $request->validate([
            'inicio' => ['nullable', 'date'],
            'fim' => ['nullable', 'date', 'after_or_equal:inicio'],
        ]);
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        $vendas = $tipo === 'vendas';
        $registros = $vendas
            ? $this->historico->vendas($usuario, $periodo['inicio'] ?? null, $periodo['fim'] ?? null)
            : $this->historico->compras($usuario, $periodo['inicio'] ?? null, $periodo['fim'] ?? null);

        return view('report', [
            'vendas' => $vendas,
            'registros' => $registros,
            'inicio' => $periodo['inicio'] ?? null,
            'fim' => $periodo['fim'] ?? null,
            'total' => $registros->sum('value'),
        ]);
    }
}
