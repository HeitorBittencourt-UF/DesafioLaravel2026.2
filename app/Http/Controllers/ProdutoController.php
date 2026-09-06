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
}
