<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-form-shell">
            {{-- Navegação --}}
            <div class="front-breadcrumb">
                <a href="{{ route('produtos.manage') }}">Meus produtos</a>
                <span>/</span>
                <span>{{ $editando ? 'Editar' : 'Novo' }}</span>
            </div>

            {{-- Formulário do produto --}}
            <form action="{{ route('produtos.manage') }}" method="GET" class="front-form-card">
                <div class="front-form-section-heading">
                    <span>{{ $editando ? 'E' : '+' }}</span>
                    <div>
                        <h1>{{ $editando ? 'Editar produto' : 'Cadastrar produto' }}</h1>
                        <p>Preencha os dados do anúncio e confira as informações antes de salvar.</p>
                    </div>
                </div>

                <div class="front-form-grid">
                    {{-- Nome --}}
                    <label class="front-field front-field-wide">
                        <span>Nome</span>
                        <input name="nome" value="{{ old('nome', $editando ? $produto->nome : '') }}" required>
                    </label>

                    {{-- Categoria --}}
                    <label class="front-field">
                        <span>Categoria</span>
                        <select name="categoria_id" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected((string) old('categoria_id', $editando ? $produto->categoria_id : '') === (string) $categoria->id)>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    {{-- Preço --}}
                    <label class="front-field">
                        <span>Preço</span>
                        <input name="preco" inputmode="decimal" value="{{ old('preco', $editando ? number_format((float) $produto->preco, 2, ',', '.') : '') }}" placeholder="0,00" required>
                    </label>

                    {{-- Quantidade --}}
                    <label class="front-field">
                        <span>Quantidade</span>
                        <input name="quantidade" type="number" min="1" value="{{ old('quantidade', $editando ? $produto->quantidade : 1) }}" required>
                    </label>

                    {{-- Foto --}}
                    <label class="front-field">
                        <span>Foto</span>
                        <input name="foto" type="file" accept="image/*">
                    </label>

                    {{-- Descrição --}}
                    <label class="front-field front-field-full">
                        <span>Descrição</span>
                        <textarea name="descricao" rows="6" required>{{ old('descricao', $editando ? $produto->descricao : '') }}</textarea>
                    </label>
                </div>

                {{-- Ações --}}
                <div class="front-form-actions">
                    <a href="{{ route('produtos.manage') }}" class="front-button front-button-ghost">Cancelar</a>
                    <button type="submit" class="front-button front-button-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar produto' }}</button>
                </div>
            </form>
        </div>
    </main>

    <x-footer />
</x-app-layout>