<x-app-layout>
    <main class="front-page front-purchase-page">
        <div class="front-shell">
            <div class="front-breadcrumb">
                <a href="{{ route('produtos.show', $produto) }}">Produto</a>
                <span>/</span>
                <span>Comprar</span>
            </div>

            @if ($errors->any())
                <div class="front-alert-error">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <section class="front-purchase-grid">
                <div class="front-purchase-visual">
                    <img src="{{ asset($produto->foto ?? 'assets/Logo-1.png') }}" alt="{{ $produto->nome }}">
                </div>

                <div class="front-purchase-summary">
                    <div class="front-checkout-steps front-checkout-steps-compact" aria-label="Etapas da compra">
                        <span class="active"><b>1</b><small>Produto</small></span>
                        <i></i>
                        <span><b>2</b><small>Carrinho</small></span>
                        <i></i>
                        <span><b>3</b><small>Pagamento</small></span>
                    </div>

                    <span class="front-eyebrow">{{ $produto->categoria?->nome ?? 'Sem categoria' }}</span>
                    <h1>{{ $produto->nome }}</h1>
                    <div class="front-rating"><span>★</span> 4,7 <small>(218 avaliações)</small></div>
                    <p class="front-detail-price">
                        R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}
                    </p>
                    <p class="front-installments">
                        ou 10x de R$ {{ number_format((float) $produto->preco / 10, 2, ',', '.') }} sem juros
                    </p>

                    <form action="{{ route('compra.show', $produto) }}" method="GET" class="front-cep-form">
                        <label for="cep-compra">Calcular entrega</label>
                        <div>
                            <input id="cep-compra" name="cep" inputmode="numeric" value="{{ request('cep') }}"
                                placeholder="Digite o CEP">
                            <button type="submit">Consultar</button>
                        </div>
                    </form>

                    @if ($cepConsultado)
                        <p class="front-shipping-result">Frete grátis para o CEP {{ $cepConsultado }}.</p>
                    @endif

                    @auth
                        @if (auth()->user()->tipo !== 'administrador')
                            <form action="{{ route('cart.store', $produto) }}" method="POST">
                                @csrf
                                <button type="submit" class="front-button front-button-primary front-purchase-button">
                                    Adicionar ao carrinho
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="front-button front-button-primary front-purchase-button">
                            Entrar para comprar
                        </a>
                    @endauth

                    <a href="{{ route('produtos.show', $produto) }}" class="front-back-action">
                        Voltar aos detalhes
                    </a>
                </div>
            </section>
        </div>
    </main>

    <x-footer />
</x-app-layout>
