<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\ItemCarrinho;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarrinhoController extends Controller
{
    public function index(Request $request): View
    {
        $carrinho = $this->carrinhoDoUsuario($request);
        $itens = $carrinho->itens()
            ->with(['produto.categoria', 'produto.usuario'])
            ->get();

        $quantidadeProdutos = $itens->sum('quantidade');
        $total = $itens->sum(
            fn (ItemCarrinho $item): float => (float) $item->produto->preco * $item->quantidade
        );

        return view('cart', compact('itens', 'quantidadeProdutos', 'total'));
    }

    public function store(Request $request, Produto $produto): RedirectResponse
    {
        $usuario = $this->usuarioAutenticado($request);

        if ($usuario->tipo === 'administrador') {
            return back()->withErrors(['carrinho' => 'Administradores não podem realizar compras.']);
        }

        if ($produto->quantidade < 1) {
            return back()->withErrors(['carrinho' => 'Este produto está sem estoque.']);
        }

        $carrinho = $this->carrinhoDoUsuario($request);
        $item = $carrinho->itens()->firstOrNew(['ProdutoId' => $produto->getKey()]);
        $novaQuantidade = ($item->exists ? $item->quantidade : 0) + 1;

        if ($novaQuantidade > $produto->quantidade) {
            return back()->withErrors(['carrinho' => 'Não há mais unidades disponíveis desse produto.']);
        }

        $item->quantidade = $novaQuantidade;
        $item->save();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produto adicionado ao carrinho.');
    }

    public function update(Request $request, ItemCarrinho $itemCarrinho): RedirectResponse
    {
        $this->garantirQueItemPertenceAoUsuario($request, $itemCarrinho);

        $dados = $request->validate([
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        $itemCarrinho->loadMissing('produto');

        if ($dados['quantidade'] > $itemCarrinho->produto->quantidade) {
            return back()->withErrors([
                'carrinho' => 'A quantidade solicitada é maior que o estoque disponível.',
            ]);
        }

        $itemCarrinho->update(['quantidade' => $dados['quantidade']]);

        return redirect()->route('cart.index')->with('success', 'Quantidade atualizada.');
    }

    public function destroy(Request $request, ItemCarrinho $itemCarrinho): RedirectResponse
    {
        $this->garantirQueItemPertenceAoUsuario($request, $itemCarrinho);
        $itemCarrinho->delete();

        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $this->carrinhoDoUsuario($request)->itens()->delete();

        return redirect()->route('cart.index')->with('success', 'Carrinho esvaziado.');
    }

    private function carrinhoDoUsuario(Request $request): Carrinho
    {
        $usuario = $this->usuarioAutenticado($request);

        return Carrinho::firstOrCreate(['UsuarioId' => $usuario->getKey()]);
    }

    private function usuarioAutenticado(Request $request): Usuario
    {
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        return $usuario;
    }

    private function garantirQueItemPertenceAoUsuario(
        Request $request,
        ItemCarrinho $itemCarrinho
    ): void {
        $usuario = $this->usuarioAutenticado($request);
        $itemCarrinho->loadMissing('carrinho');

        abort_unless(
            (int) $itemCarrinho->carrinho->UsuarioId === (int) $usuario->getKey(),
            403
        );
    }
}
