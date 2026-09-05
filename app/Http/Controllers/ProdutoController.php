<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('categoria')
            ->where('quantidade', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        $categorias = Categoria::all();

        return view('landing', compact('produtos', 'categorias'));
    }
}