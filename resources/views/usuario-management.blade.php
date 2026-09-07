<x-app-layout>
    <main class="front-page front-management-page">
        <div class="front-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Administração</span>
                    <h1>Gerenciar {{ $administradores ? 'administradores' : 'usuários' }}</h1>
                    <p>Crie, visualize, edite e exclua registros pela interface.</p>
                </div>

                <a href="{{ route($createRoute) }}" class="front-button front-button-primary">
                    + Novo {{ $administradores ? 'administrador' : 'usuário' }}
                </a>
            </header>

            <nav class="front-management-tabs">
                <a href="{{ route('admin.users.index') }}" @class(['active' => ! $administradores])>Usuários</a>
                <a href="{{ route('admin.admins.index') }}" @class(['active' => $administradores])>Administradores</a>
                <a href="{{ route('admin.email') }}">Enviar e-mail</a>
            </nav>

            @if (session('success'))
                <div class="front-alert-success">
                    {{ session('success') }}
                </div>
            @endif

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
                                            @if ($pessoa->foto)
                                                <img src="{{ asset($pessoa->foto) }}" alt="Foto de {{ $pessoa->nome }}" class="front-person-photo">
                                            @else
                                                <span class="front-person-initial">
                                                    {{ mb_strtoupper(mb_substr($pessoa->nome, 0, 1)) }}
                                                </span>
                                            @endif

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

                                            @php
                                                $podeGerenciar = true;

                                                if ($administradores) {
                                                    $adminLogado = auth()->user();
                                                    $ehProprioPerfil = (int) $adminLogado->getKey() === (int) $pessoa->getKey();
                                                    $foiCriadoPeloAdmin = (int) $pessoa->criador_id === (int) $adminLogado->getKey();
                                                    $podeGerenciar = $ehProprioPerfil || $foiCriadoPeloAdmin;
                                                }
                                            @endphp

                                            @if ($podeGerenciar)
                                                <a href="{{ route($editRoute, $pessoa->getKey()) }}">Editar</a>

                                                <form action="{{ route($destroyRoute, $pessoa->getKey()) }}" method="POST" class="front-delete-form" onsubmit="return confirm('Tem certeza que deseja excluir {{ addslashes($pessoa->nome) }}?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit">Excluir</button>
                                                </form>
                                            @endif
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