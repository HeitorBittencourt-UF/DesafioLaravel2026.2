<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">

            {{-- ============================================================
                CABEÇALHO
            ============================================================ --}}
            <header class="front-page-heading">

                <div>
                    <span class="front-eyebrow">
                        Histórico
                    </span>

                    <h1>
                        @if ($vendas)
                        {{ auth()->user()->tipo === 'administrador'
                                ? 'Histórico de vendas'
                                : 'Minhas vendas'
                            }}
                        @else
                        Minhas compras
                        @endif
                    </h1>

                    <p>
                        Acompanhe as transações realizadas no sistema.
                    </p>
                </div>


                {{-- ========================================================
                    BOTÕES DE EXPORTAÇÃO
                ======================================================== --}}
                <div class="front-heading-actions">

                    {{-- PDF --}}
                    <a
                        href="{{ route('reports.show', [
                            'tipo' => $vendas
                                ? 'vendas'
                                : 'compras',

                            'inicio' => request('inicio'),
                            'fim' => request('fim'),
                        ]) }}"
                        class="front-button front-button-primary">
                        Gerar PDF
                    </a>


                    {{-- XLSX somente para administrador e somente em vendas --}}
                    @if (
                    $vendas
                    && auth()->user()->tipo === 'administrador'
                    )

                    <a
                        href="{{ route('reports.sales.xlsx', [
                                'inicio' => request('inicio'),
                                'fim' => request('fim'),
                            ]) }}"
                        class="front-button front-button-ghost">
                        Gerar XLSX
                    </a>

                    @endif

                </div>

            </header>


            {{-- ============================================================
                ERROS
            ============================================================ --}}
            @if ($errors->any())

            <div class="front-alert-error">

                @foreach ($errors->all() as $erro)
                <p>
                    {{ $erro }}
                </p>
                @endforeach

            </div>

            @endif


            {{-- ============================================================
                FILTRO POR PERÍODO
            ============================================================ --}}
            <form
                action="{{ $vendas
                    ? route('historico.vendas')
                    : route('historico.compras')
                }}"
                method="GET"
                class="front-period-filter">

                <label class="front-field">

                    <span>
                        Data inicial
                    </span>

                    <input
                        type="date"
                        name="inicio"
                        value="{{ request('inicio') }}">

                </label>


                <label class="front-field">

                    <span>
                        Data final
                    </span>

                    <input
                        type="date"
                        name="fim"
                        value="{{ request('fim') }}">

                </label>


                <button
                    class="front-button front-button-primary"
                    type="submit">
                    Aplicar período
                </button>


                {{-- Mostra limpar somente se existir algum filtro --}}
                @if (
                request()->filled('inicio')
                || request()->filled('fim')
                )

                <a
                    href="{{ $vendas
                            ? route('historico.vendas')
                            : route('historico.compras')
                        }}"
                    class="front-button front-button-ghost">
                    Limpar período
                </a>

                @endif

            </form>


            {{-- ============================================================
                RF014 - GRÁFICO DE VENDAS
            ============================================================
                Apenas usuários comuns podem visualizar.

                Administrador continua conseguindo visualizar o histórico
                de vendas, mas NÃO visualiza este gráfico.
            ============================================================ --}}
            @if (
            $vendas
            && ($mostrarGraficoVendas ?? false)
            )

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


            {{-- ============================================================
                TABELA DO HISTÓRICO
            ============================================================ --}}
            <section class="front-table-card">

                {{-- Cabeçalho da tabela --}}
                <div class="front-table-toolbar">

                    <div>

                        <h2>
                            Transações
                        </h2>

                        <p>
                            {{ $registros->count() }}
                            {{ $registros->count() === 1
                                ? 'registro encontrado'
                                : 'registros encontrados'
                            }}
                        </p>

                    </div>

                </div>


                {{-- Scroll para telas menores --}}
                <div class="front-table-scroll">

                    <table class="front-table">

                        {{-- ==================================================
                            CABEÇALHO
                        ================================================== --}}
                        <thead>

                            <tr>

                                <th>
                                    Produto
                                </th>

                                <th>
                                    Categoria
                                </th>

                                <th>
                                    Qtd.
                                </th>

                                <th>
                                    Data
                                </th>

                                <th>
                                    {{ $vendas
                                        ? 'Comprador'
                                        : 'Vendedor'
                                    }}
                                </th>

                                <th>
                                    Valor
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        {{-- ==================================================
                            REGISTROS
                        ================================================== --}}
                        <tbody>

                            @forelse ($registros as $registro)

                            <tr>

                                {{-- ======================================
                                        PRODUTO + FOTO
                                    ====================================== --}}
                                <td>

                                    <div class="front-table-produto">

                                        @if (! empty($registro['foto']))

                                        <img
                                            src="{{ asset($registro['foto']) }}"
                                            alt="Foto de {{ $registro['produto'] }}">

                                        @else

                                        {{-- Caso um produto antigo não tenha foto --}}
                                        <div
                                            style="
                                                        width: 46px;
                                                        height: 42px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        flex-shrink: 0;
                                                        border-radius: 7px;
                                                        background: rgba(255,255,255,0.08);
                                                    ">
                                            <span>
                                                {{ mb_strtoupper(
                                                            mb_substr(
                                                                $registro['produto'],
                                                                0,
                                                                1
                                                            )
                                                        ) }}
                                            </span>
                                        </div>

                                        @endif


                                        <strong>
                                            {{ $registro['produto'] }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- ======================================
                                        CATEGORIA
                                    ====================================== --}}
                                <td>
                                    {{ $registro['category'] }}
                                </td>


                                {{-- ======================================
                                        QUANTIDADE
                                    ====================================== --}}
                                <td>
                                    {{ $registro['quantidade'] ?? 1 }}
                                </td>


                                {{-- ======================================
                                        DATA
                                    ====================================== --}}
                                <td>
                                    {{ $registro['date'] }}
                                </td>


                                {{-- ======================================
                                        COMPRADOR / VENDEDOR
                                    ====================================== --}}
                                <td>
                                    {{ $registro['other'] }}
                                </td>


                                {{-- ======================================
                                        VALOR
                                    ====================================== --}}
                                <td>
                                    R$
                                    {{ number_format(
                                            $registro['value'],
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                </td>


                                {{-- ======================================
                                        STATUS
                                    ====================================== --}}
                                <td>

                                    <span class="front-status">
                                        {{ ucfirst(
                                                $registro['status']
                                            ) }}
                                    </span>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td colspan="7">
                                    Nenhuma transação encontrada no período informado.
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