<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;

class ProdutoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with([
            'produtos' => function ($query) {
                $query
                    ->where('quantidade', '>', 0)
                    ->latest();
            }
        ])->get();

        return view('landing', compact('categorias'));
    }
    public function show(Produto $produto)
{
    $produto->load([
        'categoria',
        'usuario',
        'fotos',
    ]);

    $relacionados = Produto::with([
        'categoria',
        'usuario',
    ])
        ->where(
            'categoria_id',
            $produto->categoria_id
        )
        ->where(
            'id',
            '!=',
            $produto->id
        )
        ->where(
            'quantidade',
            '>',
            0
        )
        ->take(3)
        ->get();

    return view(
        'produto',
        compact(
            'produto',
            'relacionados'
        )
    );
}
}
