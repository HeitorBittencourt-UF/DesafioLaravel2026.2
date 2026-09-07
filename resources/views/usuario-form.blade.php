<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-form-shell">

            {{-- Breadcrumb --}}
            <div class="front-breadcrumb">
                <a href="{{ route($indexRoute) }}">
                    {{ $administrador ? 'Administradores' : 'Usuários' }}
                </a>

                <span>/</span>

                <span>
                    {{ $criando ? 'Criar' : ($visualizando ? 'Visualizar' : 'Editar') }}
                </span>
            </div>

            @php
                if ($criando) {
                    $formAction = route($storeRoute);
                } elseif ($visualizando) {
                    $formAction = route($indexRoute);
                } else {
                    $formAction = route($updateRoute, $id);
                }
            @endphp

            <form
                action="{{ $formAction }}"
                method="{{ $visualizando ? 'GET' : 'POST' }}"
                enctype="multipart/form-data"
                class="front-form-card"
            >

                {{-- CSRF / PUT --}}
                @unless ($visualizando)
                    @csrf
                @endunless

                @if (! $criando && ! $visualizando)
                    @method('PUT')
                @endif


                {{-- Cabeçalho --}}
                <div class="front-form-section-heading">

                    <span>
                        {{ $administrador ? 'A' : 'U' }}
                    </span>

                    <div>
                        <h1>
                            @if ($criando)
                                Novo {{ $administrador ? 'administrador' : 'usuário' }}
                            @elseif ($visualizando)
                                Visualizar {{ $administrador ? 'administrador' : 'usuário' }}
                            @else
                                Editar {{ $administrador ? 'administrador' : 'usuário' }}
                            @endif
                        </h1>

                        <p>
                            @if ($visualizando)
                                Dados completos do cadastro, sem exibir a senha.
                            @elseif ($criando)
                                Preencha os dados obrigatórios para concluir o cadastro.
                            @else
                                Altere os dados desejados e salve as modificações.
                            @endif
                        </p>
                    </div>

                </div>


                {{-- Erros de validação --}}
                @if ($errors->any())
                    <div class="front-alert front-alert-error">
                        <strong>Não foi possível salvar o cadastro.</strong>

                        <ul>
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="front-form-grid">

                    {{-- Nome --}}
                    <label class="front-field front-field-wide">
                        <span>Nome</span>

                        <input
                            name="nome"
                            type="text"
                            maxlength="150"
                            value="{{ old('nome', $pessoa?->nome) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- E-mail --}}
                    <label class="front-field front-field-wide">
                        <span>E-mail</span>

                        <input
                            name="email"
                            type="email"
                            maxlength="150"
                            value="{{ old('email', $pessoa?->email) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Senha --}}
                    @unless ($visualizando)

                        <label class="front-field">
                            <span>
                                Senha
                                @unless ($criando)
                                    (deixe em branco para manter)
                                @endunless
                            </span>

                            <input
                                name="senha"
                                type="password"
                                minlength="8"
                                autocomplete="new-password"
                                @required($criando)
                            >
                        </label>

                    @endunless


                    {{-- CPF --}}
                    <label class="front-field">
                        <span>CPF</span>

                        <input
                            name="cpf"
                            id="cpf"
                            type="text"
                            maxlength="14"
                            value="{{ old('cpf', $pessoa?->cpf) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Telefone --}}
                    <label class="front-field">
                        <span>Telefone</span>

                        <input
                            name="telefone"
                            id="telefone"
                            type="text"
                            maxlength="20"
                            value="{{ old('telefone', $pessoa?->telefone) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Data de nascimento --}}
                    <label class="front-field">
                        <span>Data de nascimento</span>

                        <input
                            name="data_nascimento"
                            type="date"
                            value="{{ old(
                                'data_nascimento',
                                $pessoa?->data_nascimento?->format('Y-m-d')
                            ) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Saldo só existe no gerenciamento de usuário comum --}}
                    @unless ($administrador)

                        <label class="front-field">
                            <span>Saldo</span>

                            <input
                                name="saldo"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('saldo', $pessoa?->saldo ?? '0.00') }}"
                                @disabled($visualizando)
                            >
                        </label>

                    @endunless


                    {{-- Foto --}}
                    <label class="front-field front-field-wide">
                        <span>
                            Foto

                            @if ($administrador && $criando)
                                *
                            @elseif (! $criando)
                                (opcional para alterar)
                            @endif
                        </span>

                        @if ($pessoa?->foto)
                            <div style="margin-bottom: 12px;">
                                <img
                                    src="{{ asset($pessoa->foto) }}"
                                    alt="Foto de {{ $pessoa->nome }}"
                                    style="
                                        width: 90px;
                                        height: 90px;
                                        object-fit: cover;
                                        border-radius: 12px;
                                    "
                                >
                            </div>
                        @endif

                        @unless ($visualizando)
                            <input
                                name="foto"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                @required($administrador && $criando)
                            >
                        @endunless

                    </label>


                    {{-- Divisor endereço --}}
                    <div class="front-form-divider front-field-full">
                        <span>Endereço</span>
                    </div>


                    {{-- CEP --}}
                    <label class="front-field">
                        <span>CEP</span>

                        <input
                            name="cep"
                            id="cep"
                            type="text"
                            maxlength="9"
                            value="{{ old('cep', $endereco?->cep) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Logradouro --}}
                    <label class="front-field front-field-wide">
                        <span>Logradouro</span>

                        <input
                            name="logradouro"
                            id="logradouro"
                            type="text"
                            maxlength="150"
                            value="{{ old('logradouro', $endereco?->logradouro) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Número --}}
                    <label class="front-field">
                        <span>Número</span>

                        <input
                            name="numero"
                            id="numero"
                            type="text"
                            maxlength="10"
                            value="{{ old('numero', $endereco?->numero) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Bairro --}}
                    <label class="front-field">
                        <span>Bairro</span>

                        <input
                            name="bairro"
                            id="bairro"
                            type="text"
                            maxlength="100"
                            value="{{ old('bairro', $endereco?->bairro) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Cidade --}}
                    <label class="front-field">
                        <span>Cidade</span>

                        <input
                            name="cidade"
                            id="cidade"
                            type="text"
                            maxlength="100"
                            value="{{ old('cidade', $endereco?->cidade) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Estado --}}
                    <label class="front-field">
                        <span>Estado</span>

                        <input
                            name="estado"
                            id="estado"
                            type="text"
                            maxlength="2"
                            value="{{ old('estado', $endereco?->estado) }}"
                            @disabled($visualizando)
                            required
                        >
                    </label>


                    {{-- Complemento --}}
                    <label class="front-field front-field-wide">
                        <span>Complemento</span>

                        <input
                            name="complemento"
                            id="complemento"
                            type="text"
                            maxlength="100"
                            value="{{ old('complemento', $endereco?->complemento) }}"
                            @disabled($visualizando)
                        >
                    </label>

                </div>


                {{-- Botões --}}
                <div class="front-form-actions">

                    <a
                        href="{{ route($indexRoute) }}"
                        class="front-button front-button-ghost"
                    >
                        Voltar
                    </a>


                    @if ($visualizando)

                        <a
                            href="{{ route($editRoute, $id) }}"
                            class="front-button front-button-primary"
                        >
                            Editar cadastro
                        </a>

                    @else

                        <button
                            type="submit"
                            class="front-button front-button-primary"
                        >
                            {{ $criando ? 'Criar cadastro' : 'Salvar alterações' }}
                        </button>

                    @endif

                </div>

            </form>
        </div>
    </main>

    <x-footer />
</x-app-layout>