<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\Endereco;
use App\Models\ItemCarrinho;
use App\Models\Produto;
use App\Models\Usuario;
use App\Models\Venda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function address(Request $request): View|RedirectResponse
    {
        $carrinho = $this->carrinhoComItens($request);

        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'carrinho' => 'Adicione pelo menos um produto antes de continuar.',
            ]);
        }

        $etapa = 'endereco';
        $total = $this->total($carrinho);
        $quantidadeProdutos = $carrinho->itens->sum('quantidade');
        $endereco = $this->usuarioAutenticado($request)
            ->enderecos()
            ->latest('Enderecos.created_at')
            ->first();

        return view('checkout', compact(
            'etapa',
            'total',
            'quantidadeProdutos',
            'endereco'
        ));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $carrinho = $this->carrinhoComItens($request);

        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'carrinho' => 'Seu carrinho está vazio.',
            ]);
        }

        $request->merge([
            'cep' => preg_replace('/\D/', '', (string) $request->input('cep')),
            'estado' => strtoupper((string) $request->input('estado')),
        ]);

        $dados = $request->validate([
            'cep' => ['required', 'digits:8'],
            'logradouro' => ['required', 'string', 'max:150'],
            'numero' => ['required', 'string', 'max:10'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'size:2'],
            'complemento' => ['nullable', 'string', 'max:100'],
        ]);

        $endereco = Endereco::firstOrCreate($dados);
        $this->usuarioAutenticado($request)
            ->enderecos()
            ->syncWithoutDetaching([$endereco->getKey()]);

        $request->session()->put('checkout.endereco_id', $endereco->getKey());

        return redirect()->route('checkout.payment');
    }

    public function payment(Request $request): View|RedirectResponse
    {
        $carrinho = $this->carrinhoComItens($request);

        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'carrinho' => 'Seu carrinho está vazio.',
            ]);
        }

        $endereco = $this->enderecoSelecionado($request);

        if (! $endereco) {
            return redirect()->route('checkout.address')->withErrors([
                'endereco' => 'Confirme o endereço de entrega antes do pagamento.',
            ]);
        }

        $etapa = 'pagamento';
        $total = $this->total($carrinho);
        $quantidadeProdutos = $carrinho->itens->sum('quantidade');

        return view('checkout', compact(
            'etapa',
            'total',
            'quantidadeProdutos',
            'endereco'
        ));
    }

    public function finish(Request $request): RedirectResponse
    {
        $carrinho = $this->carrinhoComItens($request);

        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'carrinho' => 'Seu carrinho está vazio.',
            ]);
        }

        if (! $this->enderecoSelecionado($request)) {
            return redirect()->route('checkout.address')->withErrors([
                'endereco' => 'Confirme o endereço de entrega antes do pagamento.',
            ]);
        }

        $dados = $request->validate([
            'forma' => ['required', 'in:cartao,pix'],
            'numero_cartao' => ['nullable', 'required_if:forma,cartao', 'regex:/^[0-9 ]{13,19}$/'],
            'nome_cartao' => ['nullable', 'required_if:forma,cartao', 'string', 'max:150'],
            'validade' => ['nullable', 'required_if:forma,cartao', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['nullable', 'required_if:forma,cartao', 'digits_between:3,4'],
        ]);

        $venda = DB::transaction(function () use ($request, $carrinho, $dados): Venda {
            $itens = ItemCarrinho::query()
                ->where('CarrinhoId', $carrinho->getKey())
                ->lockForUpdate()
                ->get();

            if ($itens->isEmpty()) {
                throw ValidationException::withMessages([
                    'carrinho' => 'Seu carrinho está vazio.',
                ]);
            }

            $produtos = collect();
            $total = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()->lockForUpdate()->findOrFail($item->ProdutoId);

                if ($item->quantidade > $produto->quantidade) {
                    throw ValidationException::withMessages([
                        'estoque' => "O produto {$produto->nome} não possui estoque suficiente.",
                    ]);
                }

                $produtos->put($produto->getKey(), $produto);
                $total += (float) $produto->preco * $item->quantidade;
            }

            $venda = Venda::create([
                'CompradorId' => $this->usuarioAutenticado($request)->getKey(),
                'ValorTotal' => $total,
                'StatusPagamento' => 'pendente',
                'LocalPagamento' => 'mercadopago',
                'codigo_transacao' => $dados['forma'].'-'.Str::uuid(),
            ]);

            foreach ($itens as $item) {
                /** @var Produto $produto */
                $produto = $produtos->get($item->ProdutoId);
                $subtotal = (float) $produto->preco * $item->quantidade;

                $venda->itens()->create([
                    'ProdutoId' => $produto->getKey(),
                    'VendedorId' => $produto->UsuarioId,
                    'quantidade' => $item->quantidade,
                    'ValorUnitario' => $produto->preco,
                    'subtotal' => $subtotal,
                ]);

                $produto->decrement('quantidade', $item->quantidade);
            }

            ItemCarrinho::where('CarrinhoId', $carrinho->getKey())->delete();

            return $venda;
        });

        $request->session()->forget('checkout.endereco_id');
        $request->session()->put('checkout.venda_id', $venda->getKey());

        return redirect()->route('checkout.success');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $etapa = 'concluido';
        $vendaId = $request->session()->get('checkout.venda_id');
        $venda = $vendaId
            ? Venda::where('CompradorId', $this->usuarioAutenticado($request)->getKey())
                ->find($vendaId)
            : null;

        if (! $venda) {
            return redirect()->route('cart.index');
        }

        return view('checkout', compact('etapa', 'venda'));
    }

    private function carrinhoComItens(Request $request): Carrinho
    {
        $carrinho = Carrinho::firstOrCreate([
            'UsuarioId' => $this->usuarioAutenticado($request)->getKey(),
        ]);

        return $carrinho->load('itens.produto');
    }

    private function total(Carrinho $carrinho): float
    {
        return $carrinho->itens->sum(
            fn (ItemCarrinho $item): float => (float) $item->produto->preco * $item->quantidade
        );
    }

    private function enderecoSelecionado(Request $request): ?Endereco
    {
        $enderecoId = $request->session()->get('checkout.endereco_id');

        if (! $enderecoId) {
            return null;
        }

        return $this->usuarioAutenticado($request)->enderecos()->find($enderecoId);
    }

    private function usuarioAutenticado(Request $request): Usuario
    {
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        return $usuario;
    }
}
