<x-app-layout>
    <main class="front-page">
        <div class="front-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Catálogo</span>
                    <h1>{{ request('ofertas') ? 'Ofertas em destaque' : 'Produtos' }}</h1>
                    <p>Pesquise pelo nome ou selecione uma categoria.</p>
                </div>

                <span class="front-result-count">{{ $produtosFiltrados->total() }} encontrados</span>
            </header>

            <form action="{{ route('produtos.index') }}" method="GET" class="front-filter-panel">
                <label>
                    <span>Nome do produto</span>
                    <input type="search" name="busca" value="{{ $busca }}" placeholder="O que você procura?">
                </label>

                <label>
                    <span>Categoria</span>
                    <select name="categoria">
                        <option value="">Todas</option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected((string) $categoriaId === (string) $categoria->id)>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </label>

                <button type="submit" class="front-button front-button-primary">Filtrar</button>
                <a href="{{ route('produtos.index') }}" class="front-button front-button-ghost">Limpar</a>
            </form>

            <nav class="front-category-pills" aria-label="Categorias">
                <a href="{{ route('produtos.index', array_filter(['busca' => $busca])) }}" @class(['active' => empty($categoriaId)])>Todas</a>

                @foreach ($categorias as $categoria)
                    <a href="{{ route('produtos.index', array_filter(['busca' => $busca, 'categoria' => $categoria->id])) }}" @class(['active' => (string) $categoriaId === (string) $categoria->id])>{{ $categoria->nome }}</a>
                @endforeach
            </nav>

            <section class="front-produto-grid" aria-label="Lista de produtos">
                @forelse ($produtosFiltrados as $produto)
                    <article class="front-produto-card">
                        <a href="{{ route('produtos.show', $produto->id) }}" class="front-produto-image-box">
                            <img src="{{ asset($produto->foto) }}" alt="{{ $produto->nome }}">
                        </a>

                        <div class="front-produto-content">
                            <span class="front-produto-category">{{ $produto->categoria?->nome ?? 'Sem categoria' }}</span>

                            <h2>
                                <a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->nome }}</a>
                            </h2>

                            <span class="front-produto-price">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</span>
                            <small>{{ $produto->quantidade }} unidades disponíveis</small>

                            <div class="front-produto-actions">
                                <a href="{{ route('produtos.show', $produto->id) }}" class="front-button front-button-ghost">Ver produto</a>

                                @if (auth()->user()->tipo !== 'administrador')
                                    <a href="{{ route('compra.show', $produto->id) }}" class="front-button front-button-primary">Comprar</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="front-empty-state">
                        <h2>Nenhum produto encontrado</h2>
                        <p>Altere a busca ou remova o filtro de categoria.</p>
                        <a href="{{ route('produtos.index') }}" class="front-button front-button-primary">Ver todos</a>
                    </div>
                @endforelse
            </section>

            @if ($produtosFiltrados->lastPage() > 1)
                <nav class="front-pagination" aria-label="Paginação">
                    @for ($pagina = 1; $pagina <= $produtosFiltrados->lastPage(); $pagina++)
                        <a href="{{ $produtosFiltrados->url($pagina) }}" @class(['active' => $pagina === $produtosFiltrados->currentPage()])>{{ $pagina }}</a>
                    @endfor
                </nav>
            @endif
        </div>
    </main>

    <x-footer />
</x-app-layout>
