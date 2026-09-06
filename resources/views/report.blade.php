<x-app-layout>
    <main class="front-page front-report-page">
        <div class="front-shell">
            <div class="front-report-actions">
                <a href="{{ $vendas ? route('historico.vendas') : route('historico.compras') }}"
                    class="front-button front-button-ghost">← Voltar</a>
                <button type="button" class="front-button front-button-primary" onclick="window.print()">
                    Imprimir / salvar PDF
                </button>
            </div>

            <article class="front-report-sheet">
                <header>
                    <img src="{{ asset('assets/Logo-1.png') }}" alt="HypeStore">
                    <div>
                        <span>HypeStore</span>
                        <h1>Relatório de {{ $vendas ? 'vendas' : 'compras' }}</h1>
                    </div>
                </header>

                <p class="front-report-period">
                    Período:
                    {{ $inicio ? \Carbon\Carbon::parse($inicio)->format('d/m/Y') : 'início dos registros' }}
                    a
                    {{ $fim ? \Carbon\Carbon::parse($fim)->format('d/m/Y') : now()->format('d/m/Y') }}
                </p>

                <div class="front-table-scroll">
                    <table class="front-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Comprador</th>
                                <th>Vendedor</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($registros as $registro)
                                <tr>
                                    <td>{{ $registro['date'] }}</td>
                                    <td>{{ $registro['produto'] }}</td>
                                    <td>{{ $registro['category'] }}</td>
                                    <td>{{ $registro['comprador'] }}</td>
                                    <td>{{ $registro['vendedor'] }}</td>
                                    <td>R$ {{ number_format($registro['value'], 2, ',', '.') }}</td>
                                    <td>{{ ucfirst($registro['status']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Nenhum registro encontrado no período informado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <footer>
                    <span>Total de registros: {{ $registros->count() }}</span>
                    <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </footer>
            </article>
        </div>
    </main>
</x-app-layout>
