<x-app-layout>
    <div class="dash-body">
        <!-- Conteúdo Principal -->
        <main class="dash-main">
            <!-- Título -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="dash-header-title">PAINEL DE CONTROLE</h1>
                    <p class="text-gray-400 text-sm">Acompanhe suas vendas, pedidos e métricas de desempenho.</p>
                </div>
                @if (Auth::user()->tipo !== 'administrador')
                    <a href="{{ route('produtos.create') }}" class="dash-btn">+ Novo Produto</a>
                @endif
            </div>

            <!-- Cards  -->
            <div class="dash-card-grid">
                <div class="dash-stat-card">
                    <span class="dash-stat-title">Vendas Totais</span>
                    <span class="dash-stat-value">R$ 12.450,00</span>
                    <span class="dash-stat-badge">+18% este mês</span>
                </div>
                <div class="dash-stat-card">
                    <span class="dash-stat-title">Pedidos Concluídos</span>
                    <span class="dash-stat-value">342</span>
                    <span class="dash-stat-badge">+5 novos hoje</span>
                </div>
                <div class="dash-stat-card">
                    <span class="dash-stat-title">Produtos Ativos</span>
                    <span class="dash-stat-value">128</span>
                    <span class="dash-stat-badge">Em 8 categorias</span>
                </div>
            </div>

            <nav class="front-dashboard-links" aria-label="Acessos rápidos">
                <a href="{{ route('produtos.manage') }}"><strong>Meus produtos</strong><span>Gerenciar anúncios</span></a>
                <a href="{{ route('historico.compras') }}"><strong>Compras</strong><span>Ver histórico</span></a>
                <a href="{{ route('historico.vendas') }}"><strong>Vendas</strong><span>Histórico e gráfico</span></a>
                @if (Auth::user()->tipo === 'administrador')
                    <a href="{{ route('admin.users.index') }}"><strong>Usuários</strong><span>Gerenciar contas</span></a>
                    <a href="{{ route('admin.admins.index') }}"><strong>Administradores</strong><span>Gerenciar equipe</span></a>
                    <a href="{{ route('admin.email') }}"><strong>E-mail</strong><span>Enviar mensagem</span></a>
                @endif
            </nav>

            @if ($graficoProdutos !== null)
                <!-- Gráfico visível para administradores -->
                <section class="dash-panel dash-chart-panel" aria-labelledby="produto-chart-title">
                    <div class="dash-chart-header">
                        <h2 id="produto-chart-title" class="dash-chart-title">
                            PRODUTOS CADASTRADOS POR MÊS
                        </h2>
                        <span class="dash-chart-period">Últimos 12 meses</span>
                    </div>

                    <div class="dash-chart-scroll">
                        <div class="dash-chart-wrapper">
                            <canvas
                                id="produto-by-month-chart"
                                data-labels='@json($graficoProdutos['labels'])'
                                data-values='@json($graficoProdutos['valores'])'
                                role="img"
                                aria-label="Gráfico de barras com a quantidade de produtos cadastrados por mês nos últimos 12 meses"
                            ></canvas>
                        </div>
                    </div>
                </section>
            @else
                <!-- Usuários comuns continuam vendo a tabela atual -->
                <div class="dash-panel">
                    <h2 class="font-league text-2xl text-white mb-4">ÚLTIMOS PEDIDOS</h2>

                    <div class="front-table-scroll">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Produto</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-bold text-white">#1024</td>
                                <td>Lucas Silva</td>
                                <td>Apple iPhone 13 (128gb)</td>
                                <td>R$ 2.761,44</td>
                                <td><span class="text-emerald-400 font-semibold">Aprovado</span></td>
                            </tr>
                            <tr>
                                <td class="font-bold text-white">#1023</td>
                                <td>Mariana Costa</td>
                                <td>Water Cooler Montech LightFlow</td>
                                <td>R$ 209,99</td>
                                <td><span class="text-amber-400 font-semibold">Pendente</span></td>
                            </tr>
                            <tr>
                                <td class="font-bold text-white">#1022</td>
                                <td>Gabriel Rocha</td>
                                <td>Teclado Magnetico Redragon</td>
                                <td>R$ 229,99</td>
                                <td><span class="text-emerald-400 font-semibold">Enviado</span></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            @endif
        </main>
    </div>
</x-app-layout>
