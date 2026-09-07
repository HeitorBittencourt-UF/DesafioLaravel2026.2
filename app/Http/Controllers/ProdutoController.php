<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $usuarioId = $request->user()->getKey();

        $categorias = Categoria::with([
            'produtos' => function ($query) use ($usuarioId) {
                $query
                    ->where('quantidade', '>', 0)
                    ->where('UsuarioId', '!=', $usuarioId)
                    ->latest()
                    ->limit(4);
            },
        ])
            ->orderBy('nome')
            ->get();

        return view('landing', compact('categorias'));
    }

    public function catalogo(Request $request)
    {
        $usuarioId = $request->user()->getKey();
        $busca = trim((string) $request->get('busca', ''));
        $categoriaId = $request->get('categoria');

        $query = Produto::with(['categoria', 'usuario'])
            ->where('quantidade', '>', 0)
            ->where('UsuarioId', '!=', $usuarioId);

        if ($busca !== '') {
            $query->where('nome', 'like', '%' . $busca . '%');
        }

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        $produtosFiltrados = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::orderBy('nome')->get();

        return view('catalogo', compact(
            'produtosFiltrados',
            'categorias',
            'busca',
            'categoriaId'
        ));
    }

    public function show(Request $request, Produto $produto)
    {
        $usuario = $request->user();

        if (! $usuario instanceof Usuario) {
            abort(401);
        }

        $produto->load([
            'categoria',
            'usuario',
            'fotos',
        ]);

        $usuarioEhAdministrador = $usuario->tipo === 'administrador';
        $produtoPertenceAoUsuario =
            (int) $produto->UsuarioId === (int) $usuario->getKey();

        $podeComprar =
            ! $usuarioEhAdministrador
            && ! $produtoPertenceAoUsuario
            && $produto->quantidade > 0;

        $relacionados = Produto::with(['categoria', 'usuario'])
            ->where('categoria_id', $produto->categoria_id)
            ->where('id', '!=', $produto->id)
            ->where('UsuarioId', '!=', $usuario->getKey())
            ->where('quantidade', '>', 0)
            ->latest()
            ->take(3)
            ->get();

        return view('produto', compact(
            'produto',
            'relacionados',
            'usuarioEhAdministrador',
            'produtoPertenceAoUsuario',
            'podeComprar'
        ));
    }

    public function gerenciar(Request $request): View
{
    $usuario = $this->usuarioAutenticado($request);

    $busca = trim(
        (string) $request->query('busca', '')
    );

    /*
    |--------------------------------------------------------------------------
    | Produtos que o usuário pode visualizar
    |--------------------------------------------------------------------------
    */

    $query = Produto::query();

    if ($usuario->tipo !== 'administrador') {
        $query->where(
            'UsuarioId',
            $usuario->getKey()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Estatísticas
    |--------------------------------------------------------------------------
    */

    $produtosParaEstatisticas = (clone $query)->get([
        'quantidade',
        'categoria_id',
    ]);

    $produtosAtivos = $produtosParaEstatisticas
        ->where('quantidade', '>', 0)
        ->count();

    $estoqueTotal = $produtosParaEstatisticas
        ->sum('quantidade');

    $categoriasTotal = $produtosParaEstatisticas
        ->pluck('categoria_id')
        ->filter()
        ->unique()
        ->count();

    $totalProdutos = $produtosParaEstatisticas
        ->count();


    /*
    |--------------------------------------------------------------------------
    | RF013 - Produtos cadastrados nos últimos 12 meses
    |--------------------------------------------------------------------------
    |
    | Apenas administradores recebem os dados do gráfico.
    |
    */

    $graficoProdutos = null;

    if ($usuario->tipo === 'administrador') {

        $inicioDoPeriodo = now()
            ->startOfMonth()
            ->subMonths(11);

        $fimDoPeriodo = now()
            ->endOfMonth();

        $produtosPorMes = Produto::query()
            ->whereBetween(
                'created_at',
                [
                    $inicioDoPeriodo,
                    $fimDoPeriodo,
                ]
            )
            ->get(['created_at'])
            ->countBy(
                fn (Produto $produto) =>
                    $produto->created_at->format('Y-m')
            );

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
        ];

        $labels = [];
        $valores = [];

        for ($indice = 11; $indice >= 0; $indice--) {

            $data = now()
                ->startOfMonth()
                ->subMonths($indice);

            $chaveDoMes = $data->format('Y-m');

            $numeroDoMes = (int) $data->format('n');

            $labels[] =
                $nomesDosMeses[$numeroDoMes];

            $valores[] =
                (int) $produtosPorMes->get(
                    $chaveDoMes,
                    0
                );
        }

        $graficoProdutos = [
            'labels' => $labels,
            'valores' => $valores,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Pesquisa
    |--------------------------------------------------------------------------
    */

    if ($busca !== '') {
        $query->where(
            'nome',
            'like',
            '%' . $busca . '%'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Produtos da tabela
    |--------------------------------------------------------------------------
    */

    $produtos = $query
        ->with('categoria')
        ->latest()
        ->get();


    return view(
        'produtos-management',
        compact(
            'produtos',
            'produtosAtivos',
            'estoqueTotal',
            'categoriasTotal',
            'totalProdutos',
            'busca',
            'graficoProdutos'
        )
    );
}

    public function criar(Request $request): View
    {
        $usuario = $this->usuarioAutenticado($request);
        $this->garantirQuePodeCriar($usuario);

        $categorias = Categoria::query()
            ->orderBy('nome')
            ->get();

        return view('produto-form', [
            'editando' => false,
            'produto' => null,
            'categorias' => $categorias,
        ]);
    }

    public function store(ProdutoRequest $request): RedirectResponse
    {
        $usuario = $this->usuarioAutenticado($request);
        $this->garantirQuePodeCriar($usuario);

        $dados = $request->validated();
        unset($dados['foto']);

        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('produtos', 'public');

            if (! $caminhoFoto) {
                return back()
                    ->withInput()
                    ->withErrors(['foto' => 'Não foi possível salvar a foto do produto.']);
            }

            $dados['foto'] = 'storage/' . $caminhoFoto;
        }

        $dados['UsuarioId'] = $usuario->getKey();

        try {
            Produto::create($dados);
        } catch (Throwable $erro) {
            if ($caminhoFoto) {
                Storage::disk('public')->delete($caminhoFoto);
            }

            throw $erro;
        }

        return redirect()
            ->route('produtos.manage')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function editar(Request $request, Produto $produto): View
    {
        $usuario = $this->usuarioAutenticado($request);
        $this->garantirQuePodeGerenciar($usuario, $produto);

        $categorias = Categoria::query()
            ->orderBy('nome')
            ->get();

        return view('produto-form', [
            'editando' => true,
            'produto' => $produto,
            'categorias' => $categorias,
        ]);
    }

    public function update(
        ProdutoRequest $request,
        Produto $produto
    ): RedirectResponse {
        $usuario = $this->usuarioAutenticado($request);
        $this->garantirQuePodeGerenciar($usuario, $produto);

        $dados = $request->validated();
        unset($dados['foto']);

        $fotoAntiga = $produto->foto;
        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('produtos', 'public');

            if (! $caminhoFoto) {
                return back()
                    ->withInput()
                    ->withErrors(['foto' => 'Não foi possível salvar a nova foto do produto.']);
            }

            $dados['foto'] = 'storage/' . $caminhoFoto;
        }

        try {
            $produto->update($dados);
        } catch (Throwable $erro) {
            if ($caminhoFoto) {
                Storage::disk('public')->delete($caminhoFoto);
            }

            throw $erro;
        }

        if ($caminhoFoto) {
            $this->excluirFotoLocal($fotoAntiga);
        }

        return redirect()
            ->route('produtos.manage')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(
        Request $request,
        Produto $produto
    ): RedirectResponse {
        $usuario = $this->usuarioAutenticado($request);
        $this->garantirQuePodeGerenciar($usuario, $produto);

        if ($produto->itensVendas()->exists()) {
            return redirect()
                ->route('produtos.manage')
                ->withErrors([
                    'produto' => 'Este produto possui vendas registradas e não pode ser excluído.',
                ]);
        }

        $fotos = collect([$produto->foto])
            ->merge($produto->fotos()->pluck('foto'))
            ->filter()
            ->values();

        DB::transaction(function () use ($produto) {
            $produto->itensCarrinho()->delete();
            $produto->delete();
        });

        $fotos->each(fn (string $foto) => $this->excluirFotoLocal($foto));

        return redirect()
            ->route('produtos.manage')
            ->with('success', 'Produto excluído com sucesso.');
    }

    private function usuarioAutenticado(Request $request): Usuario
    {
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        return $usuario;
    }

    private function garantirQuePodeCriar(Usuario $usuario): void
    {
        abort_if(
            $usuario->tipo === 'administrador',
            403,
            'Administradores não podem cadastrar produtos.'
        );
    }

    private function garantirQuePodeGerenciar(
        Usuario $usuario,
        Produto $produto
    ): void {
        $produtoPertenceAoUsuario =
            (int) $produto->UsuarioId === (int) $usuario->getKey();

        abort_unless(
            $usuario->tipo === 'administrador' || $produtoPertenceAoUsuario,
            403
        );
    }

    private function excluirFotoLocal(?string $foto): void
    {
        $caminho = ltrim(trim((string) $foto), '/');

        if (! str_starts_with($caminho, 'storage/')) {
            return;
        }

        $caminho = substr($caminho, strlen('storage/'));

        if ($caminho !== '') {
            Storage::disk('public')->delete($caminho);
        }
    }
}
