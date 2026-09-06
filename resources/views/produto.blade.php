<x-app-layout>
    <main class="front-page">
        <div class="front-shell">

            <!-- Navegação -->
            <div class="front-breadcrumb">
                <a href="{{ route('landing') }}">
                    Início
                </a>

                <span>/</span>

                <a href="{{ route('produtos.index') }}">
                    Produtos
                </a>

                <span>/</span>

                <span>{{ $produto->nome }}</span>
            </div>

            <!-- Detalhes do produto -->
            <section class="front-produto-detail">

                <!-- Imagens do produto -->
                <div class="front-produto-gallery">
                    <div class="front-produto-main-image">
                        <img src="{{ asset($produto->foto) }}" alt="{{ $produto->nome }}">
                    </div>

                    <div class="front-produto-thumbs">
                        @forelse ($produto->fotos->take(3) as $imagem)
                            <img src="{{ asset($imagem->foto) }}" alt="Imagem complementar de {{ $produto->nome }}">
                        @empty
                            <img src="{{ asset($produto->foto) }}" alt="{{ $produto->nome }}">
                        @endforelse
                    </div>
                </div>

                <!-- Informações do produto -->
                <article class="front-produto-summary">
                    <span class="front-eyebrow">
                        {{ $produto->categoria?->nome ?? 'Sem categoria' }}
                    </span>

                    <h1>{{ $produto->nome }}</h1>

                    <div class="front-rating">
                        <span>★</span>
                        4,7
                        <small>(218 avaliações)</small>
                    </div>

                    <p class="front-detail-price">
                        R$
                        {{ number_format((float) $produto->preco, 2, ',', '.') }}
                    </p>

                    <p class="front-stock">
                        {{ $produto->quantidade }}
                        unidades em estoque
                    </p>

                    <dl class="front-produto-facts">
                        <div>
                            <dt>Vendedor</dt>

                            <dd>
                                {{ $produto->usuario?->nome ?? 'Não informado' }}
                            </dd>
                        </div>

                        <div>
                            <dt>Telefone</dt>

                            <dd>
                                {{ $produto->usuario?->telefone ?? 'Não informado' }}
                            </dd>
                        </div>

                        <div>
                            <dt>Categoria</dt>

                            <dd>
                                {{ $produto->categoria?->nome ?? 'Sem categoria' }}
                            </dd>
                        </div>
                    </dl>

                    @if (!auth()->check() || auth()->user()->tipo !== 'administrador')
                        <div class="front-detail-actions">
                            <a href="{{ route('compra.show', $produto->id) }}"
                                class="front-button front-button-primary">
                                Comprar agora
                            </a>

                            @auth
                                <form action="{{ route('cart.store', $produto) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="front-button front-button-ghost">
                                        Adicionar ao carrinho
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="front-button front-button-ghost">
                                    Entrar para comprar
                                </a>
                            @endauth
                        </div>
                    @endif
                </article>
            </section>

            <!-- Descrição -->
            <section class="front-description-card">
                <div>
                    <span class="front-section-number">
                        1
                    </span>

                    <h2>Descrição</h2>

                    <p>
                        {{ $produto->descricao ?? 'Este produto não possui descrição.' }}
                    </p>
                </div>

                <div>
                    <span class="front-section-number">
                        2
                    </span>

                    <h2>Informações técnicas</h2>

                    <p>
                        Produto eletrônico com disponibilidade
                        informada pelo vendedor.
                    </p>
                </div>
            </section>

            <!-- Produtos relacionados -->
            @if ($relacionados->isNotEmpty())
                <section class="front-related-section">
                    <div class="front-section-heading">
                        <h2>Produtos relacionados</h2>

                        <a
                            href="{{ route('produtos.index', [
                                'categoria' => $produto->categoria?->nome,
                            ]) }}">
                            Ver mais
                        </a>
                    </div>

                    <div class="front-mini-produto-grid">
                        @foreach ($relacionados as $relacionado)
                            <a href="{{ route('produtos.show', $relacionado->id) }}" class="front-mini-produto">
                                <img src="{{ asset($relacionado->foto) }}" alt="{{ $relacionado->nome }}">

                                <span>
                                    {{ $relacionado->nome }}
                                </span>

                                <strong>
                                    R$
                                    {{ number_format((float) $relacionado->preco, 2, ',', '.') }}
                                </strong>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>

    <x-footer />
</x-app-layout>
