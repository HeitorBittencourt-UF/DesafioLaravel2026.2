<x-app-layout>
    <main class="front-page front-cart-page">
        <div class="front-shell">
            <header class="front-page-heading front-cart-heading">
                <div>
                    <span class="front-eyebrow">Pedido</span>
                    <h1>Seu carrinho</h1>
                    <p>Revise os produtos antes de continuar.</p>
                </div>

                <div class="front-checkout-steps" aria-label="Etapas da compra">
                    <span class="active"><b>1</b><small>Carrinho</small></span>
                    <i></i>
                    <span><b>2</b><small>Endereço</small></span>
                    <i></i>
                    <span><b>3</b><small>Pagamento</small></span>
                </div>
            </header>

            @if (session('success'))
                <div class="front-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="front-alert-error">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <div class="front-cart-layout">
                <section class="front-cart-card">
                    <div class="front-cart-toolbar">
                        <strong>{{ $quantidadeProdutos }}
                            {{ $quantidadeProdutos === 1 ? 'produto' : 'produtos' }}</strong>

                        @if ($itens->isNotEmpty())
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Limpar carrinho</button>
                            </form>
                        @endif
                    </div>

                    @forelse ($itens as $item)
                        @php($produto = $item->produto)

                        <article class="front-cart-item" data-price="{{ $produto->preco }}">
                            <input type="checkbox" checked disabled aria-label="Produto {{ $produto->nome }}">

                            <img src="{{ asset($produto->foto ?? 'assets/Logo-1.png') }}" alt="{{ $produto->nome }}">

                            <div class="front-cart-item-info">
                                <h2>{{ $produto->nome }}</h2>
                                <span>{{ $produto->categoria?->nome ?? 'Sem categoria' }}</span>
                                <small>Vendido por {{ $produto->usuario?->nome ?? 'Não informado' }}</small>
                            </div>

                            <strong>
                                R$ {{ number_format((float) $produto->preco * $item->quantidade, 2, ',', '.') }}
                            </strong>

                            <form action="{{ route('cart.update', $item) }}" method="POST"
                                class="front-quantity-control">
                                @csrf
                                @method('PATCH')

                                <button type="submit" name="quantidade" value="{{ max(1, $item->quantidade - 1) }}"
                                    aria-label="Diminuir quantidade" @disabled($item->quantidade <= 1)>−</button>

                                <span>{{ $item->quantidade }}</span>

                                <button type="submit" name="quantidade" value="{{ $item->quantidade + 1 }}"
                                    aria-label="Aumentar quantidade" @disabled($item->quantidade >= $produto->quantidade)>+</button>
                            </form>

                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="front-remove-item">Remover</button>
                            </form>
                        </article>
                    @empty
                        <div class="front-empty-state front-cart-empty">
                            <h2>Seu carrinho está vazio</h2>
                            <p>Escolha um produto para iniciar seu pedido.</p>
                            <a href="{{ route('produtos.index') }}" class="front-button front-button-primary">
                                Escolher produtos
                            </a>
                        </div>
                    @endforelse
                </section>

                <aside class="front-order-summary">
                    <h2>Resumo da compra</h2>
                    <div>
                        <span>Produtos</span>
                        <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </div>
                    <div><span>Frete</span><strong class="front-free-shipping">Grátis</strong></div>
                    <div class="front-order-total">
                        <span>Total</span>
                        <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </div>

                    @if ($itens->isNotEmpty())
                        <a href="{{ route('checkout.address') }}" class="front-button front-button-primary">
                            Fechar carrinho
                        </a>
                    @endif

                    <small>Compra segura e protegida</small>
                </aside>
            </div>

            <a href="{{ route('produtos.index') }}" class="front-back-action">← Continuar comprando</a>
        </div>
    </main>

    <x-footer />
</x-app-layout>
