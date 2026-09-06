<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            {{-- Cabeçalho --}}
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Gerenciamento</span>
                    <h1>Meus produtos</h1>
                    <p>Visualize e organize seus anúncios.</p>
                </div>

                @if (auth()->user()->tipo !== 'administrador')
                    <a href="{{ route('produtos.create') }}" class="front-button front-button-primary">+ Novo produto</a>
                @endif
            </header>

            {{-- Estatísticas --}}
            <section class="front-stat-grid">
                <article>
                    <span>Produtos ativos</span>
                    <strong>{{ $produtosAtivos }}</strong>
                    <small>Em {{ $categoriasTotal }} categorias</small>
                </article>

                <article>
                    <span>Estoque total</span>
                    <strong>{{ $estoqueTotal }}</strong>
                    <small>Unidades disponíveis</small>
                </article>

                <article>
                    <span>Produtos cadastrados</span>
                    <strong>{{ $produtos->count() }}</strong>
                    <small>Total de anúncios</small>
                </article>
            </section>

            {{-- Gráfico administrativo --}}
            @if (auth()->user()->tipo === 'administrador')
                <section class="front-chart-card">
                    <div class="front-section-heading">
                        <div>
                            <span class="front-eyebrow">Relatório</span>
                            <h2>Produtos cadastrados por mês</h2>
                        </div>

                        <small>Últimos 12 meses</small>
                    </div>

                    <div class="front-chart-box">
                        <canvas id="front-produtos-chart" data-labels='["Out","Nov","Dez","Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set"]' data-values='[4,7,5,9,8,12,10,14,13,17,15,20]'></canvas>
                    </div>
                </section>
            @endif

            {{-- Tabela de produtos --}}
            <section class="front-table-card">
                <div class="front-table-toolbar">
                    <div>
                        <h2>Produtos cadastrados</h2>
                        <p>Visualize e gerencie os anúncios da sua conta.</p>
                    </div>

                    <input type="search" placeholder="Pesquisar nesta lista" data-table-search>
                </div>

                <div class="front-table-scroll">
                    <table class="front-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($produtos as $produto)
                                <tr data-search-row>
                                    <td>
                                        <div class="front-table-produto">
                                            <img src="{{ asset($produto->foto) }}" alt="{{ $produto->nome }}">
                                            <strong>{{ $produto->nome }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $produto->categoria?->nome ?? 'Sem categoria' }}</td>
                                    <td>R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                                    <td>{{ $produto->quantidade }}</td>

                                    <td>
                                        <div class="front-table-actions">
                                            <a href="{{ route('produtos.show', $produto->id) }}">Ver</a>
                                            <a href="{{ route('produtos.edit', $produto->id) }}">Editar</a>
                                            <button type="button" data-remove-row>Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">Nenhum produto cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <x-footer />
</x-app-layout>