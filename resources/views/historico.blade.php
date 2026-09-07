<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Histórico</span>
                    <h1>{{ $vendas ? 'Minhas vendas' : 'Minhas compras' }}</h1>
                    <p>Acompanhe as transações realizadas no sistema.</p>
                </div>

                <div class="front-heading-actions">
                    <a href="{{ route('reports.show', [
                        'tipo' => $vendas ? 'vendas' : 'compras',
                        'inicio' => request('inicio'),
                        'fim' => request('fim'),
                    ]) }}"
                        class="front-button front-button-primary">Gerar PDF</a>
                </div>
            </header>

            @if ($errors->any())
            <div class="front-alert-error">
                @foreach ($errors->all() as $erro)
                <p>{{ $erro }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ $vendas ? route('historico.vendas') : route('historico.compras') }}" method="GET"
                class="front-period-filter">
                <label class="front-field">
                    <span>Data inicial</span>
                    <input type="date" name="inicio" value="{{ request('inicio') }}">
                </label>
                <label class="front-field">
                    <span>Data final</span>
                    <input type="date" name="fim" value="{{ request('fim') }}">
                </label>
                <button class="front-button front-button-primary" type="submit">Aplicar período</button>
            </form>

            {{-- RF014 - Gráfico de vendas realizadas --}}
            @if ($vendas && $mostrarGraficoVendas)

            <section class="front-chart-card">

                <div class="front-section-heading">

                    <div>
                        <span class="front-eyebrow">
                            Desempenho
                        </span>

                        <h2>
                            Vendas realizadas por mês
                        </h2>
                    </div>

                    <small>
                        Últimos 12 meses
                    </small>

                </div>


                <div class="front-chart-box">

                    <canvas
                        id="front-sales-chart"
                        data-labels='@json($chartLabels)'
                        data-values='@json($chartValues)'
                        role="img"
                        aria-label="Quantidade de vendas realizadas por mês nos últimos 12 meses"></canvas>

                </div>

            </section>

            @endif

            <section class="front-table-card">
                <div class="front-table-toolbar">
                    <div>
                        <h2>Transações</h2>
                        <p>{{ $registros->count() }} registros encontrados</p>
                    </div>
                </div>

                <div class="front-table-scroll">
                    <table class="front-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Data</th>
                                <th>{{ $vendas ? 'Comprador' : 'Vendedor' }}</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($registros as $registro)
                            <tr>
                                <td><strong>{{ $registro['produto'] }}</strong></td>
                                <td>{{ $registro['category'] }}</td>
                                <td>{{ $registro['date'] }}</td>
                                <td>{{ $registro['other'] }}</td>
                                <td>R$ {{ number_format($registro['value'], 2, ',', '.') }}</td>
                                <td><span class="front-status">{{ ucfirst($registro['status']) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">Nenhuma transação encontrada no período informado.</td>
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