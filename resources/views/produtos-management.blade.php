<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Gerenciamento</span>
                    <h1>
                        {{ auth()->user()->tipo === 'administrador' ? 'Gerenciar produtos' : 'Meus produtos' }}
                    </h1>
                    <p>
                        {{ auth()->user()->tipo === 'administrador'
                            ? 'Visualize e organize todos os anúncios da plataforma.'
                            : 'Visualize e organize os anúncios da sua conta.' }}
                    </p>
                </div>

                @if (auth()->user()->tipo !== 'administrador')
                    <a href="{{ route('produtos.create') }}" class="front-button front-button-primary">
                        + Novo produto
                    </a>
                @endif
            </header>

            @if (session('success'))
                <div class="front-alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="front-alert-error" role="alert">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

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
                    <strong>{{ $totalProdutos }}</strong>
                    <small>Total de anúncios</small>
                </article>
            </section>

            {{-- O gráfico será conectado ao banco durante a revisão do RF013. --}}
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
                        <canvas
                            id="front-produtos-chart"
                            data-labels='["Out","Nov","Dez","Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set"]'
                            data-values='[4,7,5,9,8,12,10,14,13,17,15,20]'
                        ></canvas>
                    </div>
                </section>
            @endif

            <section class="front-table-card">
                <div class="front-table-toolbar">
                    <div>
                        <h2>Produtos cadastrados</h2>
                        <p>
                            {{ $produtos->count() }}
                            {{ $produtos->count() === 1 ? 'produto encontrado' : 'produtos encontrados' }}
                        </p>
                    </div>

                    <form
                        action="{{ route('produtos.manage') }}"
                        method="GET"
                        class="flex items-center gap-2"
                    >
                        <input
                            type="search"
                            name="busca"
                            value="{{ $busca }}"
                            placeholder="Pesquisar por nome"
                        >

                        <button type="submit" class="front-button front-button-primary">
                            Pesquisar
                        </button>

                        @if ($busca !== '')
                            <a href="{{ route('produtos.manage') }}" class="front-button front-button-ghost">
                                Limpar
                            </a>
                        @endif
                    </form>
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
                                <tr>
                                    <td>
                                        <div class="front-table-produto">
                                            <img
                                                src="{{ asset($produto->foto ?: 'assets/Logo-1.png') }}"
                                                alt="{{ $produto->nome }}"
                                            >
                                            <strong>{{ $produto->nome }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $produto->categoria?->nome ?? 'Sem categoria' }}</td>
                                    <td>R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                                    <td>{{ $produto->quantidade }}</td>

                                    <td>
                                        <div class="front-table-actions">
                                            <a href="{{ route('produtos.show', $produto) }}">
                                                Ver
                                            </a>

                                            <a href="{{ route('produtos.edit', $produto) }}">
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route('produtos.destroy', $produto) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este produto?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        {{ $busca !== ''
                                            ? 'Nenhum produto foi encontrado para esta pesquisa.'
                                            : 'Nenhum produto cadastrado.' }}
                                    </td>
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
