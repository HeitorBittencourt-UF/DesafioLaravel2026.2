<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            {{-- Cabeçalho --}}
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Administração</span>
                    <h1>Gerenciar {{ $administradores ? 'administradores' : 'usuários' }}</h1>
                    <p>Crie, visualize, edite e exclua registros pela interface.</p>
                </div>

                <a href="{{ route($createRoute) }}" class="front-button front-button-primary">+ Novo {{ $administradores ? 'administrador' : 'usuário' }}</a>
            </header>

            {{-- Navegação administrativa --}}
            <nav class="front-management-tabs">
                <a href="{{ route('admin.users.index') }}" @class(['active' => ! $administradores])>Usuários</a>
                <a href="{{ route('admin.admins.index') }}" @class(['active' => $administradores])>Administradores</a>
                <a href="{{ route('admin.email') }}">Enviar e-mail</a>
            </nav>

            {{-- Estatísticas --}}
            <section class="front-stat-grid">
                <article>
                    <span>Total cadastrado</span>
                    <strong>{{ $total }}</strong>
                    <small>No sistema</small>
                </article>

                <article>
                    <span>Ativos</span>
                    <strong>{{ $ativos }}</strong>
                    <small>Acesso liberado</small>
                </article>

                <article>
                    <span>Novos este mês</span>
                    <strong>{{ $novosEsteMes }}</strong>
                    <small>Mês atual</small>
                </article>
            </section>

            {{-- Tabela --}}
            <section class="front-table-card">
                <div class="front-table-toolbar">
                    <div>
                        <h2>Lista de {{ $administradores ? 'administradores' : 'usuários' }}</h2>
                        <p>A senha não é exibida na administração.</p>
                    </div>

                    <input type="search" placeholder="Pesquisar nome ou e-mail" data-table-search>
                </div>

                <div class="front-table-scroll">
                    <table class="front-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Cidade</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pessoas as $pessoa)
                                <tr data-search-row>
                                    <td>
                                        <div class="front-person-cell">
                                            <span>{{ mb_strtoupper(mb_substr($pessoa->nome, 0, 1)) }}</span>
                                            <strong>{{ $pessoa->nome }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $pessoa->email }}</td>
                                    <td>{{ $pessoa->telefone ?? 'Não informado' }}</td>
                                    <td>{{ $pessoa->enderecos->first()?->cidade ?? 'Não informado' }}</td>

                                    <td>
                                        <span class="front-status">Ativo</span>
                                    </td>

                                    <td>
                                        <div class="front-table-actions">
                                            <a href="{{ route($showRoute, $pessoa->getKey()) }}">Ver</a>
                                            <a href="{{ route($editRoute, $pessoa->getKey()) }}">Editar</a>
                                            <button type="button" data-remove-row>Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Nenhum registro encontrado.</td>
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