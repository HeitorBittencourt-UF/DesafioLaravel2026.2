<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Histórico</span>

                    <h1>
                        @if ($vendas)
                            {{ auth()->user()->tipo === 'administrador' ? 'Histórico de vendas' : 'Minhas vendas' }}
                        @else
                            Minhas compras
                        @endif
                    </h1>

                    <p>Acompanhe as transações realizadas no sistema.</p>
                </div>

                <div class="front-heading-actions">
                    <a href="{{ route('reports.show', ['tipo' => $vendas ? 'vendas' : 'compras', 'inicio' => request('inicio'), 'fim' => request('fim')]) }}" target="_blank" rel="noopener" class="front-button front-button-primary">
                        Gerar PDF
                    </a>

                    @if ($vendas && auth()->user()->tipo === 'administrador')
                        <a href="{{ route('reports.sales.xlsx', ['inicio' => request('inicio'), 'fim' => request('fim')]) }}" class="front-button front-button-ghost">
                            Gerar XLSX
                        </a>
                    @endif
                </div>
            </header>

            @if ($errors->any())
                <div class="front-alert-error">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ $vendas ? route('historico.vendas') : route('historico.compras') }}" method="GET" class="front-period-filter">
                <label class="front-field">
                    <span>Data inicial</span>
                    <input type="date" name="inicio" value="{{ request('inicio') }}">
                </label>

                <label class="front-field">
                    <span>Data final</span>
                    <input type="date" name="fim" value="{{ request('fim') }}">
                </label>

                <button class="front-button front-button-primary" type="submit">Aplicar período</button>

                @if (request()->filled('inicio') || request()->filled('fim'))
                    <a href="{{ $vendas ? route('historico.vendas') : route('historico.compras') }}" class="front-button front-button-ghost">
                        Limpar período
                    </a>
                @endif
            </form>

            @if ($vendas && ($mostrarGraficoVendas ?? false))
                <section class="front-chart-card">
                    <div class="front-section-heading">
                        <div>
                            <span class="front-eyebrow">Desempenho</span>
                            <h2>Vendas realizadas por mês</h2>
                        </div>

                        <small>Últimos 12 meses</small>
                    </div>

                    <div class="front-chart-box">
                        <canvas id="front-sales-chart" data-labels='@json($chartLabels)' data-values='@json($chartValues)' role="img" aria-label="Quantidade de vendas realizadas por mês nos últimos 12 meses"></canvas>
                    </div>
                </section>
            @endif

            <section class="front-table-card">
                <div class="front-table-toolbar">
                    <div>
                        <h2>Transações</h2>

                        <p>
                            {{ $registros->count() }}
                            {{ $registros->count() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                        </p>
                    </div>
                </div>

                <div class="front-table-scroll">
                    <table class="front-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Qtd.</th>
                                <th>Data</th>
                                <th>{{ $vendas ? 'Comprador' : 'Vendedor' }}</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($registros as $registro)
                                <tr>
                                    <td>
                                        <div class="front-table-produto">
                                            @if (! empty($registro['foto']))
                                                <img src="{{ asset($registro['foto']) }}" alt="Foto de {{ $registro['produto'] }}">
                                            @else
                                                <div style="width: 46px; height: 42px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border-radius: 7px; background: rgba(255,255,255,0.08);">
                                                    <span>{{ mb_strtoupper(mb_substr($registro['produto'], 0, 1)) }}</span>
                                                </div>
                                            @endif

                                            <strong>{{ $registro['produto'] }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $registro['category'] }}</td>
                                    <td>{{ $registro['quantidade'] ?? 1 }}</td>
                                    <td>{{ $registro['date'] }}</td>
                                    <td>{{ $registro['other'] }}</td>
                                    <td>R$ {{ number_format($registro['value'], 2, ',', '.') }}</td>

                                    <td>
                                        <span class="front-status">{{ ucfirst($registro['status']) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Nenhuma transação encontrada no período informado.</td>
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