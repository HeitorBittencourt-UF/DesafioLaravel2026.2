<?php

namespace App\Http\Controllers;

use App\Exports\VendasExport;
use App\Models\Usuario;
use App\Services\HistoricoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(
        private readonly HistoricoService $historico
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | PDF - compras ou vendas
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        string $tipo
    ) {
        $periodo = $request->validate([
            'inicio' => [
                'nullable',
                'date',
            ],

            'fim' => [
                'nullable',
                'date',
                'after_or_equal:inicio',
            ],
        ]);

        $usuario = $request->user();

        abort_unless(
            $usuario instanceof Usuario,
            401
        );

        $vendas = $tipo === 'vendas';

        $registros = $vendas
            ? $this->historico->vendas(
                $usuario,
                $periodo['inicio'] ?? null,
                $periodo['fim'] ?? null
            )
            : $this->historico->compras(
                $usuario,
                $periodo['inicio'] ?? null,
                $periodo['fim'] ?? null
            );

        $pdf = Pdf::loadView(
            'report',
            [
                'vendas' => $vendas,
                'registros' => $registros,

                'inicio' =>
                    $periodo['inicio'] ?? null,

                'fim' =>
                    $periodo['fim'] ?? null,

                'total' =>
                    $registros->sum('value'),
            ]
        )->setPaper(
            'a4',
            'landscape'
        );

        $nomeArquivo =
            'historico-'
            . $tipo
            . '-'
            . now()->format('Y-m-d')
            . '.pdf';

        return $pdf->download(
            $nomeArquivo
        );
    }


    /*
    |--------------------------------------------------------------------------
    | XLSX - todas as vendas
    |--------------------------------------------------------------------------
    |
    | Somente administrador.
    |
    */

    public function salesXlsx(Request $request)
    {
        $usuario = $request->user();

        abort_unless(
            $usuario instanceof Usuario,
            401
        );

        abort_unless(
            $usuario->tipo === 'administrador',
            403,
            'Apenas administradores podem exportar vendas em XLSX.'
        );

        $periodo = $request->validate([
            'inicio' => [
                'nullable',
                'date',
            ],

            'fim' => [
                'nullable',
                'date',
                'after_or_equal:inicio',
            ],
        ]);

        $registros =
            $this->historico->vendas(
                $usuario,
                $periodo['inicio'] ?? null,
                $periodo['fim'] ?? null
            );

        return Excel::download(
            new VendasExport($registros),

            'historico-vendas-'
            . now()->format('Y-m-d')
            . '.xlsx'
        );
    }
}