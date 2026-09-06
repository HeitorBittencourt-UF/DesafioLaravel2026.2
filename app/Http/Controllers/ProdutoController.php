<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with([
            'produtos' => function ($query) {
                $query->where('quantidade', '>', 0)->latest();
            },
        ])->get();

        return view('landing', compact('categorias'));
    }

    public function catalogo(Request $request)
    {
        $busca = trim((string) $request->get('busca', ''));
        $categoriaId = $request->get('categoria');

        $query = Produto::with(['categoria', 'usuario'])->where('quantidade', '>', 0);

        if ($busca) {
            $query->where('nome', 'like', '%' . $busca . '%');
        }

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        $produtosFiltrados = $query->latest()->paginate(12)->withQueryString();
        $categorias = Categoria::orderBy('nome')->get();

        return view('catalogo', compact('produtosFiltrados', 'categorias', 'busca', 'categoriaId'));
    }

    public function show(Produto $produto)
    {
        $produto->load([
            'categoria',
            'usuario',
            'fotos',
        ]);

        $relacionados = Produto::with(['categoria', 'usuario'])
            ->where('categoria_id', $produto->categoria_id)
            ->where('id', '!=', $produto->id)
            ->where('quantidade', '>', 0)
            ->take(3)
            ->get();

        return view('produto', compact('produto', 'relacionados'));
    }

    public function gerenciar(Request $request)
    {
        $usuario = $request->user();

        if (! $usuario instanceof Usuario) {
            abort(401);
        }

        $query = Produto::with('categoria')->latest();

        if ($usuario->tipo !== 'administrador') {
            $query->where('UsuarioId', $usuario->getKey());
        }

        $produtos = $query->get();

        $produtosAtivos = $produtos->where('quantidade', '>', 0)->count();
        $estoqueTotal = $produtos->sum('quantidade');
        $categoriasTotal = $produtos->pluck('categoria_id')->filter()->unique()->count();

        return view('produtos-management', compact('produtos', 'produtosAtivos', 'estoqueTotal', 'categoriasTotal'));
    }

    public function criar()
    {
        $categorias = Categoria::query()->orderBy('nome')->get();

        return view('produto-form', [
            'editando' => false,
            'produto' => null,
            'categorias' => $categorias,
        ]);
    }

    public function editar(Request $request, Produto $produto)
    {
        $usuario = $request->user();

        if (! $usuario instanceof Usuario) {
            abort(401);
        }

        $produtoPertenceAoUsuario = (int) $produto->UsuarioId === (int) $usuario->getKey();

        if ($usuario->tipo !== 'administrador' && ! $produtoPertenceAoUsuario) {
            abort(403);
        }

        $categorias = Categoria::query()->orderBy('nome')->get();

        return view('produto-form', [
            'editando' => true,
            'produto' => $produto,
            'categorias' => $categorias,
        ]);
    }
}