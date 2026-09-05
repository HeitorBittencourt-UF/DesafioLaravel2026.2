<x-app-layout>
    <div class="dash-body">
        <!-- Dashboard Topbar -->
        <header class="dash-navbar">
            <div class="flex items-center gap-3">
                <img src="/logo.png" alt="HypeStore Logo" class="w-10 h-10">
                <span class="dash-header-title">HYPESTORE</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-300">Olá, <strong class="text-[#42B9A6]">{{ Auth::user()->name ?? 'Usuário' }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-white underline transition-colors">
                        {{ __('Sair') }}
                    </button>
                </form>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="dash-main">
            <!-- Título e Ação -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="dash-header-title">PAINEL DE CONTROLE</h1>
                    <p class="text-gray-400 text-sm">Acompanhe suas vendas, pedidos e métricas de desempenho.</p>
                </div>
                <button class="dash-btn">
                    + Novo Produto
                </button>
            </div>

            <!-- Cards de Métricas (Brancos com Glow Verde) -->
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

            <!-- Tabela de Últimas Atividades / Pedidos -->
            <div class="dash-panel">
                <h2 class="font-league text-2xl text-white mb-4">ÚLTIMOS PEDIDOS</h2>
                
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
        </main>
    </div>
</x-app-layout>