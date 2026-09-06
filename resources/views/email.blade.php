<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-form-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Administração</span>
                    <h1>Enviar e-mail</h1>
                    <p>Interface para comunicação entre administrador e usuário.</p>
                </div>
            </header>

            @if (session('success'))
                <div class="front-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="front-alert-error">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.email.send') }}" method="POST" class="front-form-card">
                @csrf

                <div class="front-form-section-heading">
                    <span>@</span>
                    <div>
                        <h2>Nova mensagem</h2>
                        <p>Selecione o destinatário e escreva o conteúdo.</p>
                    </div>
                </div>

                <div class="front-form-grid">
                    <label class="front-field front-field-wide">
                        <span>Destinatário</span>
                        <select name="usuario" required>
                            <option value="">Selecione um usuário</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" @selected((string) old('usuario') === (string) $usuario->id)>
                                    {{ $usuario->nome }} — {{ $usuario->email }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="front-field front-field-wide">
                        <span>Assunto</span>
                        <input name="assunto" value="{{ old('assunto') }}" placeholder="Assunto da mensagem" required>
                    </label>

                    <label class="front-field front-field-full">
                        <span>Conteúdo do e-mail</span>
                        <textarea name="conteudo" rows="9" placeholder="Escreva sua mensagem..." required>{{ old('conteudo') }}</textarea>
                    </label>
                </div>

                <div class="front-form-actions">
                    <a href="{{ route('dashboard') }}" class="front-button front-button-ghost">Cancelar</a>
                    <button type="submit" class="front-button front-button-primary">Enviar mensagem</button>
                </div>
            </form>
        </div>
    </main>

    <x-footer />
</x-app-layout>
