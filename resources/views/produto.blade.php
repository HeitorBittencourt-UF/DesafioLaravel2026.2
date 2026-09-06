<x-app-layout>
    <main class="front-page">
        <div class="front-shell">
            @php
                $imagemPrincipal = $produto->foto
                    ? asset($produto->foto)
                    : asset('assets/Logo-1.png');
            @endphp

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
                        <img src="{{ $imagemPrincipal }}" alt="{{ $produto->nome }}">
                    </div>

                    <div class="front-produto-thumbs">
                        @forelse ($produto->fotos->take(3) as $imagem)
                            <img
                                src="{{ $imagem->foto ? asset($imagem->foto) : $imagemPrincipal }}"
                                alt="Imagem complementar de {{ $produto->nome }}"
                            >
                        @empty
                            <img src="{{ $imagemPrincipal }}" alt="{{ $produto->nome }}">
                        @endforelse
                    </div>
                </div>

                <!-- Informações do produto -->
                <article class="front-produto-summary">
                    <span class="front-eyebrow">
                        {{ $produto->categoria?->nome ?? 'Sem categoria' }}
                    </span>

                    <h1>{{ $produto->nome }}</h1>

                    <p class="front-detail-price">
                        R$
                        {{ number_format((float) $produto->preco, 2, ',', '.') }}
                    </p>

                    <p class="front-stock">
                        @if ($produto->quantidade > 0)
                            {{ $produto->quantidade }}
                            {{ $produto->quantidade === 1 ? 'unidade disponível' : 'unidades disponíveis' }}
                        @else
                            Produto sem estoque
                        @endif
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
                                {{ formatarTelefone($produto->usuario?->telefone) ?: 'Não informado' }}
                            </dd>
                        </div>

                        <div>
                            <dt>Categoria</dt>

                            <dd>
                                {{ $produto->categoria?->nome ?? 'Sem categoria' }}
                            </dd>
                        </div>
                    </dl>

                    @if ($podeComprar)
                        <div class="front-detail-actions">
                            <a
                                href="{{ route('compra.show', $produto->id) }}"
                                class="front-button front-button-primary"
                            >
                                Comprar agora
                            </a>

                            <form action="{{ route('cart.store', $produto) }}" method="POST">
                                @csrf

                                <button type="submit" class="front-button front-button-ghost">
                                    Adicionar ao carrinho
                                </button>
                            </form>
                        </div>
                    @elseif ($usuarioEhAdministrador)
                        <p class="mt-7 rounded-lg border border-slate-300 bg-slate-50 p-3 text-sm font-semibold text-slate-700">
                            Administradores podem visualizar produtos, mas não podem realizar compras.
                        </p>
                    @elseif ($produtoPertenceAoUsuario)
                        <p class="mt-7 rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm font-semibold text-amber-800">
                            Este produto foi anunciado por você e não pode ser comprado pela sua conta.
                        </p>
                    @else
                        <p class="mt-7 rounded-lg border border-red-300 bg-red-50 p-3 text-sm font-semibold text-red-700">
                            Este produto está sem estoque no momento.
                        </p>
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
                                'categoria' => $produto->categoria_id,
                            ]) }}">
                            Ver mais
                        </a>
                    </div>

                    <div class="front-mini-produto-grid">
                        @foreach ($relacionados as $relacionado)
                            <a href="{{ route('produtos.show', $relacionado->id) }}" class="front-mini-produto">
                                <img
                                    src="{{ $relacionado->foto ? asset($relacionado->foto) : asset('assets/Logo-1.png') }}"
                                    alt="{{ $relacionado->nome }}"
                                >

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
