
<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-form-shell">
            <div class="front-breadcrumb"><a href="{{ route($indexRoute) }}">{{ $administrador ? 'Administradores' : 'Usuários' }}</a><span>/</span><span>{{ ucfirst($modo) }}</span></div>

            <form action="{{ route($indexRoute) }}" method="GET" class="front-form-card">
                <div class="front-form-section-heading">
                    <span>{{ $administrador ? 'A' : 'U' }}</span>
                    <div>
                        <h1>{{ $criando ? 'Novo' : ($visualizando ? 'Visualizar' : 'Editar') }} {{ $administrador ? 'administrador' : 'usuário' }}</h1>
                        <p>{{ $visualizando ? 'Dados completos do cadastro, sem exibir a senha.' : 'Preencha os dados obrigatórios para concluir o cadastro.' }}</p>
                    </div>
                </div>

                <div class="front-form-grid">
                    <label class="front-field front-field-wide"><span>Nome</span><input name="nome" value="{{ $criando ? '' : 'Marina Costa' }}" @disabled($visualizando) required></label>
                    <label class="front-field front-field-wide"><span>E-mail</span><input name="email" type="email" value="{{ $criando ? '' : 'marina@exemplo.com' }}" @disabled($visualizando) required></label>
                    @unless ($visualizando)
                        <label class="front-field"><span>Senha {{ $criando ? '' : '(opcional)' }}</span><input name="senha" type="password" @required($criando)></label>
                    @endunless
                    <label class="front-field"><span>CPF</span><input name="cpf" id="cpf" value="{{ $criando ? '' : '123.456.789-00' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Telefone</span><input name="telefone" id="telefone" value="{{ $criando ? '' : '(32) 99999-1234' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Data de nascimento</span><input name="data_nascimento" type="date" value="{{ $criando ? '' : '2002-05-18' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Saldo</span><input name="saldo" value="{{ $criando ? '0,00' : '1.250,00' }}" @disabled($visualizando)></label>
                    <label class="front-field"><span>Foto</span><input name="foto" type="file" accept="image/*" @disabled($visualizando)></label>

                    <div class="front-form-divider front-field-full"><span>Endereço</span></div>
                    <label class="front-field"><span>CEP</span><input name="cep" value="{{ $criando ? '' : '36035-680' }}" @disabled($visualizando) required></label>
                    <label class="front-field front-field-wide"><span>Logradouro</span><input name="logradouro" value="{{ $criando ? '' : 'Rua Guilherme Debussy' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Número</span><input name="numero" value="{{ $criando ? '' : '120' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Bairro</span><input name="bairro" value="{{ $criando ? '' : 'Borboleta' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Cidade</span><input name="cidade" value="{{ $criando ? '' : 'Juiz de Fora' }}" @disabled($visualizando) required></label>
                    <label class="front-field"><span>Estado</span><input name="estado" maxlength="2" value="{{ $criando ? '' : 'MG' }}" @disabled($visualizando) required></label>
                    <label class="front-field front-field-wide"><span>Complemento</span><input name="complemento" value="{{ $criando ? '' : 'Apto. 201' }}" @disabled($visualizando)></label>
                </div>

                <div class="front-form-actions">
                    <a href="{{ route($indexRoute) }}" class="front-button front-button-ghost">Voltar</a>
                    @if ($visualizando)
                        <a href="{{ route($editRoute, $id) }}" class="front-button front-button-primary">Editar cadastro</a>
                    @else
                        <button type="submit" class="front-button front-button-primary">{{ $criando ? 'Criar cadastro' : 'Salvar alterações' }}</button>
                    @endif
                </div>
            </form>
        </div>
    </main>
    <x-footer />
</x-app-layout>
